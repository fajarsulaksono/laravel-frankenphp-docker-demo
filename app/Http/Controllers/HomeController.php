<?php

namespace App\Http\Controllers;

use Illuminate\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redis;

class HomeController extends Controller
{
    /**
     * Check status of all Docker services
     */
    private function checkServices(): array
    {
        $services = [];

        // FrankenPHP (always running since we're here)
        $services[] = [
            'name'   => 'FrankenPHP',
            'icon'   => '🐘',
            'status' => 'running',
            'detail' => 'PHP ' . phpversion(),
        ];

        // MariaDB
        try {
            DB::connection()->getPdo();
            $version = DB::connection()->getPdo()->getAttribute(\PDO::ATTR_SERVER_VERSION);
            $services[] = [
                'name'   => 'MariaDB',
                'icon'   => '🗄️',
                'status' => 'running',
                'detail' => $version,
            ];
        } catch (\Exception $e) {
            $services[] = [
                'name'   => 'MariaDB',
                'icon'   => '🗄️',
                'status' => 'down',
                'detail' => 'Connection failed',
            ];
        }

        // Redis
        try {
            Redis::ping();
            $services[] = [
                'name'   => 'Redis',
                'icon'   => '⚡',
                'status' => 'running',
                'detail' => 'Connected',
            ];
        } catch (\Exception $e) {
            $services[] = [
                'name'   => 'Redis',
                'icon'   => '⚡',
                'status' => 'down',
                'detail' => 'Connection failed',
            ];
        }

        // phpMyAdmin (internal port 80, service name: phpmyadmin)
        $conn = @fsockopen('phpmyadmin', 80, $errno, $errstr, 2);
        if ($conn) {
            fclose($conn);
            $services[] = [
                'name'   => 'phpMyAdmin',
                'icon'   => '🛢️',
                'status' => 'running',
                'detail' => 'port :9001',
            ];
        } else {
            $services[] = [
                'name'   => 'phpMyAdmin',
                'icon'   => '🛢️',
                'status' => 'down',
                'detail' => 'Not reachable',
            ];
        }

        // Jaeger (internal port 16686)
        $conn = @fsockopen('jaeger', 16686, $errno, $errstr, 2);
        if ($conn) {
            fclose($conn);
            $services[] = [
                'name'   => 'Jaeger (Tracing)',
                'icon'   => '🔭',
                'status' => 'running',
                'detail' => 'port :9016',
            ];
        } else {
            $services[] = [
                'name'   => 'Jaeger (Tracing)',
                'icon'   => '🔭',
                'status' => 'down',
                'detail' => 'Not reachable',
            ];
        }

        // OpenTelemetry Collector (Jaeger OTLP endpoint, internal port 4317)
        $conn = @fsockopen('jaeger', 4317, $errno, $errstr, 2);
        if ($conn) {
            fclose($conn);
            $services[] = [
                'name'   => 'OpenTelemetry (OTLP)',
                'icon'   => '📡',
                'status' => 'running',
                'detail' => 'gRPC :4317',
            ];
        } else {
            $services[] = [
                'name'   => 'OpenTelemetry (OTLP)',
                'icon'   => '📡',
                'status' => 'down',
                'detail' => 'Not reachable',
            ];
        }

        return $services;
    }

    /**
     * Show home page
     */
    public function index(): View
    {
        return view('app', [
            'services' => $this->checkServices(),
            'phpVersion' => phpversion(),
            'laravelVersion' => app()->version(),
        ]);
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
     * API: Get health check with service details
     */
    public function health(): JsonResponse
    {
        return response()->json([
            'status' => 'healthy',
            'database' => 'connected',
            'cache' => 'active',
            'queue' => 'running',
            'services' => $this->checkServices(),
            'php_version' => phpversion(),
            'laravel_version' => app()->version(),
        ]);
    }
}
