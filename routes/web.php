<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect('/login');
});

Route::get('/login', function () {
    return view('auth.login');
})->name('login');

Route::get('/provinces', function () {
    return view('provinces.index');
})->name('provinces');

Route::get('/wilayah', function () {
    return view('wilayah');
});
