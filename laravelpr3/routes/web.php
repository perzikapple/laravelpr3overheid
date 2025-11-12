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
        'description' => $request->description,
        'email' => $request->email,
        'phone' => $request->phone,
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
        'description' => 'required|string|max:2000',
        'email' => 'nullable|email|max:255',
        'phone' => 'nullable|string|max:50',
        'photo' => 'nullable|image|mimes:jpg,jpeg,png|max:4096',
        'latitude' => 'nullable|numeric',
        'longitude' => 'nullable|numeric',
    ]);

    User::create([
        'name' => $request->name,
        'email' => $request->email,
        'password' => Hash::make($request->password),
    ]);

    return redirect()->route('home')->with('success', 'Account aangemaakt!');
})->name('register.post');


// ADMIN
Route::middleware(['auth', 'admin'])->group(function () {

    Route::get('/admin', function (Request $request) {

        if (!Auth::check() || Auth::user()->admin !== 1) {
            abort(403, 'Toegang geweigerd');
        }

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

    Route::post('/admin/update/{id}', function ($id, Request $request) {

        if (!Auth::check() || Auth::user()->admin !== 1) {
            abort(403);
        }

        $report = Report::findOrFail($id);
        $report->status = $request->status;
        $report->save();
        return redirect()->route('admin');

    })->name('admin.update');

    Route::get('/admin', function (Request $request) {

        if (!Auth::check() || Auth::user()->admin !== 1) {
            abort(403, 'Toegang geweigerd');
        }

        $sort = $request->query('sort', 'desc');
        $reports = Report::orderBy('created_at', $sort)->get();
        return view('admin', compact('reports', 'sort'));

    })->name('admin');
});

