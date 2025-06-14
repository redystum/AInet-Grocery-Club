<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use App\Utils\ToastCreator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class CategoryController extends Controller
{
    public function index()
    {
        return view('pages.admin.categories.index');
    }

    public function create()
    {
        return view('pages.admin.categories.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:categories,name',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        // Handle image upload
        $image = $request->file('image');
        $filename = Str::slug($validated['name']) . '-' . time() . '.' . $image->getClientOriginalExtension();
        $path = $image->storeAs('public/categories', $filename);
        $validated['image'] = $filename;

        Category::create($validated);

        ToastCreator::success('Category created successfully.');
        return redirect()->route('board.categories');
    }

    public function show(Category $category)
    {
        $category->load('products');
        return view('pages.admin.categories.show', compact('category'));
    }

    public function edit(Category $category)
    {
        return view('pages.admin.categories.edit', compact('category'));
    }

    public function update(Category $category, Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:categories,name,' . $category->id,
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        // Handle image upload if new image is provided
        if ($request->hasFile('image')) {
            // Delete old image
            if ($category->image) {
                Storage::delete('public/categories/' . $category->image);
            }

            $image = $request->file('image');
            $filename = Str::slug($validated['name']) . '-' . time() . '.' . $image->getClientOriginalExtension();
            $path = $image->storeAs('public/categories', $filename);
            $validated['image'] = $filename;
        }

        $category->update($validated);

        ToastCreator::success('Category updated successfully.');
        return redirect()->route('board.categories');
    }

    public function destroy(Category $category)
    {
        if ($category->products()->exists()) {
            ToastCreator::error('Cannot delete category with associated products.');
            return redirect()->route('board.categories');
        }

        // Delete associated image
        if ($category->image) {
            Storage::delete('public/categories/' . $category->image);
        }

        $category->delete();

        ToastCreator::success('Category deleted successfully.');
        return redirect()->route('board.categories');
    }
}
