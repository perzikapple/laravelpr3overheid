<<<<<<< Updated upstream
=======
<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ReportController;

Route::get('/', fn() => redirect()->route('home'));

Route::get('reports/create', [ReportController::class, 'create'])->name('reports.create');
Route::post('reports', [ReportController::class, 'store'])->name('reports.store');
Route::get('reports/{report}', [ReportController::class, 'show'])->name('reports.show');

// Admin routes - protect with auth / admin middleware as needed
Route::middleware('auth')->group(function () {
    Route::get('admin/reports', [ReportController::class, 'index'])->name('admin.reports.index');
});
>>>>>>> Stashed changes
