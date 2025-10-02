<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class CategoryController extends Controller
{
    public function index(Request $request)
    {
        $query = Category::withCount('products');

        // Apply name filter
        if ($request->filled('name')) {
            $query->where('name', 'like', '%' . $request->name . '%');
        }

        // Apply status filter
        if ($request->filled('status')) {
            switch ($request->status) {
                case 'active':
                    $query->where('is_active', true)->whereNull('deleted_at');
                    break;
                case 'inactive':
                    $query->where('is_active', false)->whereNull('deleted_at');
                    break;
                case 'deleted':
                    $query->onlyTrashed();
                    break;
                case 'all':
                default:
                    $query->withTrashed();
                    break;
            }
        } else {
            // Default to showing all categories (including deleted)
            $query->withTrashed();
        }

        $categories = $query->ordered()->paginate(15)->withQueryString();
            
        return view('admin.categories.index', compact('categories'));
    }

    public function create()
    {
        return view('admin.categories.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255|unique:categories,slug',
            'description' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'is_active' => 'nullable|boolean',
            'sort_order' => 'nullable|integer|min:0',
        ]);

        $data = $request->only(['name', 'description', 'is_active', 'sort_order']);
        
        // Generate slug if not provided
        $data['slug'] = $request->slug ?? Str::slug($request->name);
        $data['is_active'] = $request->has('is_active') ? true : false;
        $data['sort_order'] = $request->sort_order ?? 0;

        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('categories', 'public');
        }

        Category::create($data);

        return redirect()->route('admin.categories.index')->with('success', 'Category created successfully!');
    }

    public function edit(Category $category)
    {
        // Use withTrashed to allow editing soft-deleted categories
        $category = Category::withTrashed()->where('slug', $category->slug)->firstOrFail();
        return view('admin.categories.edit', compact('category'));
    }

    public function update(Request $request, Category $category)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255|unique:categories,slug,' . $category->id,
            'description' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'is_active' => 'nullable|boolean',
            'sort_order' => 'nullable|integer|min:0',
        ]);

        $data = $request->only(['name', 'description', 'sort_order']);
        
        // Update slug if provided
        if ($request->filled('slug')) {
            $data['slug'] = Str::slug($request->slug);
        }
        
        $data['is_active'] = $request->has('is_active') ? true : false;
        $data['sort_order'] = $request->sort_order ?? 0;

        if ($request->hasFile('image')) {
            // Delete old image
            if ($category->image) {
                Storage::disk('public')->delete($category->image);
            }
            $data['image'] = $request->file('image')->store('categories', 'public');
        }

        $category->update($data);

        return redirect()->route('admin.categories.index')->with('success', 'Category updated successfully!');
    }

    public function destroy(Category $category)
    {
        $category->delete();
        return redirect()->route('admin.categories.index')->with('success', 'Category deleted successfully!');
    }

    public function restore($slug)
    {
        $category = Category::withTrashed()->where('slug', $slug)->firstOrFail();
        $category->restore();
        return redirect()->route('admin.categories.index')->with('success', 'Category restored successfully!');
    }

    public function forceDelete($slug)
    {
        $category = Category::withTrashed()->where('slug', $slug)->firstOrFail();
        
        // Delete image if exists
        if ($category->image) {
            Storage::disk('public')->delete($category->image);
        }
        
        $category->forceDelete();
        return redirect()->route('admin.categories.index')->with('success', 'Category permanently deleted successfully!');
    }
}
