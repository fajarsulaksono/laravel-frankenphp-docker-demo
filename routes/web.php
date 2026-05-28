<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('app');
});

Route::get('/api/health', function () {
    return response()->json(['status' => 'ok', 'message' => 'Laravel FrankenPHP is running']);
});
