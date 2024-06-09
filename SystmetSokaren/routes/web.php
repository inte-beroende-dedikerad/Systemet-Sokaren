<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\apklisting;

Route::get('/', [apklisting::class, 'index'])->name('listing');