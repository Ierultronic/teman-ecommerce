<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class StoreController extends Controller
{
    public function index()
    {
        $products = Product::with(['variants' => function($query) {
            $query->where('stock', '>', 0);
        }])->latest()->get();

        return view('store.index', compact('products'));
    }
}
