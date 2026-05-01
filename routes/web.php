<?php

use App\Http\Controllers\IdeaController;
use App\Http\Controllers\RegisteredUserController;
use Illuminate\Support\Facades\Route;
use Laravel\Fortify\Features;


Route::redirect('/', '/ideas/')->name('home');
//Route::inertia('/', 'public/Welcome', [
//    'canRegister' => Features::enabled(Features::registration()),
//])->name('home');

Route::middleware('guest')->group(function () {
    Route::get('register', [RegisteredUserController::class, 'create'])->name('register');
    Route::post('register', [RegisteredUserController::class, 'store'])->name('create.user');
    Route::get('login', [RegisteredUserController::class, 'login'])->name('login');
    Route::post('login', [RegisteredUserController::class, 'authorize'])->name('login.user');
});

Route::middleware('auth')->group(function () {
    Route::post('logout', [RegisteredUserController::class, 'logout'])->name('logout');
    Route::resource('ideas', IdeaController::class);
});
