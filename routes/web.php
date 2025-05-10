<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PasskeyController;
use App\Http\Controllers\ProfileController;

Route::get('/', fn() => view('welcome'));

Route::get('/dashboard', fn() => view('dashboard'))
  ->middleware(['auth', 'verified'])
  ->name('dashboard');

Route::middleware('auth')->group(function () {
  Route::get(
    '/profile',
    [ProfileController::class, 'edit']
  )->name('profile.edit');
  Route::patch(
    '/profile',
    [ProfileController::class, 'update']
  )->name('profile.update');
  Route::delete(
    '/profile',
    [ProfileController::class, 'destroy']
  )->name('profile.destroy');
  Route::delete(
    '/passkeys/{passkey}',
    [PasskeyController::class, 'destroy']
  )->name('passkeys.destroy');

  Route::post(
    '/passkeys',
    [PasskeyController::class, 'store']
  )->name('passkeys.store');
  Route::delete(
    '/passkeys/{passkey}',
    [PasskeyController::class, 'destroy']
  )->name('passkeys.destroy');
});

require __DIR__ . '/auth.php';
