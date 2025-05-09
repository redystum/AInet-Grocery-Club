<?php

namespace App\Livewire;

use App\Models\SupplyOrder;
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
        $this->setPage((int) $page);
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
                $query->orderBy('created_at', 'desc');
                break;
            case 'date_asc':
                $query->orderBy('created_at', 'asc');
                break;
            case 'stock_low_high':
                $query->orderBy('quantity', 'asc');
                break;
            case 'stock_high_low':
                $query->orderBy('quantity', 'desc');
                break;
            case 'name_asc':
                $query->join('products', 'products.id', '=', 'supply_orders.product_id')
                    ->orderBy('products.name', 'asc');
                break;
            case 'name_desc':
                $query->join('products', 'products.id', '=', 'supply_orders.product_id')
                    ->orderBy('products.name', 'desc');
                break;
            case 'price_low_high':
                $query->orderBy('price', 'asc');
                break;
            case 'price_high_low':
                $query->orderBy('price', 'desc');
                break;
        }

        if ($this->dateRange == 'today') {
            $query->whereDate('created_at', today());
        } elseif ($this->dateRange == 'week') {
            $query->whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()]);
        } elseif ($this->dateRange == 'month') {
            $query->whereMonth('created_at', now()->month);
        } elseif ($this->dateRange == 'year') {
            $query->whereYear('created_at', now()->year);
        }

        $supplyOrders = $query->paginate(100);

        return view('livewire.supply-orders-table', [
            'supplyOrders' => $supplyOrders,
        ]);
    }
}
