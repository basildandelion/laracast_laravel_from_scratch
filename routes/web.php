<?php

use App\Http\Controllers\RegisteredUserController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use Laravel\Fortify\Features;

Route::inertia('/', 'public/Welcome', [
    'canRegister' => Features::enabled(Features::registration()),
])->name('home');

Route::middleware('guest')->group(function () {

    Route::get('register', [RegisteredUserController::class, 'create'])->name('register');
    Route::post('register', [RegisteredUserController::class, 'store'])->name('create.user');
    Route::get('login', [RegisteredUserController::class, 'login'])->name('authorization');
    Route::post('login', [RegisteredUserController::class, 'authorize'])->name('login.user');
});
Route::post('logout', [RegisteredUserController::class, 'logout'])->name('logout');
