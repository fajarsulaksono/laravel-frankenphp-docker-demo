<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Order;

class OrderController extends Controller
{
    public function index()
    {
        return Order::with('items.product')
            ->orderByDesc('created_at')
            ->get();
    }

    public function show($id)
    {
        return Order::with('items.product')->findOrFail($id);
    }
}
