<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\TaskController;
use Illuminate\Support\Facades\Route;

Route::get('/', [TaskController::class, 'welcome'])->name('welcome');
Route::get('/tasks', [TaskController::class, 'index'])->name('tasks');
Route::get('/profile', [ProfileController::class, 'show'])->name('profile');
Route::view('/about', 'about')->name('about');
