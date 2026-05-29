<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redis;
use Illuminate\Support\Facades\DB;

class CartController extends Controller
{
    public function index(Request $request)
    {
        $ids = $request->input('ids', []);
        if (empty($ids)) return response()->json([]);

        $products = Product::whereIn('id', $ids)->get()->keyBy('id');
        return response()->json($products);
    }

    public function add(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'integer|min:1',
        ]);

        return response()->json(['status' => 'added']);
    }

    public function remove($productId)
    {
        return response()->json(['status' => 'removed']);
    }

    public function checkout(Request $request)
    {
        $request->validate([
            'items' => 'required|array|min:1',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.quantity' => 'required|integer|min:1',
            'items.*.price' => 'required|numeric',
        ]);

        DB::beginTransaction();

        try {
            $total = 0;
            $orderItems = [];

            foreach ($request->items as $item) {
                $product = Product::findOrFail($item['product_id']);

                if ($product->stock < $item['quantity']) {
                    DB::rollBack();
                    return response()->json([
                        'message' => "Stok {$product->name} tidak mencukupi (tersedia: {$product->stock})",
                    ], 422);
                }

                $subtotal = $product->price * $item['quantity'];
                $total += $subtotal;

                $orderItems[] = [
                    'product_id' => $product->id,
                    'quantity' => $item['quantity'],
                    'price' => $product->price,
                ];

                $product->decrement('stock', $item['quantity']);
            }

            $order = Order::create([
                'user_id' => null,
                'status' => 'pending',
                'total_price' => $total,
                'notes' => 'Pesanan dari Vue SPA',
            ]);

            foreach ($orderItems as $oi) {
                $order->items()->create($oi);
            }

            DB::commit();

            return response()->json([
                'status' => 'success',
                'order_id' => $order->id,
                'total_price' => $total,
            ], 201);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }
}
