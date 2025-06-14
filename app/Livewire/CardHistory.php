<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Facades\Auth;

class CardHistory extends Component
{
    use WithPagination;

    public $type = '';
    public $start_date = '';
    public $end_date = '';
    public $page = 1;
    public $user;

    public function mount($user = null)
    {
        $this->user = $user ?: Auth::user();
    }

    protected $queryString = [
        'type' => ['except' => ''],
        'start_date' => ['except' => ''],
        'end_date' => ['except' => ''],
        'page' => ['except' => 1],
    ];

    public function updating($property)
    {
        if (in_array($property, ['type', 'start_date', 'end_date'])) {
            $this->resetPage();
        }
    }

    public function goToPage($page)
    {
        $this->page = $page;
    }
    
    public function gotoFirstPage()
    {
        $this->setPage(1);
    }
    
    public function gotoLastPage($lastPage)
    {
        $this->setPage($lastPage);
    }
    
    public function previousPage()
    {
        $this->setPage(max($this->page - 1, 1));
    }
    
    public function nextPage()
    {
        $this->setPage($this->page + 1);
    }

    public function setPage($page)
    {
        $this->page = $page;
    }

    public function render()
    {

        $query = $this->user->card->operations()->orderByDesc('date');

        if ($this->type) {
            if ($this->type == 'order') {
                $query->where('debit_type', 'order');
            } elseif ($this->type == 'deposit' || $this->type == 'payment') {
                $query->where('credit_type', 'payment');
            } elseif ($this->type == 'refund') {
                $query->where('credit_type', 'order_cancellation');
            } elseif ($this->type == 'membership_fee') {
                $query->where('debit_type', 'membership_fee');
            }
        }

        if ($this->start_date) {
            $query->whereDate('created_at', '>=', $this->start_date);
        }
        if ($this->end_date) {
            $query->whereDate('created_at', '<=', $this->end_date);
        }

        $operations = $query->paginate(10, ['*'], 'page', $this->page);
        
        // Ensure the requested page does not exceed the last page
        if ($this->page > $operations->lastPage()) {
            $this->page = $operations->lastPage();
            $operations = $query->paginate(10, ['*'], 'page', $this->page);
        }

        // Calculate running balance for each operation
        if ($operations->count() > 0) {
            $currentBalance = $this->user->card->balance;
            $operationsCollection = $operations->getCollection();
            
            // Sort operations by date and ID in descending order (newest first)
            $sortedOperations = $operationsCollection->sortByDesc(function($op) {
                return [$op->created_at->format('Y-m-d'), $op->id];
            })->values();
            
            // Calculate running balances starting from the current balance
            foreach ($sortedOperations as $index => $operation) {
                // For the first (newest) item, start with current balance
                if ($index === 0) {
                    $operation->running_balance = $currentBalance;
                    continue;
                }
                
                // For subsequent items, subtract or add the operation value
                $previousOperation = $sortedOperations[$index-1];
                if ($operation->type === 'credit') {
                    $operation->running_balance = $previousOperation->running_balance - $operation->value;
                } else { // debit
                    $operation->running_balance = $previousOperation->running_balance + $operation->value;
                }
            }
            
            // Re-sort operations to original order if needed
            $operations->setCollection($sortedOperations);
        }

        return view('livewire.card-history', [
            'operations' => $operations,
            'currentBalance' => $this->user->card->balance ?? 0,
        ]);
    }
}
