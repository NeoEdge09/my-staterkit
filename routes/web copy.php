<?php

use Illuminate\Support\Facades\Route;

Route::redirect('/', '/login');

Route::view('index', 'pages/index')->name('index');
Route::view('dashboard/index', 'pages/dashboard/index')->name('dashboard');
