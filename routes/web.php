<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\LocationController;
use App\Http\Controllers\ExUsedReportController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\KatalogBarangController;
use App\Http\Controllers\StockOpnameController;

/*
|--------------------------------------------------------------------------
| BAHASA
|--------------------------------------------------------------------------
*/

Route::get('/language/{locale}', function ($locale) {

    if (!in_array($locale, ['id', 'en'])) {
        abort(404);
    }

    session(['locale' => $locale]);

    return redirect()->back();

})->name('language.switch');


/*
|--------------------------------------------------------------------------
| AUTHENTICATION
|--------------------------------------------------------------------------
*/

// Menampilkan halaman login
Route::get('/', [AuthController::class, 'showLogin'])
    ->name('login');

// Memproses login
Route::post('/login', [AuthController::class, 'login'])
    ->name('login.process');

// Logout
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');


/*
|--------------------------------------------------------------------------
| REGISTER & FORGOT PASSWORD
|--------------------------------------------------------------------------
*/

Route::get('/registerr', function () {
    return view('auth.registerr');
})->name('registerr');

Route::get('/forgetPassword', function () {
    return view('auth.forget-password');
})->name('forgetPassword');

Route::get('/verifyOtp', function () {
    return view('auth.verify-otp');
})->name('verifyOtp');

Route::get('/resendOtp', function () {
    return view('auth.resend-otp');
})->name('resendOtp');


/*
|--------------------------------------------------------------------------
| VERIFIKASI LOKASI
|--------------------------------------------------------------------------
*/

Route::middleware('auth')->group(function () {
    Route::get('/verify-location', [LocationController::class, 'show'])
        ->name('verifyLocation');

    Route::post('/verify-location', [LocationController::class, 'verify'])
        ->name('verifyLocation.process');
});


/*
|--------------------------------------------------------------------------
| EMPLOYEE
|--------------------------------------------------------------------------
*/

Route::middleware(['auth', 'role:worker'])->group(function () {

    Route::get('/dashboardEmployee', function () {
        if (!session('location_verified')) {
            return redirect()->route('verifyLocation');
        }

        return view('employee.dashboard-employee');
    })->name('dashboardEmployee');

    Route::get('/dashboardEmployee', [
    ExUsedReportController::class,
    'dashboard'
])->name('dashboardEmployee');

Route::get('/riwayat-employee', [
    ExUsedReportController::class,
    'riwayat'
])->name('riwayatEmployee');

Route::get('/riwayatEmployee/{id}', [
    ExUsedReportController::class,
    'detail'
])->name('riwayatEmployee.detail');

   Route::get('/laporanEmployee', [
    ExUsedReportController::class,
    'laporan'
])->name('laporanEmployee');

    // Route::get('/notifikasiEmployee', function () {
    //     return view('employee.notifikasi-employee');
    // })->name('notifikasiEmployee');

    Route::get('/profileEmployee', function () {
        return view('employee.profile-employee');
    })->name('profileEmployee');

   Route::get('/inputExBarang', [
        ExUsedReportController::class, 'step1'
    ])->name('inputExBarang');

       Route::post('/inputExBarang/step1', [
        ExUsedReportController::class, 'saveStep1'
    ])->name('inputExBarang.step1.save');

    Route::get('/inputExBarang/step2', [
        ExUsedReportController::class, 'step2'
    ])->name('inputExBarang.step2');

    Route::post('/inputExBarang/step2', [
        ExUsedReportController::class, 'saveStep2'
    ])->name('inputExBarang.step2.save');

      Route::get('/inputExBarang/step3', [
        ExUsedReportController::class, 'step3'
    ])->name('inputExBarang.step3');

    Route::post('/inputExBarang/step3', [
        ExUsedReportController::class, 'saveStep3'
    ])->name('inputExBarang.step3.save');

    Route::get('/notifikasiEmployee', [
        NotificationController::class,
        'index'
    ])->name('notifikasiEmployee');

    Route::patch('/notifikasiEmployee/{id}/baca', [
        NotificationController::class,
        'tandaiDibaca'
    ])->name('notifikasiEmployee.baca');

    Route::patch('/notifikasiEmployee/baca-semua', [
        NotificationController::class,
        'tandaiSemuaDibaca'
    ])->name('notifikasiEmployee.bacaSemua');

// Membuka halaman edit laporan yang perlu revisi
Route::get('/riwayatEmployee/{id}/revisi', [
    ExUsedReportController::class,
    'editRevisi'
])->name('riwayatEmployee.revisi');

// Memproses pengiriman ulang laporan
Route::patch('/riwayatEmployee/{id}/revisi', [
    ExUsedReportController::class,
    'updateRevisi'
])->name('riwayatEmployee.revisi.update');
});



/*
|--------------------------------------------------------------------------
| ADMIN
|--------------------------------------------------------------------------
*/

Route::middleware(['auth', 'role:admin'])->group(function () {

    Route::get('/dashboardAdmin', function () {
        return view('admin.dashboard-admin');
    })->name('dashboardAdmin');

    Route::get('/profileAdmin', function () {
        return view('admin.profile-admin');
    })->name('profileAdmin');

// Menampilkan katalog barang
Route::get('/katalogAdmin', [
    KatalogBarangController::class,
    'index'
])->name('katalogAdmin');

// Menambahkan barang baru
Route::post('/katalogAdmin/store', [
    KatalogBarangController::class,
    'store'
])->name('katalogAdmin.store');

// Menghapus barang
Route::delete('/katalogAdmin/delete/{itemNumber}', [
    KatalogBarangController::class,
    'destroy'
])->name('katalogAdmin.destroy');

    Route::get('/StockAdmin', [
    StockOpnameController::class,
    'index'
])->name('StockAdmin');

// Menyimpan hasil opname fisik
Route::post('/StockAdmin/opname/{itemNumber}', [
    StockOpnameController::class,
    'saveOpname'
])->name('StockAdmin.opname');

// Halaman riwayat opname fisik
Route::get('/StockAdmin/riwayat', [
    StockOpnameController::class,
    'riwayat'
])->name('StockAdmin.riwayat');

// Menyimpan hasil rekonsiliasi stok
Route::post('/StockAdmin/rekonsiliasi/{itemNumber}', [
    StockOpnameController::class,
    'saveRekonsiliasi'
])->name('StockAdmin.rekonsiliasi');

Route::get('/StockAdmin/riwayat-rekonsiliasi', [
    StockOpnameController::class,
    'riwayatRekonsiliasi'
])->name('StockAdmin.riwayatRekonsiliasi');

    Route::get('/inputAdmin', function () {
        return view('admin.inputBarang-admin');
    })->name('inputAdmin');

    Route::get('/inputAdmin.Step2', function () {
        return view('admin.input2-admin');
    })->name('inputAdmin.Step2');

    Route::get('/inputAdminStep3', function () {
        return view('admin.input3-admin');
    })->name('inputAdmin.Step3');

    Route::get('/penerimaanBarang', function () {
        return view('admin.po-admin');
    })->name('penerimaanBarang');

// Menampilkan daftar laporan Ex Used
Route::get('/pengeluaranBarang', [
    ExUsedReportController::class,
    'adminIndex'
])->name('pengeluaranBarang');

// Menyimpan laporan Ex Used dari Admin
Route::post('/pengeluaranBarang', [
    ExUsedReportController::class,
    'storeAdmin'
])->name('pengeluaranBarang.store');

// Menampilkan detail laporan Ex Used
Route::get('/pengeluaranBarang/{id}', [
    ExUsedReportController::class,
    'detailAdmin'
])->name('pengeluaranBarang.detail');

// Menyetujui laporan Ex Barang
Route::patch('/pengeluaranBarang/{id}/setujui',
    [ExUsedReportController::class, 'setujuiLaporan']
)->name('pengeluaranBarang.setujui');

// Menolak laporan Ex Barang
Route::patch('/pengeluaranBarang/{id}/tolak', [
    ExUsedReportController::class,
    'tolakLaporan'
])->name('pengeluaranBarang.tolak');

// Mengembalikan laporan kepada Worker untuk direvisi
Route::patch('/pengeluaranBarang/{id}/revisi', [
    ExUsedReportController::class,
    'revisiLaporan'
])->name('pengeluaranBarang.revisi');

    Route::get('/mutasiGudang', function () {
        return view('admin.mutasi-admin');
    })->name('mutasiGudang');

    Route::get('/riwayatTransaksi', function () {
        return view('admin.riwayatTransaksi-admin');
    })->name('riwayatTransaksi');

    Route::get('/materialBekas', function () {
        return view('admin.materialBekas-admin');
    })->name('materialBekas');

// Menampilkan halaman pemindai barcode
Route::get('/scanBarcode', [
    KatalogBarangController::class,
    'pemindaiBarcode'
])->name('scanBarcode');

    Route::get('/pemindaiBarcode', [
    KatalogBarangController::class,
    'pemindaiBarcode'
])->name('pemindaiBarcode');

    // Pencarian barang dari hasil pemindaian barcode
Route::get('/katalogAdmin/pemindai/cari/{kodeBarang}', [
    KatalogBarangController::class,
    'cariBarcode'
])->name('katalogAdmin.pemindai.cari');

    Route::get('/laporanAdmin', function () {
        return view('admin.laporan-admin');
    })->name('laporanAdmin');

   
// Halaman edit katalog
Route::get('/katalogAdmin/edit/{itemNumber}', [
    KatalogBarangController::class,
    'edit'
])->name('katalogAdmin.edit');

// Proses simpan perubahan katalog
Route::put('/katalogAdmin/update/{itemNumber}', [
    KatalogBarangController::class,
    'update'
])->name('katalogAdmin.update');


});



