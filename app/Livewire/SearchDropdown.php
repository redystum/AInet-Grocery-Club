<?php

namespace App\Livewire;

use App\Models\Category;
use App\Models\Product;
use Livewire\Component;

class SearchDropdown extends Component
{
    public $query = '';
    public $results = [];
    public $selectedIndex = -1;

    // Clear input and results on escape or reset
    public function resetInput()
    {
        $this->query = '';
        $this->results = [];
        $this->selectedIndex = -1;
    }

    public function updatedQuery()
    {
        $this->updateResults();
        // Reset selected index when query changes
        $this->selectedIndex = -1;
    }

    public function updateResults()
    {
        if (strlen($this->query) < 1) {
            $this->results = [];
            return;
        }

        $pages = [
            ['name' => 'Home', 'url' => route('home'), 'icon' => 'fa-home'],
            ['name' => 'Products', 'url' => route('products.index'), 'icon' => 'fa-box'],
            ['name' => 'Cart', 'url' => route('cart'), 'icon' => 'fa-shopping-cart'],
        ];

        if (Auth()->check()) {
            $pages[] = ['name' => 'Profile', 'url' => route('profile'), 'icon' => 'fa-user-circle'];
            $pages[] = ['name' => 'Dashboard', 'url' => route('dashboard'), 'icon' => 'fa-dashboard'];
            $pages[] = ['name' => 'Card', 'url' => route('card.index'), 'icon' => 'fa-card'];
            if (Auth()->user()->isBoard()) {
                $pages[] = ['name' => 'Management', 'url' => "#" /*route('dashboard')*/, 'icon' => 'fa-boxes'];
            }
        }

        // Search products and categories
        $products = Product::where('name', 'like', "%{$this->query}%")->limit(5)->get();
        $categories = Category::where('name', 'like', "%{$this->query}%")->limit(5)->get();


        $query = strtolower($this->query);
        $filteredPages = collect($pages)->filter(function ($page) use ($query) {
            return str_contains(strtolower($page['name']), $query);
        })->values();


        $mappedProducts = $products->map(fn($p) => [
            'type' => 'product - ' . $p->category->name,
            'name' => $p->name,
            'url' => route('product.show', $p->id),
            'image' => $p->getImage(),
        ]);

        $mappedCategories = $categories->map(fn($c) => [
            'type' => 'category',
            'name' => $c->name,
            'url' => route('products.category', $c->id),
            'image' => $c->getImage(),
        ]);

        // Add pages to the results
        $mappedPages = collect($filteredPages)->map(fn($p) => [
            'type' => 'page',
            'name' => $p['name'],
            'url' => $p['url'],
            'icon' => $p['icon'],
        ]);

        // Combine and take top 5
        $this->results = collect($mappedProducts)->merge($mappedCategories)->merge($mappedPages)
            ->take(5)
            ->sortBy(fn($result) => stripos($result['name'], $this->query) === 0 ? 0 : 1)
            ->values()
            ->all();
    }

    // Navigate to previous item
    public function incrementIndex()
    {
        if (count($this->results) > 0) {
            $this->selectedIndex = $this->selectedIndex === count($this->results) - 1 ? 0 : $this->selectedIndex + 1;
        }
    }

    // Navigate to next item
    public function decrementIndex()
    {
        if (count($this->results) > 0) {
            $this->selectedIndex = $this->selectedIndex === 0 || $this->selectedIndex === -1 ? count($this->results) - 1 : $this->selectedIndex - 1;
        }
    }

    // Select the currently highlighted item
    public function selectItem(int $index = null)
    {
        if ($index !== null) {
            $this->selectedIndex = $index;
            return redirect()->to($this->results[$this->selectedIndex]['url']);
        }

        if ($this->selectedIndex >= 0 && $this->selectedIndex < count($this->results)) {
            return redirect()->to($this->results[$this->selectedIndex]['url']);
        }

        if ($this->selectedIndex === -1 && count($this->results) === 1) {
            return redirect()->to($this->results[0]['url']);
        }
    }

    // Reset selection and return focus to search input
    public function resetSelection()
    {
        $this->selectedIndex = -1;
    }

    public function render()
    {
        return view('livewire.search-dropdown');
    }
}