<?php

use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use Laravel\Fortify\Features;

Route::inertia('/', 'public/Welcome', [
    'canRegister' => Features::enabled(Features::registration()),
])->name('home');

Route::inertia('/about', 'public/About', [
    'canRegister' => Features::enabled(Features::registration()),
])->name('about');


Route::inertia('/contacts', 'public/Contacts', [
    'canRegister' => Features::enabled(Features::registration()),
])->name('contacts');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::inertia('dashboard', 'Dashboard')->name('dashboard');
});

require __DIR__.'/settings.php';
