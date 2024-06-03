<?php


use App\Http\Controllers\BahanBakuController;
use App\Http\Controllers\admin\SaldoController as AdminSaldoController;
use App\Http\Controllers\customer\SaldoController as CustomerSaldoController;
use App\Http\Controllers\BahanBakuControllers;
use App\Http\Controllers\admin\PresensiController;
use App\Http\Controllers\mo\PenitipController;
use App\Http\Controllers\mo\PresensiController as MoPresensiController;
use App\Http\Controllers\mo\SaldoController;
use App\Http\Controllers\owner\LaporanController as OwnerLaporanController;
use App\Http\Controllers\owner\PenitipController as OwnerPenitipController;
use App\Http\Controllers\owner\PresensiController as OwnerPresensiController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\customer\CartController as CustomerCartController;
use App\Http\Controllers\customer\DashboardCustomerController;
use App\Http\Controllers\customer\TransaksiController as CustomerTransaksiController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\ResepController;
use App\Http\Controllers\dukproController;
use App\Http\Controllers\ProdukController;
use App\Http\Controllers\hampersController;
use App\Http\Controllers\PegawaiController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\mo\LaporanController;
use App\Http\Controllers\mo\TransaksiController as MoTransaksiController;
use App\Http\Controllers\mo\TransaksiPOController as MoTransaksiPOController;
use App\Http\Controllers\registerController;
use App\Http\Controllers\PromoPointController;
use App\Http\Controllers\PemasukanPerusahaanController;
use App\Http\Controllers\pencatatanBBController;
use App\Http\Controllers\pencatatanPengeluaranLainController;
use App\Http\Controllers\TransaksiController;
use App\Http\Controllers\LaporanController as OwnerMoLaporanController;

use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Route;

use App\Http\Middleware\CheckRole;

use App\Models\Customer;
use App\Models\BahanBaku;
use App\Models\Penitip;



//untuk verifikasi email -------------------------------------------------------------------------

Route::get('/email/verify', function () {
    return view('auth.verify-email');
})->middleware('auth')->name('verification.notice');

Route::get('/email/verify/{id}/{hash}', function (EmailVerificationRequest $request) {
    $request->fulfill();

    // Perbarui email_verified_at pengguna saat email diverifikasi
    $request->user()->forceFill([
        'email_verified_at' => now(),
    ])->save();

    return redirect('/login');
})->middleware(['auth', 'signed'])->name('verification.verify');

// Route::get('/', function () {
//     return 'ini adalahprofile. penanda bahwa user sudah login dan terverifikasi';
// })->middleware(['auth', 'verified']);
// ;

//------------------------------------------------------------------------------------------------

Route::get('/pengeluaranLain/create', [pencatatanPengeluaranLainController::class, 'create_PencatatanPengeluaranLain'])->name('create_PencatatanPengeluaranLain');
Route::get('/pengeluaranLain/tampilanPengeluaranLain', [pencatatanPengeluaranLainController::class, 'index'])->name('index_PencatatanPengeluaranLain');
Route::post('/pengeluaranLain/store_PencatatanPengeluaranLain', [pencatatanPengeluaranLainController::class, 'store_PencatatanPengeluaranLain'])->name('store_PencatatanPengeluaranLain');
Route::get('/pengeluaranLain/edit_PencatatanPengeluaranLain/{PencatatanPengeluaranLain}', [pencatatanPengeluaranLainController::class, 'edit_PencatatanPengeluaranLain'])->name('edit_PencatatanPengeluaranLain');
Route::patch('/pengeluaranLain/update_PencatatanPengeluaranLain/{PencatatanPengeluaranLain}', [pencatatanPengeluaranLainController::class, 'update_PencatatanPengeluaranLain'])->name('update_PencatatanPengeluaranLain');
Route::delete('/pengeluaranLain/delete_PencatatanPengeluaranLain/{PencatatanPengeluaranLain}', [pencatatanPengeluaranLainController::class, 'destroy_PencatatanPengeluaranLain'])->name('destroy_PencatatanPengeluaranLain');

Route::get('/', [DashboardController::class, 'index'])->name('dashboard');


Route::get('/dashboardCustomer', [DashboardCustomerController::class, 'index'])->name('dashboardCustomer.index');


Route::get('/register/view', function () {
    return view('customer.register');
})->name('register_view');

// ini route yang dipake untuk register ASLINYA
Route::post('/register', [CustomerController::class, 'register'])->name('register');


Route::get('/login', function () {
    return view('customer.login');
})->name('login');

Route::get('/logout', function () {
    Auth::logout();
    return redirect()->route('login');
})->name('logout');

Route::post('/check', [LoginController::class, 'check'])->name('check');

Route::get('/tambahPenitip', function () {
    return view('MO.tambahPenitip');
});

Route::get('/editPenitip', function () {
    return view('MO.editPenitip');
});

// Route::get('/BahanBaku/edit_bahanBaku', 'BahanBakuController@edit_BahanBaku')->name('edit_bahanBaku');

Route::get('/BahanBaku/tampilanDataBahanBaku', [BahanBakuController::class, 'index'])->name('index_bahanBaku');

Route::get('/addBahanBaku', function () {
    return view('admin.addBahanBaku');
});


//UNTUK CUSTUMOR
Route::get('/customer/profile', [CustomerController::class, 'profil_customer'])->name('profil_customer')->middleware('auth', CheckRole::class . ':customer');
Route::patch('/customer/update_profile', [CustomerController::class, 'update_profil'])->name('update_profil')->middleware('auth', CheckRole::class . ':customer');
Route::get('customer/history_pesanan', [CustomerController::class, 'history_pesanan'])->name('history_pesanan')->middleware('auth', CheckRole::class . ':customer');
Route::get('customer/history_pesanan/detail', [CustomerController::class, 'searchHistory'])->name('searchHistory')->middleware('auth', CheckRole::class . ':customer');

// Auth::routes(['verify' => true]);

//Route UNTUK CHANGE PASSWORD UNTUK ADMIN, MO, OWNER
Route::get('/changePassword', [UserController::class, 'change_password'])->name('change_password');
Route::patch('/changePassword', [UserController::class, 'update_password'])->name('update_password');


//INI NANTINYA UNTUK ADMIN
Route::get('/pengiriman', [TransaksiController::class, 'show_pengiriman'])->name('show_pengiriman');
Route::get('/updatePengiriman/{id}', [TransaksiController::class, 'update_pengiriman'])->name('update_pengiriman');
Route::patch('/input_pengiriman/{id}/{total_harga}', [TransaksiController::class, 'input_pengiriman'])->name('input_pengiriman');

//INI UNTUK ADMIN(KONFIRMASI PESANAN)
Route::get('/show_konfirmasi_pesanan', [TransaksiController::class, 'show_konfirmasi_pesanan'])->name('show_konfirmasi_pesanan');
Route::get('/detailKonfirmasiPesanan/{id}', [TransaksiController::class, 'detail_konfirmasi_pesanan'])->name('detail_konfirmasi_pesanan');
Route::get('/show_pesanan_diproses', [TransaksiController::class, 'show_pesanan_diproses'])->name('show_pesanan_diproses');
Route::get('/show_pesanan_telat_bayar', [TransaksiController::class, 'show_pesanan_telat_bayar'])->name('show_pesanan_telat_bayar');
Route::patch('/pesanan_siap_dikirim_dipickup/{id}', [TransaksiController::class, 'pesanan_siap_dikirim_dipickup'])->name('pesanan_siap_dikirim_dipickup');
Route::patch('batalkan_pesanan_telat_bayar/{id}', [TransaksiController::class, 'batalkan_pesanan_telat_bayar'])->name('batalkan_pesanan_telat_bayar');
//INI UNTUK ADMIN UNTUK KONFIRMASI PESANAN DAN MENGINPUTKAN JUMLAH INCOME PERUSAHAAN
Route::post('/store_pemasukan_perusahaan', [PemasukanPerusahaanController::class, 'store_pemasukan_perusahaan'])->name('store_pemasukan_perusahaan');

//INI UNTUK CUSTOMER PAYMENT
Route::get('/show_payment_pesanan_list', [CustomerController::class, 'show_payment_pesanan_list'])->name('show_payment_pesanan_list');
Route::get('/payment_pesanan/{id}', [CustomerController::class, 'payment_pesanan'])->name('payment_pesanan');
Route::patch('/store/bukti_pembayaran/{id}', [CustomerController::class, 'store_bukti_pembayaran'])->name('store_bukti_pembayaran');
Route::patch('/pesanan_selesai/{id}', [CustomerController::class, 'pesanan_selesai'])->name('pesanan_selesai');
Route::patch('/pesanan_dibatalkan/{id}', [CustomerController::class, 'pesanan_dibatalkan'])->name('pesanan_dibatalkan');


//INI UNTUK LAPORAN OWNER DAN MO (MACE)
Route::get('/show_laporan_penjualan_keseluruhan', [OwnerMoLaporanController::class, 'show_laporan_penjualan_keseluruhan'])->name('show_laporan_penjualan_keseluruhan');
Route::get('/chart-penjualan-bulanan', [OwnerMoLaporanController::class, 'show_chart_penjualan_bulanan'])->name('chart_penjualan_bulanan');
Route::get('/show_laporan_penggunaan_bahanbaku', [OwnerMoLaporanController::class, 'show_laporan_penggunaan_bahanbaku'])->name('show_laporan_penggunaan_bahanbaku');


//================================= CUSTOMER =================================
Route::middleware(['auth', CheckRole::class . ':customer'])->group(function () {
    //group route untuk customer
    Route::group(['prefix' => 'customer'], function () {
        //store
        Route::get('/tarik_saldo', [CustomerSaldoController::class, 'tarik_saldo'])->name('customer.tarik_saldo');
        Route::get('/riwayat_saldo', [CustomerSaldoController::class, 'riwayat_saldo'])->name('customer.riwayat_saldo');
        Route::post('/tarik_saldo_store', [CustomerSaldoController::class, 'tarik_saldo_store'])->name('customer.tarik_saldo_store');
    });
});

// INI UNTUK ADMIN SAJA
Route::middleware(['auth', CheckRole::class . ':admin'])->group(function () {

    //UNTUK PROFIL ADMIN
    Route::get('/admin/profile', [UserController::class, 'profil_admin'])->name('profil_admin');
    //BAHAN BAKU
    Route::get('/bahanBaku', function () {
        return view('admin.bahanBaku');
    });
    Route::get('/tambahBahanBaku', function () {
        return view('admin.tambahBahanBaku');
    });
    // Route::get('/BahanBaku/create', [BahanBakuController::class, 'create_BahanBaku'])->name('create_BahanBaku');
    // Route::post('/BahanBaku/store_bahanBaku', [BahanBakuController::class, 'store_BahanBaku'])->name('store_BahanBaku');
    // Route::get('/BahanBaku/edit_bahanBaku/{BahanBaku}', [BahanBakuController::class, 'edit_BahanBaku'])->name('edit_BahanBaku');
    // Route::patch('/BahanBaku/update_BahanBaku/{BahanBaku}', [BahanBakuController::class, 'update_BahanBaku'])->name('update_BahanBaku');

    Route::get('/BahanBaku/edit_bahanBaku', 'BahanBakuController@edit_BahanBaku')->name('edit_bahanBaku');
    Route::get('/BahanBaku/tampilanDataBahanBaku', [BahanBakuController::class, 'index'])->name('index_bahanBaku');
    Route::get('/BahanBaku/create', [BahanBakuController::class, 'create_BahanBaku'])->name('create_BahanBaku');
    Route::post('/BahanBaku/store_bahanBaku', [BahanBakuController::class, 'store_BahanBaku'])->name('store_BahanBaku');
    // Route::get('/BahanBaku/tampilanDataBahanBaku', function () {
    //     return view('admin.bahanBaku');
    // });
    // Route::get('/BahanBaku/store_bahanBaku', [BahanBakuController::class, 'store_BahanBaku'])->name('store_BahanBaku');
    Route::get('/BahanBaku/edit_bahanBaku/{BahanBaku}', [BahanBakuController::class, 'edit_BahanBaku'])->name('edit_BahanBaku');
    Route::patch('/BahanBaku/update_BahanBaku/{BahanBaku}', [BahanBakuController::class, 'update_BahanBaku'])->name('update_BahanBaku');
    Route::delete('/BahanBaku/delete_bahanBaku/{BahanBaku}', [BahanBakuController::class, 'destroy_BahanBaku'])->name('destroy_BahanBaku');
    // Route::get('/BahanBaku/tampilanDataBahanBaku', [BahanBakuController::class, 'store_BahanBaku'])->name('tampilanDataBahanBaku');


    //hampers controller
    Route::controller(hampersController::class)->group(function () {
        Route::get('/hampers', 'index')->name('hampers.index');
        Route::get('/hampers/create', 'create')->name('hampers.create');
        Route::post('/hampers', 'store')->name('hampers.store');

        Route::get('/hampers/{hampers}/edit', 'edit')->name('hampers.edit');
        Route::get('/hampers/{hampers}/show', 'show')->name('hampers.show');
        Route::put('/hampers/{hampers}/', 'update')->name('hampers.update');

        Route::delete('/hampers/{hampers}/', 'destroy')->name('hampers.destroy');

        Route::get('/hampers/search', 'search')->name('hampers.search');
    });

    Route::controller(dukproController::class)->group(function () {
        Route::get('/dukpro', 'index')->name('dukpro.index');
        Route::get('/dukpro/create', 'create')->name('dukpro.create');
        Route::post('/dukpro', 'store')->name('dukpro.store');

        Route::get('/dukpro/{dukpro}/edit', 'edit')->name('dukpro.edit');
        Route::get('/dukpro/{dukpro}/show', 'show')->name('dukpro.show');
        Route::put('/dukpro/{dukpro}/', 'update')->name('dukpro.update');

        Route::delete('/dukpro/{dukpro}/', 'destroy')->name('dukpro.destroy');
        Route::get('/dukpro/search', 'search')->name('dukpro.search');
    });

    Route::controller(PromoPointController::class)->group(function () {
        Route::get('/promopoint', 'index')->name('promopoint.index');
        Route::get('/promopoint/create', 'create')->name('promopoint.create');
        Route::post('/promopoint', 'store')->name('promopoint.store');

        Route::get('/promopoint/{promopoint}/edit', 'edit')->name('promopoint.edit');
        Route::get('/promopoint/{promopoint}/show', 'show')->name('promopoint.show');
        Route::put('/promopoint/{promopoint}/', 'update')->name('promopoint.update');

        Route::delete('/promopoint/{promopoint}/', 'destroy')->name('promopoint.destroy');
        Route::get('/promopoint/search', 'search')->name('promopoint.search');
    });


    //RESEP
    Route::get('/resep/create', [ResepController::class, 'create_resep'])->name('create_resep');
    Route::post('/resep/create', [ResepController::class, 'tambahResep'])->name('tambahResep');
    Route::get('/resep/update/{produk}', [ResepController::class, 'updateResep'])->name('updateResep');
    Route::get('/resep', [ResepController::class, 'index_resep'])->name('index_resep');
    Route::get('/detailResep/{produk}', [ResepController::class, 'index_detailResep'])->name('detailResep');
    Route::get('/resep/search', [ResepController::class, 'search_resep'])->name('search_resep');
    Route::post('/resep/update/{produk}', [ResepController::class, 'update_resep'])->name('update_resep');
    Route::post('/resep/delete/{produk}', [ResepController::class, 'delete_resep'])->name('delete_resep');
});




//PEMROSESAN PESANAN DAN LAPORAN

Route::middleware(['auth', CheckRole::class . ':mo'])->group(function () {
    Route::group(['prefix' => 'mo', 'as' => 'mo.'], function () {
        // show_konfirmasi
        Route::get('pemrosesan/pesanan', [MoTransaksiPOController::class, 'pesanan'])->name('pemrosesanpesanan');
        Route::put('pemrosesan/pesanan/accept', [MoTransaksiPOController::class, 'pesanan_accept'])->name('pemrosesanpesanan.accept');
        Route::get('laporan/penjualan', [LaporanController::class, 'penjualan'])->name('laporan.penjualan');
        Route::get('laporan/stok_bb', [LaporanController::class, 'stok_bb'])->name('laporan.stok_bb');
    });
});



//INI UNTUK MO
Route::middleware(['auth', CheckRole::class . ':mo'])->group(function () {

    Route::group(['prefix' => 'mo'], function () {
        Route::resource('penitip', PenitipController::class)->except(['show']);
        Route::get('/penitip/rekap', [PenitipController::class, 'rekap'])->name('penitip.rekap');
        Route::get('/presensi', [MoPresensiController::class, 'index'])->name('mo.presensi');
        Route::get('/presensi/rekap', [MoPresensiController::class, 'rekap'])->name('mo.presensi.rekap');
        Route::get('/presensi/detail/{id}', [MoPresensiController::class, 'detail'])->name('mo.presensi.detail');
        Route::get('/laporan/pemasukan', [LaporanController::class, 'pemasukan'])->name('mo.laporan.pemasukan');
        Route::get('/laporan/pengeluaran', [LaporanController::class, 'pengeluaran'])->name('mo.laporan.pengeluaran');
    });

    //UNTUK PROFIL MO
    Route::get('/mo/profile', [UserController::class, 'profil_mo'])->name('profil_mo');

    Route::controller(pencatatanBBController::class)->group(function () {
        Route::get('/bahanbaku', 'index')->name('bahanbaku.index');
        Route::get('/bahanbaku/create', 'create')->name('bahanbaku.create');
        Route::post('/bahanbaku', 'store')->name('bahanbaku.store');
        Route::get('/bahanbaku/{bahanbaku}/edit', 'edit')->name('bahanbaku.edit');
        Route::get('/bahanbaku/{bahanbaku}/show', 'show')->name('bahanbaku.show');
        Route::put('/bahanbaku/{bahanbaku}/', 'update')->name('bahanbaku.update');
        Route::delete('/bahanbaku/{bahanbaku}/', 'destroy')->name('bahanbaku.destroy');
        Route::get('/bahanbaku/search', 'search')->name('bahanbaku.search');

        Route::get('/mo/show_konfirmasi_pesanan', [MoTransaksiController::class, 'show_konfirmasi_pesanan'])->name('mo.show_konfirmasi_pesanan');;
        Route::get('/mo/konfirmasi_pesanan/accept/{id}', [MoTransaksiController::class, 'konfirmasi_pesanan_accept'])->name('mo.konfirmasi_pesanan.accept');;
        Route::get('/mo/konfirmasi_pesanan/reject/{id}', [MoTransaksiController::class, 'konfirmasi_pesanan_reject'])->name('mo.konfirmasi_pesanan.reject');;
    });


    //MO SEMUA
    Route::get('/tambahKaryawan', function () {
        return view('MO.tambahKaryawan');
    })->name('tambahKaryawan');
    Route::post('/tambah-pegawai', [PegawaiController::class, 'tambahPegawai'])->name('pegawai.tambah');
    Route::get('/dataKaryawan', [PegawaiController::class, 'showPegawai'])->name('dataKaryawan');
    Route::get('/pegawai/{karyawan}/edit', [PegawaiController::class, 'edit_pegawai'])->name('edit_pegawai');
    Route::patch('/pegawai/{pegawai}/update', [PegawaiController::class, 'update_pegawai'])->name('update_pegawai');
    Route::delete('/pegawai/{pegawai}', [PegawaiController::class, 'delete_pegawai'])->name('delete_pegawai');

    //PENITIP BELUM ADA YANTOK

    //PENCATATAN PENGELUARAN BAHAN BAKU BELUM YANTOK
});

Route::middleware(['auth', CheckRole::class . ':owner'])->group(function () {

    //UNTUK PROFIL OWNER
    Route::get('/owner/profile', [UserController::class, 'profil_owner'])->name('profil_owner');

    // INI ADALAH UNTUK OWNER
    Route::get('/owner/bonus', [PegawaiController::class, 'index_gaji_bonus_karyawan'])->name('index_gaji_bonus_karyawan')->middleware('auth', CheckRole::class . ':owner');
    Route::get('/showGajiPegawai', [PegawaiController::class, 'showGajiPegawai'])
        ->name('showGajiPegawai')
        ->middleware('auth', CheckRole::class . ':owner');
    Route::get('/pegawai/{pegawai}/edit_gajiPegawai', [PegawaiController::class, 'edit_gajiPegawai'])->name('edit_gajiPegawai');
    Route::patch('/pegawai/{pegawai}/update_gajiPegawai', [PegawaiController::class, 'update_gajiPegawai'])->name('update_gajiPegawai');
});

Route::middleware(['auth', CheckRole::class . ':owner'])->group(function () {
    Route::group(['prefix' => 'owner'], function () {
        Route::get('/penitip/rekap', [OwnerPenitipController::class, 'rekap'])->name('owner_penitip.rekap');
        Route::get('/presensi', [OwnerPresensiController::class, 'index'])->name('owner.presensi');
        Route::get('/presensi/rekap', [OwnerPresensiController::class, 'rekap'])->name('owner.presensi.rekap');
        Route::get('/presensi/detail/{id}', [OwnerPresensiController::class, 'detail'])->name('owner.presensi.detail');
        Route::get('/laporan/pemasukan', [OwnerLaporanController::class, 'pemasukan'])->name('owner.laporan.pemasukan');
        Route::get('/laporan/pengeluaran', [OwnerLaporanController::class, 'pengeluaran'])->name('owner.laporan.pengeluaran');
    });

    //UNTUK PROFIL OWNER
    Route::get('/owner/profile', [UserController::class, 'profil_owner'])->name('profil_owner');

    // INI ADALAH UNTUK OWNER
    Route::get('/owner/bonus', [PegawaiController::class, 'index_gaji_bonus_karyawan'])->name('index_gaji_bonus_karyawan')->middleware('auth', CheckRole::class . ':owner');
    Route::get('/showGajiPegawai', [PegawaiController::class, 'showGajiPegawai'])
        ->name('showGajiPegawai')
        ->middleware('auth', CheckRole::class . ':owner');
    Route::get('/pegawai/{pegawai}/edit_gajiPegawai', [PegawaiController::class, 'edit_gajiPegawai'])->name('edit_gajiPegawai');
    Route::patch('/pegawai/{pegawai}/update_gajiPegawai', [PegawaiController::class, 'update_gajiPegawai'])->name('update_gajiPegawai');
});

// =============================== CUSTOMER ===============================
Route::middleware(['auth', CheckRole::class . ':customer'])->group(function () {
    Route::group(['prefix' => 'customer', 'as' => 'customer.'], function () {
        // ============ DASHBOARD CUSTOMER ============
        Route::get('dashboard/filter', [DashboardCustomerController::class, 'filter'])->name('dashboard.filter');
        Route::get('dashboard', [DashboardCustomerController::class, 'index'])->name('dashboard');
        // ============ DASHBOARD CUSTOMER ============

        // ============ CART ============
        Route::post('/cart/applyPoints', [CustomerCartController::class, 'applyPoints'])->name('cart.applyPromoPoint');
        Route::post('/cart/applyPromo', [CustomerCartController::class, 'applyPromo'])->name('cart.applyPromo');
        Route::post('/cart/update', [CustomerCartController::class, 'updateCart'])->name('cart.update');
        Route::post('/cart/remove', [CustomerCartController::class, 'removeFromCart'])->name('cart.remove');
        Route::get('/cart', [CustomerCartController::class, 'showCart'])->name('cart');
        Route::post('/cart/checkout', [CustomerCartController::class, 'checkout'])->name('checkout');
        Route::post('/cart/add', [CustomerCartController::class, 'addToCart'])->name('cart.add');
        // ============ CART ============

        // ============ TRANSAKSI ============
        Route::get('/transaksi/create', [CustomerTransaksiController::class, 'create'])->name('transaksi.create');
        Route::post('/transaksi', [CustomerTransaksiController::class, 'store'])->name('transaksi.store');
        Route::post('/transaksi/upload_bukti', [CustomerTransaksiController::class, 'upload_bukti'])->name('transaksi.upload_bukti');
        Route::get('/transaksi/{id}/nota', [CustomerTransaksiController::class, 'showNota'])->name('transaksi.nota');
        // ============ TRANSAKSI ============
    });
});
// =============================== CUSTOMER ===============================

// =============================== MO ===============================
Route::middleware(['auth', CheckRole::class . ':mo'])->group(function () {
    Route::group(['prefix' => 'mo', 'as' => 'mo.'], function () {
        // show_konfirmasi
        Route::get('pesanan', [MoTransaksiController::class, 'pesanan'])->name('pesanan');
        Route::put('pesanan/accept', [MoTransaksiController::class, 'pesanan_accept'])->name('pesanan.accept');
        Route::get('laporan/penjualan', [LaporanController::class, 'penjualan'])->name('laporan.penjualan');
        Route::get('laporan/stok_bb', [LaporanController::class, 'stok_bb'])->name('laporan.stok_bb');
    });
});


// Route::middleware(['auth', CheckRole::class . ':owner'])->group(function () {
//     Route::group(['prefix' => 'owner', 'as' => 'mo.'], function () {
//         // show_konfirmasi
//         Route::get('laporan/penjualan', [LaporanController::class, 'penjualan'])->name('laporan.penjualan');
//         Route::get('laporan/stok_bb', [LaporanController::class, 'stok_bb'])->name('laporan.stok_bb');
//     });
// });


// =============================== CUSTOMER ===============================
//dummy hampers
Route::get('/dummy/hampers', function () {
    // buat dummy hampers untuk dukpro_id 1,2,3
    for ($i = 1; $i <= 3; $i++) {
        $hampers = new \App\Models\Hampers();
        $hampers->nama = 'Hampers ' . $i;
        $hampers->harga = 100000;
        $hampers->stok = 10;
        $hampers->ukuran = 'M';
        $hampers->berat = '1kg';
        $hampers->deskripsi = 'Hampers ' . $i . ' adalah hampers yang sangat bagus';
        $hampers->image = 'https://via.placeholder.com/150';
        $hampers->save();

        $details = new \App\Models\HampersDetail();
        $details->hampers_id = $hampers->id;
        $details->dukpro_id = $i;
        $details->save();
    }
    return 'Hampers berhasil dibuat';
});

// dummy pegawai 10 orang
// protected $fillable = ['user_id', 'nip', 'gaji', 'bonus'];
Route::get('/dummy/pegawai', function () {
    //random gaji harian
    $gaji_harian = [50000, 100000, 150000, 200000, 250000, 300000, 350000, 400000, 450000, 500000];
    $rand_bonus = [0, 50000, 100000, 150000, 200000, 250000, 300000, 350000, 400000, 450000, 500000];
    for ($i = 10; $i <= 20; $i++) {
        // buat user
        $user = new \App\Models\User();
        $user->name = 'pegawai' . $i;
        $user->email = 'pegawai' . $i . '@gmail.com';
        $user->username = 'pegawai' . $i;
        $user->password = bcrypt('12345678');
        $user->role = 'pegawai';
        // addresse, date_of_birth, phone_number, gender
        $user->address = 'JL. alibud ' . $i;
        $user->date_of_birth = '2024-04-29';
        $user->phone_number = '0841662332' . $i;
        $user->gender = 'male';
        $user->save();
        $pegawai = new \App\Models\Pegawai();
        $pegawai->user_id = $user->id;
        $pegawai->nip = '1234567880' . $i;
        $pegawai->gaji = $gaji_harian[array_rand($gaji_harian)];
        $pegawai->bonus = $rand_bonus[array_rand($rand_bonus)];
        $pegawai->save();
    }
    return 'berhasil';
});

//dummy presensi
Route::get('/dummy/presensi', function () {
    $pegawai = \App\Models\Pegawai::all();

    // Mendapatkan bulan dan tahun sekarang
    $bulan = date('m');
    $tahun = date('Y');
    $hari = cal_days_in_month(CAL_GREGORIAN, $bulan, $tahun);

    // Status presensi yang mungkin
    $status = ['hadir', 'izin', 'sakit', 'cuti', 'alpha'];

    foreach ($pegawai as $p) {
        // Mendapatkan angka random antara 1 dan 24
        $angka = rand(1, 24);
        for ($i = 1; $i <= $angka; $i++) {
            $presensi = new \App\Models\Presensi();

            // Mengatur user_id dengan benar
            $presensi->user_id = $p->user_id;

            // Mengubah $i menjadi format dua digit
            $tanggal = str_pad($i, 2, '0', STR_PAD_LEFT);
            $presensi->tanggal = $tahun . '-' . $bulan . '-' . $tanggal;

            // Menetapkan waktu jam_masuk dan jam_keluar secara acak
            $presensi->jam_masuk = date('H:i:s', rand(strtotime('08:00:00'), strtotime('10:00:00')));
            $presensi->jam_keluar = date('H:i:s', rand(strtotime('16:00:00'), strtotime('18:00:00')));

            // Menetapkan status secara acak
            $presensi->status = $status[array_rand($status)];

            // Menyimpan data presensi
            $presensi->save();
        }
    }

    return 'berhasil';
});


//dummy penitip
// nama' => 'required',
// 'alamat' => 'required',
// 'no_ktp' => 'required',
// 'no_telp' => 'required',
// 'mulai_kontrak' => 'required',
// 'akhir_kontrak' => 'required',
// 'status' => 'required',
Route::get('/dummy/penitip', function () {
    $penitip = [
        [
            'nama' => 'penitip 4',
            'alamat' => 'Jl. alibud 1',
            'no_ktp' => '1234567890',
            'no_telp' => '081234567890',
            'mulai_kontrak' => '2024-04-29',
            'akhir_kontrak' => '2024-04-29',
            'status' => 'aktif',
        ],
        [
            'nama' => 'penitip 2',
            'alamat' => 'Jl. alibud 2',
            'no_ktp' => '1234567891',
            'no_telp' => '081234567891',
            'mulai_kontrak' => '2024-04-29',
            'akhir_kontrak' => '2024-04-29',
            'status' => 'aktif',
        ],
        [
            'nama' => 'penitip 3',
            'alamat' => 'Jl. alibud 3',
            'no_ktp' => '1234567892',
            'no_telp' => '081234567892',
            'mulai_kontrak' => '2024-04-29',
            'akhir_kontrak' => '2024-04-29',
            'status' => 'aktif',
        ],
    ];

    foreach ($penitip as $p) {
        $penitip = new Penitip();
        $penitip->nama = $p['nama'];
        $penitip->alamat = $p['alamat'];
        $penitip->no_ktp = $p['no_ktp'];
        $penitip->no_telp = $p['no_telp'];
        $penitip->mulai_kontrak = $p['mulai_kontrak'];
        $penitip->akhir_kontrak = $p['akhir_kontrak'];
        $penitip->status = $p['status'];
        $penitip->save();
    }

    return 'berhasil';
});
