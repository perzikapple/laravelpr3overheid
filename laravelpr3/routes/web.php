<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;

// Homepagina
Route::get('/', function () {
    return view('home'); // <-- alleen de viewnaam, geen .blade.php
})->name('home');

// Formulier afhandeling
Route::post('/home', function (Request $request) {
    try {
        // hier kun je je melding verwerken
        return redirect()->route('bedankt');
    } catch (\Exception $e) {
        return redirect()->back()->with('error', 'Er is iets misgegaan bij het verzenden.');
    }
});

// Bedankt-pagina
Route::get('/bedankt', function () {
    return view('bedankt');
})->name('bedankt');

// Inlogpagina
Route::get('/inlog', function () {
    return view('inlog');
})->name('inlog');
