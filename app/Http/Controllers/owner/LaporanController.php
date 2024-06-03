<?php

namespace App\Http\Controllers\owner;

use App\Models\Transaksi;
use App\Http\Controllers\Controller;
use App\Models\CustomerSaldo;
use App\Models\Pegawai;
use App\Models\PemasukanPerusahaan;
use App\Models\PencatatanPengeluaranLain;
use App\Models\Presensi;
use DateTime;
use Illuminate\Http\Request;

class LaporanController extends Controller
{
    public function pemasukan()
    {
        $datas = PemasukanPerusahaan::whereMonth('created_at', date('m'))->get();
        return view('owner.laporan.pemasukan', compact('datas'));
    }

    public function pengeluaran()
    {
        $datas = PencatatanPengeluaranLain::whereMonth('created_at', date('m'))->get();
        return view('owner.laporan.pengeluaran', compact('datas'));
    }

}
