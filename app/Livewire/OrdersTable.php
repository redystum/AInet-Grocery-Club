<?php

namespace App\Livewire;

use App\Models\Order;
use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Validation\Rule;
use App\Utils\CustomFieldManager;

class OrdersTable extends Component
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
        'date_desc', 'date_asc', 'user_asc', 'user_desc', 'price_desc', 'price_asc', 'requests'
    ];

    protected $validDateRangeOptions = [
        '', 'today', 'week', 'month', 'year'
    ];

    protected $validTabOptions = [
        'pending', 'received', 'all', 'cancellation', ''
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
        $this->search = request()->query('search', $this->search);
        $this->orderBy = request()->query('orderBy', $this->orderBy);
        $this->dateRange = request()->query('dateRange', $this->dateRange);
        $this->tab = request()->query('tab', $this->tab);

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
        if ($this->tab == 'cancellation') {
            $this->orderBy = 'requests';
        } elseif ($this->orderBy == 'requests') {
            $this->orderBy = 'date_desc';
        }
        $this->resetPage();
    }

    public function gotoPage($page)
    {
        $this->setPage((int)$page);
    }

    public function render()
    {
        $this->validateInputs();

        $query = Order::query()->select('orders.*')->with(['user', 'items.product']);

        if ($this->search) {
            $sanitizedSearch = trim(e($this->search));

            $query->where(function ($q) use ($sanitizedSearch) {
                $q->whereHas('items.product', function ($q) use ($sanitizedSearch) {
                    $q->where('name', 'like', '%' . $sanitizedSearch . '%');
                })
                    ->orWhereHas('user', function ($q) use ($sanitizedSearch) {
                        $q->where('name', 'like', '%' . $sanitizedSearch . '%');
                    })
                    ->orWhere('id', 'like', '%' . $sanitizedSearch . '%');
            });
        }

        if ($this->tab == 'pending') {
            $query->where('status', Order::STATUS_PENDING);
//                  ->whereRaw("(JSON_EXTRACT(custom, '$.cancellationStatus') IS NULL OR JSON_EXTRACT(custom, '$.cancellationStatus') != ?)", [Order::CANCEL_STATUS_PENDING]);
        } elseif ($this->tab == 'received') {
            $query->where('status', Order::STATUS_COMPLETED);
        } elseif ($this->tab == 'cancellation') {
            $query->where(function ($q) {
                $q->where('status', Order::STATUS_CANCELED)
                  ->orWhereRaw("JSON_EXTRACT(custom, '$.cancellationStatus') = ?", [Order::CANCEL_STATUS_PENDING]);
            });
        }

        switch ($this->orderBy) {
            case 'date_dsc':
            case 'date_desc':
                $query->orderBy('orders.created_at', 'desc');
                break;
            case 'user_asc':
                $query->join('users', 'users.id', '=', 'orders.member_id')
                    ->orderBy('users.name');
                break;
            case 'user_desc':
                $query->join('users', 'users.id', '=', 'orders.member_id')
                    ->orderBy('users.name', 'desc');
                break;
            case 'price_asc':
                $query->orderBy('orders.total');
                break;
            case 'price_desc':
                $query->orderBy('orders.total', 'desc');
                break;
            case 'requests':
                // Order by cancellation status first (pending cancellations first)
                $query->orderByRaw("CASE 
                    WHEN JSON_EXTRACT(custom, '$.cancellationStatus') = ? THEN 0
                    WHEN status = ? THEN 1
                    ELSE 2 
                    END", [Order::CANCEL_STATUS_PENDING, Order::STATUS_CANCELED])
                    ->orderBy('orders.created_at', 'desc');
                break;
            default: // date_asc
                $query->orderBy('orders.created_at');
                break;
        }

        if ($this->dateRange == 'today') {
            $query->whereDate('orders.created_at', today());
        } elseif ($this->dateRange == 'week') {
            $query->whereBetween('orders.created_at', [now()->startOfWeek(), now()->endOfWeek()]);
        } elseif ($this->dateRange == 'month') {
            $query->whereMonth('orders.created_at', now()->month);
        } elseif ($this->dateRange == 'year') {
            $query->whereYear('orders.created_at', now()->year);
        }

        $orders = $query->paginate(50);

        foreach ($orders as $order) {
            CustomFieldManager::self_custom_to_attribute($order);

            if ($this->tab == "cancellation" && $order->cancellationStatus && $order->cancellationStatus == Order::CANCEL_STATUS_REFUSED) {
                $orders->forget($orders->search($order));
                continue;
            }

            // Calculate totals
            $items_count = 0;

            $can_be_delivered = true;

            foreach ($order->items as $item) {
                $items_count += $item->quantity;
                if ($item->product->stock < $item->quantity) {
                    $can_be_delivered = false;
                }
            }

            $order->setAttribute("items_count", $items_count);
            $order->setAttribute("can_be_delivered", $can_be_delivered);

        }

        return view('livewire.orders-table', [
            'orders' => $orders,
        ]);
    }
}
