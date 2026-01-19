<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BankController;
use App\Http\Controllers\GajianController;
use App\Http\Controllers\InvoiceController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\KaryawanController;
use App\Http\Controllers\DetailGajiController;
use App\Http\Controllers\WaybillController;
use App\Livewire\Pekerjaan\Index as PekerjaanIndex;
use App\Livewire\Pekerjaan\Detail as PekerjaanDetail;


Route::get('/', function () {
    return redirect('login');
});

Route::middleware(['auth:sanctum', config('jetstream.auth_session'), 'verified'])->group(function () {
    Route::get('/dashboard', function () { return view('dashboard'); })->name('dashboard');

    // ALL ROLES (Admin, Operasional, Karyawan)
    Route::middleware(['role:admin,operasional,karyawan'])->group(function () {
        Route::get('/setortarik', App\Livewire\Setortarik\Index::class)->name('setortarik.index');
        Route::get('/setortarik-detail/{mastersetortarik_id}', App\Livewire\Setortarik\Detail::class)->name('setortarik.detail');
        Route::get('/setortarik-ambil/{mastersetortarik_id}/{id?}', App\Livewire\Setortarik\Ambil::class)->name('setortarik.ambil');
        Route::get('/setortarik-setor/{mastersetortarik_id}/{id?}', App\Livewire\Setortarik\Setor::class)->name('setortarik.setor');
        Route::get('/gajian', App\Livewire\Gajian::class)->name('gajian.index');
        Route::get('gajian/{id}/print', [GajianController::class, 'print'])->name('gajian.print');
    });

    // ADMIN & OPERASIONAL Only
    Route::middleware(['role:admin,operasional'])->group(function () {
        Route::get('/pekerjaan', PekerjaanIndex::class)->name('pekerjaan.index');
        Route::get('/pekerjaan-detail/{id}', PekerjaanDetail::class)->name('pekerjaan.detail');
        Route::get('/surat-jalan', App\Livewire\SuratJalan\Index::class)->name('surat-jalan.index');
        Route::get('/surat-jalan-form/{id?}', App\Livewire\SuratJalan\Form::class)->name('surat-jalan.form');
        Route::get('/surat-jalan/{id}', App\Livewire\SuratJalan\Detail::class)->name('surat-jalan.detail');
        Route::get('/surat-jalan/{id}/preview', [App\Http\Controllers\SuratJalanController::class, 'preview'])->name('surat-jalan.preview');
        Route::get('/surat-jalan/{id}/print', [App\Http\Controllers\SuratJalanController::class, 'print'])->name('surat-jalan.print');
        
        Route::resource('karyawan', KaryawanController::class);
        
        // Override create route for Livewire
        Route::get('/invoice/create', App\Livewire\Invoice\Create::class)->name('invoice.create');
        Route::get('/invoices/create', App\Livewire\Invoice\Create::class)->name('invoices.create');
        
        Route::resource('invoice', InvoiceController::class);
        Route::resource('gajian', GajianController::class)->except(['index']);
        Route::resource('detail-gaji', DetailGajiController::class);
        Route::resource('banks', BankController::class);
        Route::resource('customers', CustomerController::class);
        Route::get('customers/{customer}/pekerjaan', [CustomerController::class, 'pekerjaan'])->name('customers.pekerjaan');
        Route::resource('invoices', InvoiceController::class);
        Route::resource('waybills', WaybillController::class);
    });

    // ADMIN Only
    Route::middleware(['role:admin'])->group(function () {
        Route::get('/user-management', App\Livewire\UserManagement::class)->name('user.index');
    });
});


