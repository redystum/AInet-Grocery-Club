<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Utils\ToastCreator;
use Carbon\Carbon;
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

        $filename = Carbon::now()->format('dmYHis') . '_' . Str::random(10) . '.' .
            $request->file('image')->getClientOriginalExtension();
        $request->file('image')->storeAs('categories', $filename, 'public');
        $validated['image'] = $filename;

        Category::create($validated);

        ToastCreator::success('Category created successfully.');
        return redirect()->route('board.categories.index');
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
            'remove_image' => 'nullable|in:1,on',
        ]);

        if ($request->input('remove_image')) {
            // Delete old image
            if ($category->image && Storage::disk('public')->exists('categories/' . $category->image)) {
                Storage::disk('public')->delete('categories/' . $category->image);
            }
            $validated['image'] = null;
        }

        // Handle image upload if new image is provided
        if ($request->hasFile('image') && !$request->input('remove_image')) {

            // Delete old image
            if ($category->image && Storage::disk('public')->exists('categories/' . $category->image)) {
                Storage::disk('public')->delete('categories/' . $category->image);
            }

            $filename = Carbon::now()->format('dmYHis') . '_' . Str::random(10) . '.' .
                $request->file('image')->getClientOriginalExtension();
            $request->file('image')->storeAs('categories', $filename, 'public');
            $validated['image'] = $filename;
        }

        $category->update($validated);

        ToastCreator::success('Category updated successfully.');
        return redirect()->route('board.categories.index');
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
