<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;

Route::get('/home', function () {
    return view('home');
});

Route::post('/home', function(Request $request) {
    try {
        return redirect()->back()->with('success', 'Report submitted successfully!');
    } catch (\Exception $e) {
        return redirect()->back()->with('error', 'Error submitting report.');
    }
});

// Optioneel: redirect root naar /home
Route::get('/', function () {
    return redirect('/home.blade.php');
});

Route::get('/login', function () {
    return view('login');
})->name('login');
