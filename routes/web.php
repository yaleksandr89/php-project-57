<?php

use App\Http\Controllers\LabelController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\TaskStatusController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
})->name('home');

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::resource('task_statuses', TaskStatusController::class)
    ->only(['index'])
    ->names('task_statuses');

Route::resource('tasks', TaskController::class)
    ->only(['index'])
    ->names('tasks');

Route::resource('labels', LabelController::class)
    ->only(['index'])
    ->names('labels');

Route::middleware('auth')->group(function (): void {
    Route::resource('task_statuses', TaskStatusController::class)
        ->except(['index', 'show'])
        ->names('task_statuses');

    Route::resource('tasks', TaskController::class)
        ->except(['index'])
        ->names('tasks');

    Route::resource('labels', LabelController::class)
        ->except(['index', 'show'])
        ->names('labels');
});

require __DIR__ . '/auth.php';
