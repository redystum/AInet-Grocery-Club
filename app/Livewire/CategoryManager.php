<?php

namespace App\Livewire;

use App\Models\Category;
use App\Utils\ToastCreator;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\WithPagination;

class CategoryManager extends Component
{
    use WithPagination, WithFileUploads;

    public $search = '';
    public $order_by = 'name_asc';

    // Propriedades para edição
    public $editingCategoryId = null;
    public $name = '';
    public $image;
    public $tempImage;

    // Propriedades para exclusão
    public $categoryToDelete = null;

    protected $listeners = ['refreshComponent' => '$refresh'];

    protected $rules = [
        'name' => 'required|string|max:255',
        'tempImage' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
    ];

    public function render()
    {
        $categories = Category::withCount('products')
            ->when($this->search, function ($query) {
                $query->where('name', 'like', '%' . $this->search . '%');
            })
            ->when($this->order_by, function ($query) {
                switch ($this->order_by) {
                    case 'name_asc':
                        $query->orderBy('name', 'asc');
                        break;
                    case 'name_desc':
                        $query->orderBy('name', 'desc');
                        break;
                    case 'products_high_low':
                        $query->orderBy('products_count', 'desc');
                        break;
                    case 'products_low_high':
                        $query->orderBy('products_count', 'asc');
                        break;
                    case 'newest':
                        $query->orderBy('created_at', 'desc');
                        break;
                    case 'oldest':
                        $query->orderBy('created_at', 'asc');
                        break;
                    default:
                        $query->orderBy('name', 'asc');
                }
            })
            ->paginate(10);

        return view('livewire.category-manager', compact('categories'));
    }

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function startEditing($categoryId)
    {
        $this->editingCategoryId = $categoryId;
        $category = Category::find($categoryId);
        $this->name = $category->name;
        $this->image = $category->image;
    }

    public function cancelEditing()
    {
        $this->reset(['editingCategoryId', 'name', 'image', 'tempImage']);
        $this->resetValidation();
    }

    public function updateCategory()
    {
        $category = Category::find($this->editingCategoryId);

        $this->validate([
            'name' => 'required|string|max:255|unique:categories,name,' . $category->id,
            'tempImage' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        // Handle image upload if new image is provided
        if ($this->tempImage) {
            // Delete old image
            if ($category->image) {
                Storage::delete('public/categories/' . $category->image);
            }

            $filename = Str::slug($this->name) . '-' . time() . '.' . $this->tempImage->getClientOriginalExtension();
            $this->tempImage->storeAs('public/categories', $filename);
            $category->image = $filename;
        }

        $category->name = $this->name;
        $category->save();

        $this->cancelEditing();
        ToastCreator::success('Category updated successfully.');
    }

    public function confirmDelete($categoryId)
    {
        $this->categoryToDelete = Category::find($categoryId);
    }

    public function cancelDelete()
    {
        $this->categoryToDelete = null;
    }

    public function deleteCategory()
    {
        if ($this->categoryToDelete->products()->exists()) {
            ToastCreator::error('Cannot delete category with associated products.');
            $this->categoryToDelete = null;
            return;
        }

        // Delete associated image
        if ($this->categoryToDelete->image) {
            Storage::delete('public/categories/' . $this->categoryToDelete->image);
        }

        $this->categoryToDelete->delete();
        $this->categoryToDelete = null;

        ToastCreator::success('Category deleted successfully.');
    }
}
