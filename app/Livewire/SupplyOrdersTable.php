<?php

namespace App\Livewire;

use App\Models\SupplyOrder;
use App\Utils\CustomFieldManager;
use Carbon\Carbon;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Validation\Rule;
use Livewire\Component;
use Livewire\WithPagination;
use Storage;
use ZipArchive;

class SupplyOrdersTable extends Component
{
    use WithPagination;

    public $search = '';
    public $orderBy = 'date_desc';
    public $dateRange = '';
    public $tab = 'pending';

    protected $queryString = [
        'search' => ['except' => ''],
        'orderBy' => ['except' => 'date_desc'],
        'dateRange' => ['except' => ''],
        'tab' => ['except' => 'pending'],
    ];

    protected $validOrderByOptions = [
        'date_desc', 'date_asc', 'quantity_low_high', 'quantity_high_low', 'name_asc', 'name_desc'
    ];

    protected $validDateRangeOptions = [
        '', 'today', 'week', 'month', 'year'
    ];

    protected $validTabOptions = [
        'pending', 'received', 'all', ''
    ];

    protected function rules()
    {
        return [
            'search' => 'nullable|string|max:100',
            'orderBy' => ['required', Rule::in($this->validOrderByOptions)],
            'dateRange' => ['nullable', Rule::in($this->validDateRangeOptions)],
            'tab' => ['required', Rule::in($this->validTabOptions)],
        ];
    }

    public function validateInputs()
    {
        $this->validateOnly('search');
        $this->validateOnly('orderBy');
        $this->validateOnly('dateRange');
        $this->validateOnly('tab');
    }

    public function mount()
    {
        $this->validateInputs();
    }

    public function updatedSearch()
    {
        $this->validateOnly('search');
        $this->resetPage();
    }

    public function updatedOrderBy()
    {
        $this->validateOnly('orderBy');
        $this->resetPage();
    }

    public function updatedDateRange()
    {
        $this->validateOnly('dateRange');
        $this->resetPage();
    }

    public function updatedTab()
    {
        $this->validateOnly('tab');
        $this->resetPage();
    }

    public function gotoPage($page)
    {
        $this->setPage((int)$page);
    }

    public function exportReceipts()
    {
        $orders = $this->getOrders();

        if (count($orders) == 0) {
            $this->dispatch('showToast', type: 'error', message: 'No receipts found for the selected orders.');
            return redirect()->back()->with('error', 'No receipts found for the selected orders.');
        }

        // Ensure the storage directory exists
        if (!is_dir(storage_path('app/private/supply_receipts'))) {
            mkdir(storage_path('app/private/supply_receipts'), 0777, true);
        }

        // Create zip file
        $zip = new ZipArchive();
        $zip_name = 'receipts_' . now()->format('Y-m-d_H-i-s') . '.zip';
        $zip_path = Storage::disk('local')->path('receipts/' . $zip_name);
        if ($zip->open($zip_path, \ZipArchive::CREATE | \ZipArchive::OVERWRITE) === true) {
            $hasFiles = false;

            foreach ($orders as $order) {
                $custom = new CustomFieldManager($order);
                $receipt = $custom->get('pdf_receipt');

                if ($receipt) {
                    $path = storage_path('app/private/supply_receipts/' . $receipt);
                    if (file_exists($path)) {
                        $zip->addFile($path, 'receipt_' . $order->id . '_' . basename($receipt));
                        $hasFiles = true;
                    }
                }
            }

            $zip->close();

            if (!$hasFiles) {
                $this->dispatch('showToast', type: 'error', message: 'No receipts found for the selected orders.');
                return redirect()->back()->with('error', 'No receipts found for the selected orders.');
            }

            $this->dispatch('showToast', type: 'success', message: 'Receipts exported successfully.');
            return response()->download($zip_path)->deleteFileAfterSend(true);
        } else {
            $this->dispatch('showToast', type: 'error', message: 'Could not create zip file.');
            return redirect()->back()->with('error', 'Could not create zip file.');
        }
    }

    public function render()
    {
        $this->validateInputs();

        $supplyOrders = $this->getOrders();

        foreach ($supplyOrders as $supplyOrder) {
            $custom = new CustomFieldManager($supplyOrder);

            $supplyOrder->setAttribute('pdf_receipt', $custom->get('pdf_receipt'));

            if (!$custom->exists()) {
                $supplyOrder->setAttribute('delivered_at', "-");
                continue;
            }

            if (($delivered_at = $custom->get('delivered_at')) != null) {
                $delivered_at = Carbon::parse($delivered_at)->format('d/m/Y H:i:s');
                $supplyOrder->setAttribute('delivered_at', $delivered_at);
                continue;
            }

            if (($delivered_at = $custom->get('expected_delivery_date')) != null) {
                $delivered_at = "Expected: " . Carbon::parse($delivered_at)->format('d/m/Y H:i:s');
                $supplyOrder->setAttribute('delivered_at', $delivered_at);
                continue;
            }

            $supplyOrder->setAttribute('delivered_at', '-');
        }

        return view('livewire.supply-orders-table', [
            'supplyOrders' => $supplyOrders,
        ]);
    }


    /**
     * @return LengthAwarePaginator
     */
    private function getOrders(): \Illuminate\Pagination\LengthAwarePaginator
    {
        $query = SupplyOrder::query();

        if ($this->search) {
            $sanitizedSearch = e($this->search);
            $query->whereHas('product', function ($q) use ($sanitizedSearch) {
                $q->where('name', 'like', '%' . $sanitizedSearch . '%');
            });
        }

        if ($this->tab == 'pending') {
            $query->where('status', SupplyOrder::STATUS_PENDING);
        } elseif ($this->tab == 'received') {
            $query->where('status', SupplyOrder::STATUS_COMPLETED);
        }

        // Apply same ordering as in the table
        switch ($this->orderBy) {
            case 'date_asc':
                $query->orderBy('supply_orders.created_at');
                break;
            case 'quantity_low_high':
                $query->orderBy('quantity');
                break;
            case 'quantity_high_low':
                $query->orderBy('quantity', 'desc');
                break;
            case 'name_asc':
                $query->join('products', 'products.id', '=', 'supply_orders.product_id')
                    ->orderBy('products.name');
                break;
            case 'name_desc':
                $query->join('products', 'products.id', '=', 'supply_orders.product_id')
                    ->orderBy('products.name', 'desc');
                break;
            default: // date_desc
                $query->orderBy('supply_orders.created_at', 'desc');
                break;
        }

        if ($this->dateRange == 'today') {
            $query->whereDate('supply_orders.created_at', today());
        } elseif ($this->dateRange == 'week') {
            $query->whereBetween('supply_orders.created_at', [now()->startOfWeek(), now()->endOfWeek()]);
        } elseif ($this->dateRange == 'month') {
            $query->whereMonth('supply_orders.created_at', now()->month);
        } elseif ($this->dateRange == 'year') {
            $query->whereYear('supply_orders.created_at', now()->year);
        }
        return $query->paginate(50);
    }
}