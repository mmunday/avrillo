<?php

use App\Http\Controllers\QuoteController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/quotes', [QuoteController::class, 'get']);

Route::post('/quotes/refresh', [QuoteController::class, 'refresh']);
