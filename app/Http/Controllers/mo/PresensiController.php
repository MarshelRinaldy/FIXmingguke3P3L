<?php

namespace App\Http\Controllers\mo;

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
        return view('mo.presensi.index', compact('datas'));
    }

    // detail
    public function detail($id)
    {
        $data = Pegawai::find($id);
        return view('mo.presensi.detail', compact('data'));
    }

    //rekap
    public function rekap()
    {
        // presensi bulan ini
        $datas = Pegawai::all();
        return view('mo.presensi.rekap', compact('datas'));
    }

}
