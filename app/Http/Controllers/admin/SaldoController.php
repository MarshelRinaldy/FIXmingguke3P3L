<?php

namespace App\Http\Controllers\admin;

use App\Models\Transaksi;
use App\Http\Controllers\Controller;
use App\Models\CustomerSaldo;
use DateTime;
use Illuminate\Http\Request;

class SaldoController extends Controller
{
    // show_penarikan_saldo
    public function show_penarikan_saldo()
    {
        $riwayat_saldo = CustomerSaldo::all();
        return view('admin.riwayat_penarikan_saldo', compact('riwayat_saldo'));
    }

    // penarikan_saldo_accept
    public function penarikan_saldo_accept($id)
    {
        $riwayat_saldo = CustomerSaldo::find($id);
        $riwayat_saldo->status = 'success';
        $riwayat_saldo->save();
        return redirect()->back()->with('success', 'Berhasil menyetujui penarikan saldo');
    }

    // penarikan_saldo_decline
    public function penarikan_saldo_decline($id)
    {
        $riwayat_saldo = CustomerSaldo::find($id);
        $riwayat_saldo->status = 'failed';
        $riwayat_saldo->save();
        return redirect()->back()->with('success', 'Berhasil menolak penarikan saldo');
    }

}
