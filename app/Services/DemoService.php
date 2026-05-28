<?php

namespace App\Services;

class DemoService
{
    /**
     * Get demo statistics
     */
    public function getStats(): array
    {
        return [
            'framework' => 'Laravel 13',
            'runtime' => 'FrankenPHP (Alpine)',
            'frontend' => 'Vue 3 + Tailwind CSS v4 + Daisy UI',
            'database' => 'MariaDB 11.4',
            'cache' => 'Redis 7',
            'tracing' => 'Jaeger OpenTelemetry',
            'reverse_proxy' => 'Nginx Alpine',
        ];
    }

    /**
     * Generate welcome message
     */
    public function getWelcomeMessage(): string
    {
        return "🚀 Welcome to Laravel FrankenPHP Docker Demo!\n"
            . "A production-ready Laravel 13 environment with modern infrastructure.\n"
            . "Access: http://localhost:9000\n"
            . "PhpMyAdmin: http://localhost:9001\n"
            . "Jaeger: http://localhost:9016";
    }

    /**
     * Get feature list
     */
    public function getFeatures(): array
    {
        return [
            'Zero-downtime deployment with FrankenPHP',
            'Automatic HTTPS with Caddy',
            'Fiber support for async operations',
            'Vue 3 reactive UI with Tailwind CSS v4',
            'Daisy UI component library',
            'MariaDB database',
            'Redis caching and queues',
            'Jaeger distributed tracing',
            'Nginx reverse proxy with caching',
            'Docker Compose orchestration',
            'Alpine Linux for minimal image size',
        ];
    }
}
