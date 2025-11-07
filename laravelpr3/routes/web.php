<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;

Route::get('/', function () {
    return view('home.blade.php');
})->name('home.blade.php');

Route::post('/home', function(Request $request) {
    try {
        return redirect('/bedankt');
    } catch (\Exception $e) {
        return redirect()->back()->with('error', 'Error submitting report.');
    }
});

Route::get('/bedankt', function () {
    return view('bedankt');
})->name('bedankt');

Route::get('/inlog', function () {
    return view('inlog');
})->name('login');
