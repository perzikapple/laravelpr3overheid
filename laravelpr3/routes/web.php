<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use App\Models\Report;
use App\Models\User;

// -------------------------
// HOMEPAGE
// -------------------------
Route::get('/', function () {
    return view('home');
})->name('home');


// -------------------------
// RAPPORT INDIENEN
// -------------------------
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


// -------------------------
// LOGIN
// -------------------------
Route::get('/login', function () {
    if (Auth::check()) {
        return redirect()->route('home');
    }
    return view('login');
})->name('login');

Route::post('/login', function (Request $request) {
    $credentials = $request->only('email', 'password');

    if (Auth::attempt($credentials)) {
        $request->session()->regenerate();

        return redirect()->route('home');
    }

    return back()->withErrors([
        'email' => 'Ongeldige inloggegevens.',
    ]);
})->name('login');

// -------------------------
// REGISTRATIE
// -------------------------
Route::get('/register', function () {
    if (Auth::check()) {
        return redirect()->route('home');
    }
    return view('register');
})->name('register.form');

Route::post('/register', function (Request $request) {
    $request->validate([
        'name' => 'required|string|max:255',
        'email' => 'required|email|unique:users,email',
        'password' => 'required|string|min:8|confirmed',
    ]);

    $user = User::create([
        'name' => $request->name,
        'email' => $request->email,
        'password' => Hash::make($request->password),
    ]);

    // Automatisch inloggen na registratie
    Auth::login($user);

    return redirect()->route('home')->with('success', 'Account aangemaakt!');
})->name('register.post');


// -------------------------
// ADMIN
// -------------------------
Route::middleware(['auth'])->group(function () {

    Route::get('/admin', function (Request $request) {
        if (!Auth::check() || Auth::user()->admin !== 1) {
            abort(403, 'Toegang geweigerd');
        }

        $sort = $request->query('sort', 'desc');
        $search = $request->query('search');
        
        $query = Report::query();
        
        // Zoek op ID als search parameter is meegegeven
        if ($search) {
            $query->where('id', $search);
        }
        
        $reports = $query->orderBy('created_at', $sort)->get();
        
        return view('adminpage', compact('reports', 'sort'));
    })->name('admin');

    Route::post('/admin/update/{id}', function ($id, Request $request) {
        if (!Auth::check() || Auth::user()->admin !== 1) {
            abort(403);
        }

        $report = Report::findOrFail($id);
        $report->status = $request->status;
        $report->save();

        return redirect()->route('admin');
    })->name('admin.update');

    Route::delete('/admin/delete/{id}', function ($id) {
        if (!Auth::check() || Auth::user()->admin !== 1) {
            abort(403);
        }

        $report = Report::findOrFail($id);
        $report->delete();

        return redirect()->route('admin')->with('success', 'Melding verwijderd');
    })->name('admin.delete');

    Route::post('/logout', function (Request $request) {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/');
    })->name('logout');

});
