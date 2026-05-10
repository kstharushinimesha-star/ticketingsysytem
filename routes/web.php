<?php

use App\Http\Controllers\TicketController;
use App\Http\Controllers\CommentController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;


Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
});


Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {

   Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/dashboard', [TicketController::class, 'index'])->name('dashboard');
    
    Route::get('/admin/board', [TicketController::class, 'index'])->name('admin.board');;
    Route::resource('tickets', TicketController::class);
    Route::post('/tickets/{ticket}/comments', [CommentController::class, 'store'])->name('comments.store');

});

    Route::post('/tickets/{ticket}/comments', [CommentController::class, 'store'])->name('comments.store');
    Route::patch('/comments/{comment}', [CommentController::class, 'update'])->name('comments.update');
    Route::delete('/comments/{comment}', [CommentController::class, 'destroy'])->name('comments.destroy');

});