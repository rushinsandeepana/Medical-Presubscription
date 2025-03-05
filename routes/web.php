<?php

use App\Http\Controllers\HomeController;
use Illuminate\Support\Facades\Route;
use App\Http\Middleware\AdminMiddleware;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\PrescriptionController;

Route::get('/', function () {
    return view('welcome');
});

// Route::get('/', function () {
//     return view('welcome');
// })->middleware("is_admin_user");

Route::get('/dashboard', function () {
    return view('Pharmacy.dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

route::get('user/dashboard', [HomeController::class, 'index'])->
   middleware(['auth', 'user']);

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::prefix('user')->group(function () {
    // Route::get('/upload-prescription', [PrescriptionController::class, 'uploadPrescription'])->name('User.upload_prescription');
    // Route::get('/upload-prescription', [PrescriptionController::class, 'uploadPrescription'])->name('User.upload_prescription');
    Route::get('/show-upload-page', [PrescriptionController::class, 'index'])->name('prescription.index');
    Route::post('/upload', [PrescriptionController::class, 'store'])->name('User.store');
    Route::get('/view-prescriptions', [PrescriptionController::class, 'view'])->name('prescription.view');
});


require __DIR__.'/auth.php';