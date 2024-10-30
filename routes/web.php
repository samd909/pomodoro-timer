<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TodoController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/todos', [TodoController::class, 'index'])->name('todos.index');
Route::post('/todos', [TodoController::class, 'store'])->name('todos.store');

// Update route (for updating the title and description of a todo)
Route::put('/todos/{todo}', [TodoController::class, 'update'])->name('todos.update');

// Status route (for marking a todo as done)
Route::put('/todos/{todo}/status', [TodoController::class, 'status'])->name('todos.status');


// Delete route
Route::delete('/todos/{todo}', [TodoController::class, 'destroy'])->name('todos.destroy');
