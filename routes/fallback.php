<?php

use App\Http\Controllers\HomeController;
use Illuminate\Support\Facades\Route;

// Catch-all Route
Route::get('{any}', [HomeController::class, 'root'])->where('any', '.*');
