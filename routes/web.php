<?php

use App\Http\Controllers\BoardController;
use App\Http\Controllers\LabelController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\TaskController;
use Illuminate\Support\Facades\Route;

Route::get('/', fn () => redirect()->route('login'));

Route::get('/dashboard', fn () => redirect()->route('projects.index'))
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::middleware(['auth', 'verified'])->group(function () {

    Route::get('/profile',    [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile',  [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Projects
    Route::resource('projects', ProjectController::class);

    // Kanban board
    Route::get('/projects/{project}/board',
        [BoardController::class, 'show'])->name('projects.board');
    Route::post('/projects/{project}/board/move',
        [BoardController::class, 'move'])->name('projects.board.move');

    // Tasks (nested under project)
    Route::get   ('/projects/{project}/tasks/create',
        [TaskController::class, 'create'])->name('projects.tasks.create');
    Route::post  ('/projects/{project}/tasks',
        [TaskController::class, 'store'])->name('projects.tasks.store');
    Route::get   ('/projects/{project}/tasks/{task}/edit',
        [TaskController::class, 'edit'])->name('projects.tasks.edit');
    Route::patch ('/projects/{project}/tasks/{task}',
        [TaskController::class, 'update'])->name('projects.tasks.update');
    Route::delete('/projects/{project}/tasks/{task}',
        [TaskController::class, 'destroy'])->name('projects.tasks.destroy');

    // Labels
    Route::resource('labels', LabelController::class)
        ->only(['index', 'store', 'update', 'destroy']);

});

require __DIR__.'/auth.php';
