<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\ProductVariant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $query = Product::with(['creator', 'variants']);

        // Apply name filter
        if ($request->filled('name')) {
            $query->where('name', 'like', '%' . $request->name . '%');
        }

        // Apply status filter
        if ($request->filled('status')) {
            switch ($request->status) {
                case 'active':
                    $query->whereNull('deleted_at');
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
            // Default to showing all products (including deleted)
            $query->withTrashed();
        }

        $products = $query->latest()->paginate(15)->withQueryString();
            
        return view('admin.products.index', compact('products'));
    }

    public function create()
    {
        return view('admin.products.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'price' => 'required|numeric|min:0',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'variants' => 'required|array|min:1',
            'variants.*.variant_name' => 'required|string|max:255',
            'variants.*.stock' => 'required|integer|min:0',
            'variants.*.price' => 'nullable|numeric|min:0',
        ]);

        $data = $request->only(['name', 'description', 'price']);
        $data['created_by'] = auth()->id();

        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('products', 'public');
        }

        // Create the product
        $product = Product::create($data);

        // Create variants if they exist
        if ($request->has('variants') && is_array($request->variants)) {
            foreach ($request->variants as $variantData) {
                if (!empty($variantData['variant_name']) && isset($variantData['stock'])) {
                    $variantPrice = null;
                    if (!empty($variantData['price']) && $variantData['price'] > 0) {
                        $variantPrice = $variantData['price'];
                    }
                    
                    $product->variants()->create([
                        'variant_name' => $variantData['variant_name'],
                        'stock' => $variantData['stock'],
                        'price' => $variantPrice,
                    ]);
                }
            }
        }
        

        return redirect()->route('admin.products.index')->with('success', 'Product created successfully!');
    }

    public function edit(Product $product)
    {
        // Use withTrashed to allow editing soft-deleted products
        $product = Product::withTrashed()->findOrFail($product->id);
        $product->load('variants');
        return view('admin.products.edit', compact('product'));
    }

    public function update(Request $request, Product $product)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'price' => 'required|numeric|min:0',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'variants' => 'required|array|min:1',
            'variants.*.variant_name' => 'required|string|max:255',
            'variants.*.stock' => 'required|integer|min:0',
            'variants.*.price' => 'nullable|numeric|min:0',
        ]);

        $data = $request->only(['name', 'description', 'price']);

        if ($request->hasFile('image')) {
            // Delete old image
            if ($product->image) {
                Storage::disk('public')->delete($product->image);
            }
            $data['image'] = $request->file('image')->store('products', 'public');
        }

        $product->update($data);

        // Handle variants update
        if ($request->has('variants') && is_array($request->variants)) {
            // Delete existing variants
            $product->variants()->delete();
            
            // Create new variants
            foreach ($request->variants as $variantData) {
                if (!empty($variantData['variant_name']) && isset($variantData['stock'])) {
                    $variantPrice = null;
                    if (!empty($variantData['price']) && $variantData['price'] > 0) {
                        $variantPrice = $variantData['price'];
                    }
                    
                    $product->variants()->create([
                        'variant_name' => $variantData['variant_name'],
                        'stock' => $variantData['stock'],
                        'price' => $variantPrice,
                    ]);
                }
            }
        }

        return redirect()->route('admin.products.index')->with('success', 'Product updated successfully!');
    }

    public function destroy(Product $product)
    {
        $product->delete();
        return redirect()->route('admin.products.index')->with('success', 'Product deleted successfully!');
    }

    public function restore($id)
    {
        $product = Product::withTrashed()->findOrFail($id);
        $product->restore();
        return redirect()->route('admin.products.index')->with('success', 'Product restored successfully!');
    }

    public function forceDelete($id)
    {
        $product = Product::withTrashed()->findOrFail($id);
        $product->forceDelete();
        return redirect()->route('admin.products.index')->with('success', 'Product permanently deleted successfully!');
    }
}
