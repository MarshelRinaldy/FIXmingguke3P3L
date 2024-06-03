<?php

namespace App\Http\Controllers;

use DateTime;
use App\Models\Transaksi;
use Illuminate\Http\Request;
use App\Models\BahanBakuUsage;
use App\Models\PemasukanPerusahaan;

class LaporanController extends Controller
{
    public function show_laporan_penjualan_keseluruhan() {
    $transaksiPerBulan = PemasukanPerusahaan::selectRaw('MONTH(tanggal_income) as bulan, COUNT(*) as jumlah_transaksi, SUM(jumlah_income + tip_lebihan) as jumlah_uang, SUM(tip_lebihan) as total_tip')
        ->groupByRaw('MONTH(tanggal_income)')
        ->orderByRaw('MONTH(tanggal_income)')
        ->get()
        ->keyBy('bulan');

    $data = [];
    for ($i = 1; $i <= 12; $i++) {
        if (isset($transaksiPerBulan[$i])) {
            $data[$i] = $transaksiPerBulan[$i];
        } else {
            $data[$i] = (object) ['bulan' => $i, 'jumlah_transaksi' => 0, 'jumlah_uang' => 0, 'total_tip' => 0];
        }
    }

    return view('ownerandmo.penjualanBulananKeseluruhanLaporan', compact('data'));
}


public function show_chart_penjualan_bulanan() {
    $transaksiPerBulan = PemasukanPerusahaan::selectRaw('MONTH(tanggal_income) as bulan, SUM(jumlah_income) as total_uang')
        ->groupByRaw('MONTH(tanggal_income)')
        ->orderByRaw('MONTH(tanggal_income)')
        ->get();

    $labels = [];
    $data = [];

    foreach ($transaksiPerBulan as $transaksi) {
        $labels[] = DateTime::createFromFormat('!m', $transaksi->bulan)->format('F');
        $data[] = $transaksi->total_uang;
    }

    return view('ownerandmo.chart_penjualan_bulanan', compact('labels', 'data'));
}


// Controller
public function show_laporan_penggunaan_bahanbaku(Request $request)
{
    // Fetch data from the database
    $startDate = $request->input('start_date');
    $endDate = $request->input('end_date');

    $query = BahanBakuUsage::query();

    if ($startDate && $endDate) {
        $query->whereBetween('tanggal_transaksi', [$startDate, $endDate]);
    }

    $usageData = $query->with('bahanBaku')
        ->selectRaw('bahan_baku_id, SUM(jumlah_digunakan) as total_penggunaan')
        ->groupBy('bahan_baku_id')
        ->get();

    return view('ownerandmo.penggunaanBahanbakuLaporan', compact('usageData', 'startDate', 'endDate'));
}




}
