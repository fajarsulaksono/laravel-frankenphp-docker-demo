<?php

namespace App\Http\Controllers;

use Illuminate\View\View;
use Illuminate\Http\JsonResponse;

class HomeController extends Controller
{
    /**
     * Show home page
     */
    public function index(): View
    {
        return view('app');
    }

    /**
     * API: Get demo data
     */
    public function getDemoData(): JsonResponse
    {
        return response()->json([
            'status' => 'success',
            'message' => 'Welcome to Laravel FrankenPHP Demo',
            'data' => [
                'app_name' => config('app.name'),
                'environment' => config('app.env'),
                'debug' => config('app.debug'),
                'timestamp' => now()->toIso8601String(),
            ],
        ]);
    }

    /**
     * API: Get health check
     */
    public function health(): JsonResponse
    {
        return response()->json([
            'status' => 'healthy',
            'database' => 'connected',
            'cache' => 'active',
            'queue' => 'running',
        ]);
    }
}
