<?php

namespace App\Livewire;

use App\Models\SupplyOrder;
use Carbon\Carbon;
use Livewire\Component;
use Livewire\WithPagination;

class SupplyOrdersTable extends Component
{
    use WithPagination;

    public $search = '';
    public $orderBy = 'date_asc';
    public $dateRange = '';
    public $tab = 'pending';

    protected $queryString = [
        'search' => ['except' => ''],
        'orderBy' => ['except' => 'date_asc'],
        'dateRange' => ['except' => ''],
        'tab' => ['except' => 'pending'],
    ];

    public function updatedSearch()
    {
        $this->resetPage();
    }

    public function updatedOrderBy()
    {
        $this->resetPage();
    }

    public function updatedDateRange()
    {
        $this->resetPage();
    }

    public function updatedTab()
    {
        $this->resetPage();
    }

    public function gotoPage($page)
    {
        $this->setPage((int)$page);
    }

    public function render()
    {
        $query = SupplyOrder::query();

        if ($this->search) {
            $query->whereHas('product', function ($q) {
                $q->where('name', 'like', '%' . $this->search . '%');
            });
        }

        if ($this->tab == 'pending') {
            $query->where('status', SupplyOrder::STATUS_PENDING);
        } elseif ($this->tab == 'received') {
            $query->where('status', SupplyOrder::STATUS_COMPLETED);
        }

        // Apply ordering
        switch ($this->orderBy) {
            case 'date_desc':
                $query->orderBy('supply_orders.created_at', 'desc'); // Specify table name
                break;
            case 'date_asc':
                $query->orderBy('supply_orders.created_at'); // Specify table name
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
