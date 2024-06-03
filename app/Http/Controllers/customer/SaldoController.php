<?php

namespace App\Http\Controllers\customer;

use App\Models\Transaksi;
use App\Http\Controllers\Controller;
use App\Models\CustomerSaldo;
use DateTime;
use Illuminate\Http\Request;

class SaldoController extends Controller
{
    // riwayat
    public function riwayat_saldo()
    {
        $riwayat_saldo = CustomerSaldo::where('user_id', auth()->user()->id);
        if ($riwayat_saldo->exists()) {
            $total_saldo = $riwayat_saldo->first()->saldo;
        } else {
            $total_saldo = 0;
        }
        $saldo = $riwayat_saldo->get();
        return view('customer.riwayat_saldo', compact('riwayat_saldo', 'total_saldo'));
    }


    // tarik
    public function tarik_saldo()
    {
        $riwayat_saldo = CustomerSaldo::where('user_id', auth()->user()->id);
        if ($riwayat_saldo->exists()) {
            $total_saldo = $riwayat_saldo->first()->saldo;
        } else {
            $total_saldo = 0;
        }
        return view('customer.tarik_saldo', compact('total_saldo', 'riwayat_saldo'));
    }

    public function tarik_saldo_store(Request $request)
    {
        $error = [];
        $request->validate([
            'jumlah' => 'required',
            'bank' => 'required',
            'norek' => 'required',
            'nama' => 'required',
        ]);


        $riwayat_saldo = CustomerSaldo::where('user_id', auth()->user()->id);
        if ($riwayat_saldo->exists()) {
            $total_saldo = $riwayat_saldo->first()->saldo;
        } else {
            $total_saldo = 0;
        }

        $saldo = $riwayat_saldo->get();

        if ($total_saldo < request('jumlah')) {
            $error[] = 'Saldo tidak cukup';
            //back
            return back()->withErrors($error);
        }

        $simpan = new CustomerSaldo();
        $simpan->user_id = auth()->user()->id;
        // jenis,transaksi, jumlah, keterangan, status
        $simpan->jenis_transaksi = 'penarikan';
        $simpan->jumlah = request('jumlah');
        $simpan->keterangan = 'penarikan saldo';
        $simpan->status = 'pending';
        $simpan->saldo = $total_saldo - request('jumlah');
        $simpan->bank = request('bank');
        $simpan->norek = request('norek');
        $simpan->nama = request('nama');
        $simpan->save();
        return redirect()->route('riwayat_saldo')->with('success', 'Permintan penarikan saldo berhasil');
    }
}
