<?php

use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use Laravel\Fortify\Features;

Route::inertia('/', 'public/Welcome', [
    'canRegister' => Features::enabled(Features::registration()),
])->name('home');

Route::get('register', [RegisteredUserController::class, 'create'])->name('register');
