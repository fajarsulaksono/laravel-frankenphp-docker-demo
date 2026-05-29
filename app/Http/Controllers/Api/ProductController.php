<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $query = Product::with('category')->where('is_active', true);

        if ($request->filled('category')) {
            $query->where('category_id', $request->category);
        }

        if ($request->filled('search')) {
            $s = '%' . $request->search . '%';
            $query->where(function ($q) use ($s) {
                $q->where('name', 'like', $s)
                  ->orWhere('description', 'like', $s);
            });
        }

        $products = $query->orderBy('name')->get();

        return response()->json([
            'data' => $products->map(fn ($p) => [
                'id' => $p->id,
                'name' => $p->name,
                'slug' => $p->slug,
                'description' => $p->description,
                'price' => $p->price,
                'stock' => $p->stock,
                'image_url' => $p->image_url,
                'category' => $p->category->name,
                'category_id' => $p->category_id,
                'created_at' => $p->created_at,
            ]),
        ]);
    }

    public function show($id)
    {
        $product = Product::with('category')->findOrFail($id);

        return response()->json([
            'id' => $product->id,
            'name' => $product->name,
            'slug' => $product->slug,
            'description' => $product->description,
            'price' => $product->price,
            'stock' => $product->stock,
            'image_url' => $product->image_url,
            'category' => $product->category->name,
            'category_id' => $product->category_id,
            'created_at' => $product->created_at,
        ]);
    }
}
