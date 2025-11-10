<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use App\Models\Report;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

Route::get('/', function () {
    return view('home');
})->name('home');

Route::post('/home', function (Request $request) {
    // Hier kun je eventueel $request->validate([...]) doen
    Report::create([
        'title' => $request->input('title', 'Onbekend probleem'),
        'description' => $request->input('description', ''),
    ]);

    return redirect()->route('bedankt');
});

Route::get('/bedankt', fn() => view('bedankt'))->name('bedankt');
Route::get('/inlog', fn() => view('inlog'))->name('inlog');

/*
|--------------------------------------------------------------------------
| Admin Routes
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

Route::delete('/admin/delete/{id}', function ($id) {
    Report::findOrFail($id)->delete();
    return redirect()->route('admin');
})->name('admin.delete');
