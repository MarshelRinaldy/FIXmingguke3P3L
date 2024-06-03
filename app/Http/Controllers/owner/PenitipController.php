<?php

namespace App\Http\Controllers\owner;

use App\Models\Transaksi;
use App\Http\Controllers\Controller;
use App\Models\CustomerSaldo;
use App\Models\Pegawai;
use App\Models\PemasukanPerusahaan;
use App\Models\PencatatanPengeluaranLain;
use App\Models\Penitip;
use App\Models\Presensi;
use DateTime;
use Illuminate\Http\Request;

class PenitipController extends Controller
{
    public function rekap()
    {
        // presensi bulan ini
        $datas = Penitip::all();
        //mencari laba
        //total pemasukan bulan dan tahun ini
        $total_pemasukan = PemasukanPerusahaan::whereMonth('tanggal_income', date('m'))->whereYear('tanggal_income', date('Y'))->sum('jumlah_income');
        //total pengeluaran bulan ini
        $total_pengeluaran = PencatatanPengeluaranLain::whereMonth('tanggal_pengeluaran', date('m'))->whereYear('tanggal_pengeluaran', date('Y'))->sum('harga_pengeluaran');
        $total_profit = $total_pemasukan - $total_pengeluaran;
        return view('owner.penitip.rekap', compact('datas', 'total_pemasukan', 'total_pengeluaran', 'total_profit'));
    }
}
