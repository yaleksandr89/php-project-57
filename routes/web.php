<?php

use App\Http\Controllers\LabelController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\TaskStatusController;
use App\Models\Label;
use App\Models\Task;
use App\Models\TaskStatus;
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

// Статусы задач
Route::resource('task_statuses', TaskStatusController::class)
    ->except(['show'])
    ->names('task_statuses');

// Задачи
Route::resource('tasks', TaskController::class)
    ->names('tasks');

// Метки
Route::resource('labels', LabelController::class)
    ->except(['show'])
    ->names('labels');

require __DIR__ . '/auth.php';
