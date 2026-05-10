<?php

use App\Http\Controllers\TicketController;
use App\Http\Controllers\CommentController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

// පළමු පිටුව (Welcome Page)
Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
});

// ලොගින් වූ පරිශීලකයින් සඳහා පමණක් (Auth Middleware)
Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {

   
    Route::get('/dashboard', [TicketController::class, 'index'])->name('dashboard');
    Route::resource('tickets', TicketController::class);
    Route::post('/tickets/{ticket}/comments', [CommentController::class, 'store'])->name('comments.store');

});


Route::middleware(['auth', 'admin'])->group(function () {
Route::get('/tickets/{ticket}/edit', [TicketController::class, 'edit'])->name('tickets.edit');
Route::put('/tickets/{ticket}', [TicketController::class, 'update'])->name('tickets.update');
});