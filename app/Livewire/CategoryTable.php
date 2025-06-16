<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Category;
use Illuminate\Support\Facades\Storage;
use Livewire\WithFileUploads;

class CategoryTable extends Component
{
    use WithPagination, WithFileUploads;

    public $search = '';
    public $sortField = 'name';
    public $sortDirection = 'asc';
    public $perPage = 10;

    public $showDeleteModal = false;
    public $deleteCategoryId;
    public $deleteCategoryName;

    public function sortBy($field)
    {
        if ($this->sortField === $field) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortDirection = 'asc';
        }
        $this->sortField = $field;
    }

    public function confirmDelete($categoryId)
    {
        $category = Category::findOrFail($categoryId);
        $this->deleteCategoryId = $category->id;
        $this->deleteCategoryName = $category->name;
        $this->showDeleteModal = true;
    }

    public function deleteCategory()
    {
        $category = Category::findOrFail($this->deleteCategoryId);
        $category->delete();

        $this->showDeleteModal = false;
        session()->flash('success', 'Category deleted successfully!');
    }

    public function render()
    {
        $categories = Category::query()
            ->when($this->search, function ($query) {
                return $query->where('name', 'like', '%' . $this->search . '%');
            })
            ->withCount('products')
            ->orderBy($this->sortField, $this->sortDirection)
            ->paginate($this->perPage);

        return view('livewire.category-table', [
            'categories' => $categories
        ]);
    }
}
