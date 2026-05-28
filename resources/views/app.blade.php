<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laravel FrankenPHP</title>

    <!-- Google Font Outfit -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">

    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                fontFamily: {
                    'outfit': ['Outfit', 'sans-serif'],
                }
            }
        }
    </script>
</head>
<body class="font-outfit bg-gradient-to-br from-white to-slate-50 min-h-screen flex items-center justify-center p-4">
    <div class="max-w-2xl w-full">
        <!-- Main Card -->
        <div class="bg-white rounded-3xl shadow-2xl overflow-hidden backdrop-blur-xl">
            <!-- Header Gradient -->
            <div class="h-32 bg-gradient-to-r from-red-600 to-red-500 flex items-center justify-center">
                <div class="text-center">
                    <div class="text-6xl mb-4">👋</div>
                    <h1 class="text-4xl font-bold text-white">Hello World!</h1>
                </div>
            </div>

            <!-- Content -->
            <div class="p-8 md:p-12">
                <p class="text-center text-xl text-gray-600 mb-8 font-light">
                    Welcome to <span class="font-bold text-red-600">Laravel FrankenPHP</span>
                </p>

                <!-- Info Grid -->
                <div class="bg-gradient-to-br from-slate-50 to-slate-100 rounded-2xl p-8 mb-8 border border-slate-200">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="flex items-start gap-4">
                            <div class="flex-shrink-0 w-12 h-12 bg-gray-200 rounded-xl flex items-center justify-center">
                                <span class="text-xl">📦</span>
                            </div>
                            <div>
                                <p class="text-sm text-gray-600 font-medium">Application</p>
                                <p class="text-lg font-bold text-slate-900">{{ config('app.name') }}</p>
                            </div>
                        </div>

                        <div class="flex items-start gap-4">
                            <div class="flex-shrink-0 w-12 h-12 bg-red-100 rounded-xl flex items-center justify-center">
                                <span class="text-xl">⚙️</span>
                            </div>
                            <div>
                                <p class="text-sm text-gray-600 font-medium">Environment</p>
                                <p class="text-lg font-bold text-slate-900">
                                    <span class="inline-block px-3 py-1 bg-red-100 text-red-700 rounded-full text-sm font-bold">{{ config('app.env') }}</span>
                                </p>
                            </div>
                        </div>

                        <div class="flex items-start gap-4">
                            <div class="flex-shrink-0 w-12 h-12 bg-red-100 rounded-xl flex items-center justify-center">
                                <span class="text-xl">🐘</span>
                            </div>
                            <div>
                                <p class="text-sm text-gray-600 font-medium">PHP Version</p>
                                <p class="text-lg font-bold text-slate-900">{{ phpversion() }}</p>
                            </div>
                        </div>

                        <div class="flex items-start gap-4">
                            <div class="flex-shrink-0 w-12 h-12 bg-red-100 rounded-xl flex items-center justify-center">
                                <span class="text-xl">🚀</span>
                            </div>
                            <div>
                                <p class="text-sm text-gray-600 font-medium">Laravel Version</p>
                                <p class="text-lg font-bold text-slate-900">{{ app()->version() }}</p>
                            </div>
                        </div>
                    </div>

                    <!-- Timestamp -->
                    <div class="mt-6 pt-6 border-t border-slate-200">
                        <p class="text-sm text-gray-600 font-medium mb-2">Current Timestamp</p>
                        <p class="text-sm font-mono bg-white px-4 py-2 rounded-lg text-red-600 font-bold border border-slate-300">{{ now()->format('Y-m-d H:i:s') }}</p>
                    </div>
                </div>

                <!-- Service Status -->
                <div class="mb-8">
                    <h2 class="text-sm font-bold text-gray-500 uppercase tracking-widest mb-4">Docker Services</h2>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                        @foreach ($services as $service)
                        <div class="flex items-center justify-between px-4 py-3 rounded-xl border
                            {{ $service['status'] === 'running'
                                ? 'bg-green-50 border-green-200'
                                : 'bg-red-50 border-red-200' }}">
                            <div class="flex items-center gap-3">
                                <span class="text-xl">{{ $service['icon'] }}</span>
                                <div>
                                    <p class="text-sm font-bold text-slate-900">{{ $service['name'] }}</p>
                                    <p class="text-xs {{ $service['status'] === 'running' ? 'text-green-600' : 'text-red-500' }}">
                                        {{ $service['detail'] }}
                                    </p>
                                </div>
                            </div>
                            <span class="flex items-center gap-1.5 text-xs font-bold px-2.5 py-1 rounded-full
                                {{ $service['status'] === 'running'
                                    ? 'bg-green-100 text-green-700'
                                    : 'bg-red-100 text-red-700' }}">
                                <span class="w-1.5 h-1.5 rounded-full inline-block
                                    {{ $service['status'] === 'running' ? 'bg-green-500 animate-pulse' : 'bg-red-500' }}">
                                </span>
                                {{ $service['status'] === 'running' ? 'Running' : 'Down' }}
                            </span>
                        </div>
                        @endforeach
                    </div>
                </div>

                <!-- Buttons -->
                <div class="flex flex-col sm:flex-row gap-4 justify-center">
                    <a href="/api/demo" class="inline-flex items-center justify-center gap-2 px-6 py-3 bg-red-600 text-white font-bold rounded-xl hover:bg-red-700 hover:shadow-lg hover:shadow-red-600/30 hover:scale-105 transition-all duration-300">
                        <span>📊</span> API Demo
                    </a>
                    <a href="https://laravel.com" target="_blank" class="inline-flex items-center justify-center gap-2 px-6 py-3 bg-white text-red-600 font-bold rounded-xl border-2 border-red-600 hover:bg-red-50 hover:scale-105 transition-all duration-300">
                        <span>📚</span> Laravel Docs
                    </a>
                </div>
            </div>

            <!-- Footer -->
            <div class="bg-gradient-to-r from-slate-50 to-white px-8 py-6 border-t border-slate-200">
                <p class="text-center text-sm text-gray-600">
                    Powered by Laravel FrankenPHP · Running on Docker · <span class="font-bold">{{ now()->format('F j, Y') }}</span>
                </p>
            </div>
