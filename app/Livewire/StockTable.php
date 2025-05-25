<?php

namespace App\Livewire;

use App\Models\Category;
use App\Models\Product;
use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Validation\Rule;

class StockTable extends Component
{
    use WithPagination;
    
    public $search = '';
    public $category = '';
    public $stock_status = '';
    public $order_by = 'stock_low_high';

    protected $queryString = [
        'search' => ['except' => ''],
        'category' => ['except' => ''],
        'stock_status' => ['except' => ''],
        'order_by' => ['except' => 'stock_low_high'],
    ];
    
    protected $validOrderByOptions = [
        'stock_low_high', 'stock_high_low', 'name_asc', 'name_desc', 'category_asc', 'category_desc', 'price_high_low',
        'price_low_high'
    ];
    
    protected $validStockStatusOptions = [
        '', 'in_stock', 'low_stock', 'out_of_stock'
    ];
    
    protected function rules()
    {
        return [
            'search' => 'nullable|string|max:255',
            'category' => 'nullable|integer|exists:categories,id',
            'stock_status' => ['nullable', Rule::in($this->validStockStatusOptions)],
            'order_by' => ['required', Rule::in($this->validOrderByOptions)],
        ];
    }
    
    public function mount()
    {
        $this->validateInputs();
    }
    
    public function validateInputs()
    {
        $this->validateOnly('search');
        $this->validateOnly('category');
        $this->validateOnly('stock_status');
        $this->validateOnly('order_by');
    }
    
    public function updatingSearch()
    {
        $this->validateOnly('search');
        $this->resetPage();
    }
    
    public function updatingCategory()
    {
        $this->validateOnly('category');
        $this->resetPage();
    }
    
    public function updatingStockStatus()
    {
        $this->validateOnly('stock_status');
        $this->resetPage();
    }
    
    public function updatingOrderBy()
    {
        $this->validateOnly('order_by');
        $this->resetPage();
    }
    
    public function resetFilters()
    {
        $this->reset(['search', 'category', 'stock_status', 'order_by']);
        $this->resetPage();
    }
    
    public function nextPage()
    {
        $this->setPage($this->page + 1);
    }
    
    public function previousPage()
    {
        $this->setPage(max($this->page - 1, 1));
    }
    
    public function gotoPage($page)
    {
        $this->setPage(max(1, min($page, $this->getPageCount())));
    }
    
    private function getPageCount()
    {
        $query = $this->getProductsQuery();
        $total = $query->count();
        return ceil($total / 100);
    }
    
    private function getProductsQuery()
    {
        $this->validateInputs();
        
        $query = Product::query();
        
        if ($this->search) {
            $sanitizedSearch = e($this->search);
            return Product::where('name', 'like', '%' . $sanitizedSearch . '%')
                ->orWhereHas('category', function ($query) use ($sanitizedSearch) {
                    $query->where('name', 'like', '%' . $sanitizedSearch . '%');
                });
        }
        
        if ($this->category) {
            $query->where('category_id', $this->category);
        }
        
        switch ($this->order_by) {
            case 'stock_high_low':
                $query->orderBy('stock', 'desc');
                break;
            case 'name_asc':
                $query->orderBy('name');
                break;
            case 'name_desc':
                $query->orderBy('name', 'desc');
                break;
            case 'category_asc':
                $query->join('categories', 'products.category_id', '=', 'categories.id')
                    ->orderBy('categories.name')
                    ->select('products.*');
                break;
            case 'category_desc':
                $query->join('categories', 'products.category_id', '=', 'categories.id')
                    ->orderBy('categories.name', 'desc')
                    ->select('products.*');
                break;
            case 'price_high_low':
                $query->orderBy('price', 'desc');
                break;
            case 'price_low_high':
                $query->orderBy('price');
                break;
            default: // stock_low_high
                $query->orderBy('stock');
                break;
        }
        
        if ($this->stock_status) {
            if ($this->stock_status === 'in_stock') {
                $query->whereColumn('stock', '>', 'stock_lower_limit');
            } elseif ($this->stock_status === 'low_stock') {
                $query->whereColumn('stock', '<=', 'stock_lower_limit')
                    ->where('stock', '>', 0);
            } elseif ($this->stock_status === 'out_of_stock') {
                $query->where('stock', '=', 0);
            }
        }
        
        return $query;
    }
    
    public function render()
    {
        $categories = Category::all();
        $products = $this->getProductsQuery()->paginate(100);
        
        return view('livewire.stock-table', compact('products', 'categories'));
    }
}
