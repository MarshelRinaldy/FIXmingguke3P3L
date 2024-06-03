<?php

namespace App\Http\Controllers;

use App\Models\Transaksi;
use Illuminate\Http\Request;
use App\Models\PemasukanPerusahaan;
use App\Http\Controllers\Controller;

class PemasukanPerusahaanController extends Controller
{
    public function store_pemasukan_perusahaan(Request $request)
{
    // Validate the request data
    // $request->validate([
    //     'transaksi_id' => 'required|exists:transaksis,id',
    //     'jumlah_income' => 'required|numeric',
    //     'deskripsi' => 'nullable|string',
    // ]);

    // Find the transaction
    $transaksi = Transaksi::find($request->input('transaksi_id'));

    // Check if the income amount is less than the total price
    $jumlah_income = $request->input('jumlah_income');
    if ($jumlah_income < $transaksi->total_harga) {
        return redirect()->route('show_konfirmasi_pesanan')->with('error', 'Jumlah pemasukan kurang dari total harga transaksi.');
    }

    // Calculate the tip if income is greater than total price
    $tip_lebihan = $jumlah_income - $transaksi->total_harga;

    // Create a new PemasukanPerusahaan record
    PemasukanPerusahaan::create([
    'transaksi_id' => $request->input('transaksi_id'),
    'tanggal_income' => now(),
    'jumlah_income' => ($tip_lebihan > 0) ? ($jumlah_income - $tip_lebihan) : $jumlah_income,
    'tip_lebihan' => $tip_lebihan,
    'deskripsi' => $request->input('deskripsi'),
]);


    // Update transaction status
    $transaksi->update(['status_transaksi' => 'menunggu konfirmasi mo', 'status_pembayaran' => 'sudah bayar']);

    // Redirect with success message
    return redirect()->route('show_konfirmasi_pesanan')->with('success', 'Pemasukan perusahaan berhasil ditambahkan.');
}


}
