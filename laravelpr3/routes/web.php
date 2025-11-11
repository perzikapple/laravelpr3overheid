<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use App\Models\Report;
use App\Models\User;


Route::get('/', function () {
    return view('home');
})->name('home');

Route::post('/report', function (Request $request) {

    $request->validate([
        'description' => 'required|string|max:2000',
        'photo' => 'nullable|image|mimes:jpg,jpeg,png|max:4096',
        'latitude' => 'nullable|numeric',
        'longitude' => 'nullable|numeric',
    ]);

    $path = null;
    if ($request->hasFile('photo')) {
        $path = $request->file('photo')->store('photos', 'public');
    }

    Report::create([
        'title' => 'Melding',
        'description' => $request->description,
        'status' => 'open',
        'latitude' => $request->latitude,
        'longitude' => $request->longitude,
        'photo_path' => $path
    ]);

    return redirect()->route('bedankt');
})->name('report.store');

Route::get('/bedankt', fn() => view('bedankt'))->name('bedankt');


// LOGIN
Route::get('/inlog', fn() => view('inlog'))->name('login.form');
Route::post('/inlog', function (Request $request) {

    $credentials = $request->only('email', 'password');

    if (Auth::attempt($credentials)) {
        return redirect()->route('home');
    }

    return back()->withErrors(['email' => 'Login gegevens kloppen niet.']);
})->name('login');


// REGISTRATION
Route::get('/register', fn() => view('register'))->name('register.form');

Route::post('/register', function (Request $request) {
    $request->validate([
        'name' => 'required|string|max:255',
        'email' => 'required|email|unique:users,email',
        'password' => 'required|string|min:8|confirmed',
    ]);

    User::create([
        'name' => $request->name,
        'email' => $request->email,
        'password' => Hash::make($request->password),
    ]);

    return redirect()->route('home')->with('success', 'Account aangemaakt!');
})->name('register.post');


// ADMIN
Route::get('/admin', function (Request $request) {
    $sort = $request->query('sort', 'desc');
    $reports = Report::orderBy('created_at', $sort)->get();
    return view('admin', compact('reports', 'sort'));
})->name('admin');

Route::post('/admin/update/{id}', function ($id, Request $request) {
    $report = Report::findOrFail($id);
    $report->status = $request->status;
    $report->save();
    return redirect()->route('admin');
})->name('admin.update');

Route::delete('/admin/delete/{id}', function ($id) {
    Report::findOrFail($id)->delete();
    return redirect()->route('admin');
})->name('admin.delete');

