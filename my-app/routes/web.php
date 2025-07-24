<?php

use App\Http\Controllers\HelloController;
use App\Http\Controllers\ProfileController;
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

Route::get('/dashboard', function () {
  return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
  Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
  Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
  Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::group(['prefix' => 'hello'], function (): void {
  Route::get('/order', [HelloController::class, 'order'])->name('hello.order');
  Route::get('/', [HelloController::class, 'index'])->name('hello.index');
  Route::post('/', [HelloController::class, 'store'])->name('hello.store');
  Route::get('/{id}', [HelloController::class, 'show'])->name('hello.show');

});

require __DIR__ . '/auth.php';
