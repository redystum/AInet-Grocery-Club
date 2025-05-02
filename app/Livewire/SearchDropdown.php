<?php

namespace App\Livewire;

use Illuminate\Support\Facades\Log;
use Livewire\Component;
use App\Models\Product;
use App\Models\Category;

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
            ['name' => 'About Us', 'url' => "#" /*route('about')*/, 'icon' => 'fa-info-circle'],
            ['name' => 'Contact Us', 'url' => "#" /*route('contact')*/, 'icon' => 'fa-phone'],
            ['name' => 'Cart', 'url' => "#", 'icon' => 'fa-shopping-cart'],
        ];

        if (Auth()->check()) {
            $pages[] = ['name' => 'Profile', 'url' => route('profile'), 'icon' => 'fa-user-circle'];
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
            'url' => "#",
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
    public function selectItem()
    {
        if ($this->selectedIndex >= 0 && $this->selectedIndex < count($this->results)) {
            return redirect()->to($this->results[$this->selectedIndex]['url']);
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