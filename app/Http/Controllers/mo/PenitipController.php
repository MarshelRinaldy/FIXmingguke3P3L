<?php

namespace App\Http\Controllers\mo;

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
    // $table->string('nama');
    // $table->string('alamat');
    // $table->string('no_ktp');
    // $table->string('no_telp');
    // $table->date('mulai_kontrak');
    // $table->date('akhir_kontrak');
    // $table->enum('status', ['aktif', 'tidak aktif']);
    public function index()
    {
        $datas = Penitip::where('status', 'aktif')->get();
        return view('mo.penitip.index', compact('datas'));
    }

    public function create()
    {
        return view('mo.penitip.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required',
            'alamat' => 'required',
            'no_ktp' => 'required',
            'no_telp' => 'required',
            'mulai_kontrak' => 'required',
            'akhir_kontrak' => 'required',
            'status' => 'required',
        ]);

        $simpan = new Penitip;
        $simpan->nama = $request->nama;
        $simpan->alamat = $request->alamat;
        $simpan->no_ktp = $request->no_ktp;
        $simpan->no_telp = $request->no_telp;
        $simpan->mulai_kontrak = $request->mulai_kontrak;
        $simpan->akhir_kontrak = $request->akhir_kontrak;
        $simpan->status = $request->status;
        $simpan->save();

        return redirect()->route('penitip.index')
            ->with('success', 'Penitip created successfully.');
    }

    public function show($id)
    {
        $data = Penitip::find($id);
        return view('mo.penitip.show', compact('data'));
    }

    public function edit($id)
    {
        $penitip = Penitip::find($id);
        return view('mo.penitip.edit', compact('penitip'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'nama' => 'required',
            'alamat' => 'required',
            'no_ktp' => 'required',
            'no_telp' => 'required',
            'mulai_kontrak' => 'required',
            'akhir_kontrak' => 'required',
            'status' => 'required',
        ]);

        $simpan = Penitip::find($id);
        $simpan->nama = $request->nama;
        $simpan->alamat = $request->alamat;
        $simpan->no_ktp = $request->no_ktp;
        $simpan->no_telp = $request->no_telp;
        $simpan->mulai_kontrak = $request->mulai_kontrak;
        $simpan->akhir_kontrak = $request->akhir_kontrak;
        $simpan->status = $request->status;
        $simpan->save();

        return redirect()->route('penitip.index')
            ->with('success', 'Penitip updated successfully');
    }

    public function destroy($id)
    {
        $data = Penitip::find($id);
        $data->delete();

        return redirect()->route('penitip.index')
            ->with('success', 'Penitip deleted successfully');
    }

    //rekap
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
        return view('mo.penitip.rekap', compact('datas', 'total_pemasukan', 'total_pengeluaran', 'total_profit'));
    }
}
