<?php

namespace App\Livewire;

use App\Models\Settings;
use App\Models\ShippingCosts;
use Livewire\Component;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Validate;

class AdminSettings extends Component
{
    public $membershipFee;
    public $deliveryCosts = [];

    #[Validate('required|numeric|min:0')]
    public $newMinAmount;
    
    #[Validate('required|numeric|min:0')]
    public $newCost;

    public $editingIndex = null;
    public $editingId = null;
    
    public $editingMinAmount;
    
    #[Validate('required|numeric|min:0')]
    public $editingCost;

    public $confirmingDeleteIndex = null;
    public $confirmingDeleteId = null;

    public $notification = null;
    public $notificationType = 'success';

    protected $rules = [
        'membershipFee' => 'required|numeric|min:0',
    ];

    public function mount()
    {
        $this->loadSettings();
    }

    /**
     * Load settings from database
     */
    private function loadSettings()
    {
        $settings = Settings::get();
        $this->membershipFee = $settings ? $settings->membership_fee : 10.00;
        
        $shippingCosts = ShippingCosts::orderBy('min_value_threshold', 'asc')->get();
        
        if ($shippingCosts->count() > 0) {
            $this->deliveryCosts = $shippingCosts->map(function($cost) {
                return [
                    'id' => $cost->id,
                    'min_amount' => (float) $cost->min_value_threshold,
                    'cost' => (float) $cost->shipping_cost,
                ];
            })->toArray();
        } else {
            $this->deliveryCosts = [];
        }
    }

    /**
     * Sort delivery costs by minimum amount
     */
    private function sortDeliveryCosts()
    {
        usort($this->deliveryCosts, function($a, $b) {
            return $a['min_amount'] <=> $b['min_amount'];
        });
    }

    /**
     * Show notification to user
     */
    private function notify($message, $type = 'success')
    {
        $this->notification = $message;
        $this->notificationType = $type;
        
        // Auto-dismiss notification after 5 seconds
        $this->dispatch('dismiss-notification', delay: 5000);
    }

    /**
     * Update membership fee
     */
    public function updateMembershipFee()
    {
        $this->validate(['membershipFee' => 'required|numeric|min:0']);

        try {
            DB::beginTransaction();
            
            Settings::set((float) $this->membershipFee, []);
            
            DB::commit();
            $this->notify('Membership fee updated successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            $this->notify('Failed to update membership fee: ' . $e->getMessage(), 'error');
        }
    }

    /**
     * Add new delivery cost entry
     */
    public function addDeliveryCost()
    {
        $this->validate([
            'newMinAmount' => 'required|numeric|min:0',
            'newCost' => 'required|numeric|min:0',
        ]);

        // Check for duplicate minimum amount
        $duplicate = false;
        foreach ($this->deliveryCosts as $cost) {
            if ($cost['min_amount'] == $this->newMinAmount) {
                $duplicate = true;
                break;
            }
        }

        if ($duplicate) {
            $this->notify('A delivery cost with this minimum amount already exists.', 'error');
            return;
        }

        try {
            DB::beginTransaction();
            
            // Calculate max_value_threshold as infinity or find next threshold
            $maxThreshold = $this->calculateMaxThreshold((float)$this->newMinAmount);
            
            $shippingCost = new ShippingCosts();
            $shippingCost->min_value_threshold = (float) $this->newMinAmount;
            $shippingCost->max_value_threshold = $maxThreshold;
            $shippingCost->shipping_cost = (float) $this->newCost;
            $shippingCost->save();
            
            $this->deliveryCosts[] = [
                'id' => $shippingCost->id,
                'min_amount' => (float) $this->newMinAmount,
                'cost' => (float) $this->newCost,
            ];

            $this->sortDeliveryCosts();
            $this->updateMaxThresholds();
            
            DB::commit();
            $this->reset(['newMinAmount', 'newCost']);
            $this->notify('Delivery cost added successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            $this->notify('Failed to add delivery cost: ' . $e->getMessage(), 'error');
        }
    }

    /**
     * Prepare to edit a delivery cost entry
     */
    public function editDeliveryCost($index)
    {
        $this->editingIndex = $index;
        $this->editingId = $this->deliveryCosts[$index]['id'] ?? null;
        $this->editingMinAmount = $this->deliveryCosts[$index]['min_amount'];
        $this->editingCost = $this->deliveryCosts[$index]['cost'];
    }

    /**
     * Update a delivery cost entry
     */
    public function updateDeliveryCost()
    {
        $this->validate([
            'editingMinAmount' => 'required|numeric|min:0',
            'editingCost' => 'required|numeric|min:0',
        ]);

        // Check for duplicate minimum amount (excluding current item)
        $duplicate = false;
        foreach ($this->deliveryCosts as $index => $cost) {
            if ($index !== $this->editingIndex && $cost['min_amount'] == $this->editingMinAmount) {
                $duplicate = true;
                break;
            }
        }

        if ($duplicate) {
            $this->notify('A delivery cost with this minimum amount already exists.', 'error');
            return;
        }

        try {
            DB::beginTransaction();
            
            // Calculate max threshold for this entry
            $maxThreshold = $this->calculateMaxThreshold((float)$this->editingMinAmount, $this->editingIndex);
            
            if (isset($this->editingId)) {
                $shippingCost = ShippingCosts::find($this->editingId);
                if ($shippingCost) {
                    $shippingCost->min_value_threshold = (float) $this->editingMinAmount;
                    $shippingCost->max_value_threshold = $maxThreshold;
                    $shippingCost->shipping_cost = (float) $this->editingCost;
                    $shippingCost->save();
                }
            } else {
                $shippingCost = ShippingCosts::create([
                    'min_value_threshold' => (float) $this->editingMinAmount,
                    'max_value_threshold' => $maxThreshold,
                    'shipping_cost' => (float) $this->editingCost,
                ]);
                $this->deliveryCosts[$this->editingIndex]['id'] = $shippingCost->id;
            }
            
            $this->deliveryCosts[$this->editingIndex] = [
                'id' => $shippingCost->id ?? $this->deliveryCosts[$this->editingIndex]['id'] ?? null,
                'min_amount' => (float) $this->editingMinAmount,
                'cost' => (float) $this->editingCost,
            ];

            $this->sortDeliveryCosts();
            $this->updateMaxThresholds();
            
            DB::commit();
            $this->cancelEdit();
            $this->notify('Delivery cost updated successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            $this->notify('Failed to update delivery cost: ' . $e->getMessage(), 'error');
        }
    }

    /**
     * Cancel the edit operation
     */
    public function cancelEdit()
    {
        $this->editingIndex = null;
        $this->editingId = null;
        $this->reset(['editingMinAmount', 'editingCost']);
    }

    /**
     * Confirm deletion of a delivery cost entry
     */
    public function confirmDelete($index)
    {
        $this->confirmingDeleteIndex = $index;
        $this->confirmingDeleteId = $this->deliveryCosts[$index]['id'] ?? null;
    }

    /**
     * Delete a delivery cost entry
     */
    public function deleteDeliveryCost()
    {
        try {
            DB::beginTransaction();
            
            if (isset($this->confirmingDeleteId)) {
                $shippingCost = ShippingCosts::find($this->confirmingDeleteId);
                if ($shippingCost) {
                    $shippingCost->delete();
                }
            }
            
            // Remove from array
            array_splice($this->deliveryCosts, $this->confirmingDeleteIndex, 1);
            
            $this->updateMaxThresholds();
            
            DB::commit();
            $this->confirmingDeleteIndex = null;
            $this->confirmingDeleteId = null;
            $this->notify('Delivery cost deleted successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            $this->notify('Failed to delete delivery cost: ' . $e->getMessage(), 'error');
        }
    }

    /**
     * Calculate the max threshold for a delivery cost entry
     * 
     * @param float $minThreshold The minimum threshold for this entry
     * @param int|null $excludeIndex Index to exclude from calculation (for updates)
     * @return float The max threshold value
     */
    private function calculateMaxThreshold(float $minThreshold, ?int $excludeIndex = null): float
    {
        $maxThreshold = 9999999.99;
        
        $tempDeliveryCosts = $this->deliveryCosts;
        usort($tempDeliveryCosts, function($a, $b) {
            return $a['min_amount'] <=> $b['min_amount'];
        });
        
        foreach ($tempDeliveryCosts as $index => $cost) {
            if ($excludeIndex !== null && $index === $excludeIndex) {
                continue;
            }
            
            if ($cost['min_amount'] > $minThreshold) {
                $maxThreshold = $cost['min_amount'];
                break;
            }
        }
        
        return $maxThreshold;
    }

    /**
     * Update max thresholds for all delivery costs
     */
    private function updateMaxThresholds()
    {
        if (empty($this->deliveryCosts)) {
            return;
        }
        
        $this->sortDeliveryCosts();
        
        for ($i = 0; $i < count($this->deliveryCosts); $i++) {
            $currentCost = $this->deliveryCosts[$i];
            
            if (!isset($currentCost['id'])) {
                continue;
            }
            
            $shippingCost = ShippingCosts::find($currentCost['id']);
            if (!$shippingCost) {
                continue;
            }
            
            if ($i === count($this->deliveryCosts) - 1) {
                $shippingCost->max_value_threshold = 9999999.99;
            } else {
                $shippingCost->max_value_threshold = $this->deliveryCosts[$i + 1]['min_amount'];
            }
            
            $shippingCost->save();
        }
    }

    /**
     * Dismiss notification
     */
    public function dismissNotification()
    {
        $this->notification = null;
    }

    public function render()
    {
        return view('livewire.admin-settings');
    }
}
