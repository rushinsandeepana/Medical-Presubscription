<?php

use App\Http\Controllers\DrugController;
use App\Http\Controllers\HomeController;
use Illuminate\Support\Facades\Route;
use App\Http\Middleware\AdminMiddleware;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\PrescriptionController;
use App\Http\Controllers\QuotationController;

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
    Route::post('/upload', [PrescriptionController::class, 'store'])->middleware('auth')->name('User.store');
    
});

Route::prefix('pharmacy')->group(function () {
    Route::get('/view-prescriptions', [PrescriptionController::class, 'view'])->name('prescription.view');

    // Drugs
    Route::get('prepare_quotations/{prescription_id}', [DrugController::class, 'index'])->name('prepare_quotations.view');
    Route::get('/subscription/view-images/{id}', [DrugController::class, 'viewImages'])->name('subscription.viewImages');
    Route::post('/quotation/addDrugs/{prescription_id}', [DrugController::class, 'addDrugs'])->name('quotation.addDrugs');
    // Route::get('/view-drugs/{id}', [DrugController::class, 'viewDrugs'])->name('quotation.viewDrugs');

    Route::post('/send_quotation/{subscriptionId}', [QuotationController::class, 'sendQuotation'])->name('send.quotation');
    Route::get('/view_quotations', [QuotationController::class, 'viewQuotation'])->name('view.quotation');
    Route::get('/send/{subscriptionId}', [QuotationController::class, 'sendNotification']);
    Route::get('/quotations/confirm/{id}', [QuotationController::class, 'ConfirmStatus'])->name('quotation.confirm');
    Route::get('/quotations/reject/{id}', [QuotationController::class, 'RejectStatus'])->name('quotation.reject');
    Route::get('/quotations/status-details', [QuotationController::class, 'QuotationStatus'])->name('quotation.status');
});


require __DIR__.'/auth.php';