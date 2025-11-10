<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use App\Models\Report;
use App\Models\User;

/*
|--------------------------------------------------------------------------
| Publieke pagina's
|--------------------------------------------------------------------------
*/

Route::get('/', function () {
    return view('home');
})->name('home');

/*
|--------------------------------------------------------------------------
| Melding versturen
|--------------------------------------------------------------------------
*/
Route::post('/home', function (Request $request) {
    // Valideer optioneel
    $request->validate([
        'title' => 'required|string|max:255',
        'description' => 'nullable|string|max:2000',
    ]);

    // Sla melding op in database
    Report::create([
        'title' => $request->input('title', 'Onbekend probleem'),
        'description' => $request->input('description', ''),
        'status' => 'open',
    ]);

    return redirect()->route('bedankt');
});

Route::get('/bedankt', fn() => view('bedankt'))->name('bedankt');

/*
|--------------------------------------------------------------------------
| Inlog & registratie
|--------------------------------------------------------------------------
*/

Route::get('/inlog', fn() => view('inlog'))->name('login');

Route::get('/register', fn() => view('register'))->name('register');

Route::post('/register', function (Request $request) {
    $validator = Validator::make($request->all(), [
        'name' => 'required|string|max:255',
        'email' => 'required|string|email|max:255|unique:users,email',
        'password' => 'required|string|min:8|confirmed',
    ]);

    if ($validator->fails()) {
        return redirect()->back()->withErrors($validator)->withInput();
    }

    $user = User::create([
        'name' => $request->input('name'),
        'email' => $request->input('email'),
        'password' => Hash::make($request->input('password')),
    ]);

    // Optioneel: log gebruiker meteen in
    // Auth::login($user);

    return redirect()->route('home')->with('success', 'Account aangemaakt!');
})->name('register.post');

/*
|--------------------------------------------------------------------------
| Admin routes
|--------------------------------------------------------------------------
*/

Route::get('/admin', function (Request $request) {
    $sort = $request->query('sort', 'desc');
    $reports = Report::orderBy('created_at', $sort)->get();
    return view('admin', compact('reports', 'sort'));
})->name('admin');

Route::post('/admin/update/{id}', function ($id, Request $request) {
    $report = Report::findOrFail($id);
    $report->status = $request->input('status');
    $report->save();
    return redirect()->route('admin');
})->name('admin.update');

