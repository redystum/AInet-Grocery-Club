<?php

namespace App\Livewire;

use App\Models\SupplyOrder;
use Carbon\Carbon;
use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Validation\Rule;

class SupplyOrdersTable extends Component
{
    use WithPagination;

    public $search = '';
    public $orderBy = 'date_asc';
    public $dateRange = '';
    public $tab = 'pending';

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

    public function render()
    {
        $this->validateInputs();

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

        switch ($this->orderBy) {
            case 'date_desc':
                $query->orderBy('supply_orders.created_at', 'desc');
                break;
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

        $supplyOrders = $query->paginate(50);

        foreach ($supplyOrders as $supplyOrder) {
            $custom = json_decode($supplyOrder->custom, true);
            if ($custom == null) {
                $supplyOrder->setAttribute('delivered_at', "-");
                continue;
            }

            $delivered_at = $custom['delivered_at'] ?? null;
            if ($delivered_at != null) {
                $delivered_at = Carbon::parse($delivered_at)->format('d/m/Y H:i:s');
                $supplyOrder->setAttribute('delivered_at', $delivered_at);
                continue;
            }

            $delivered_at = $custom['expected_delivery_date'] ?? null;
            if ($delivered_at != null) {
                $delivered_at = "Expected: " . Carbon::parse($supplyOrder->created_at)->format('d/m/Y H:i:s');
                $supplyOrder->setAttribute('delivered_at', $delivered_at);
                continue;
            }

            $supplyOrder->setAttribute('delivered_at', '-');
        }

        return view('livewire.supply-orders-table', [
            'supplyOrders' => $supplyOrders,
        ]);
    }
}
