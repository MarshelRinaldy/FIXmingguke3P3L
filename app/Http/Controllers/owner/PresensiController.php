<?php

namespace App\Http\Controllers\owner;

use App\Models\Transaksi;
use App\Http\Controllers\Controller;
use App\Models\CustomerSaldo;
use App\Models\Pegawai;
use App\Models\Presensi;
use DateTime;
use Illuminate\Http\Request;

class PresensiController extends Controller
{
    // show_penarikan_saldo
    public function index()
    {
        $datas = Pegawai::all();
        return view('owner.presensi.index', compact('datas'));
    }

    // detail
    public function detail($id)
    {
        $data = Pegawai::find($id);
        return view('owner.presensi.detail', compact('data'));
    }

    //rekap
    public function rekap()
    {
        // presensi bulan ini
        $datas = Pegawai::all();
        return view('owner.presensi.rekap', compact('datas'));
    }

}
