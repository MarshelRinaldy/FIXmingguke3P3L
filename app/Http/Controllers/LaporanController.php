<?php

namespace App\Http\Controllers;

use DateTime;
use App\Models\Transaksi;
use Illuminate\Http\Request;
use App\Models\BahanBakuUsage;
use App\Models\PemasukanPerusahaan;

class LaporanController extends Controller
{
    public function show_laporan_penjualan_keseluruhan(Request $request) {
    // Get the start and end dates from the request
    $startDate = $request->input('start_date');
    $endDate = $request->input('end_date');

    // Query the data with optional date range filtering
    $query = PemasukanPerusahaan::selectRaw('MONTH(tanggal_income) as bulan, COUNT(*) as jumlah_transaksi, SUM(jumlah_income + tip_lebihan) as jumlah_uang, SUM(tip_lebihan) as total_tip');

    if ($startDate && $endDate) {
        $query->whereBetween('tanggal_income', [$startDate, $endDate]);
    }

    $transaksiPerBulan = $query->groupByRaw('MONTH(tanggal_income)')
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

    // Get the logged-in user
    $user = auth()->user();

    // Check the user's role and return the appropriate view
    if ($user->role === 'owner') {
        return view('ownerandmo.penjualanBulananKeseluruhanLaporan', compact('data', 'startDate', 'endDate'));
    } elseif ($user->role === 'mo') {
        return view('MO.laporan.penjualanBulananKeseluruhanLaporan', compact('data', 'startDate', 'endDate'));
    }

    // Optionally, handle other roles or unauthorized access
    abort(403, 'Unauthorized action.');
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

    // Get the logged-in user
    $user = auth()->user();

    // Check the user's role and return the appropriate view
    if ($user->role === 'owner') {
        return view('ownerandmo.penggunaanBahanbakuLaporan', compact('usageData', 'startDate', 'endDate'));
    } elseif ($user->role === 'mo') {
        return view('MO.laporan.penggunaanBahanbakuLaporan', compact('usageData', 'startDate', 'endDate'));
    }

    // Optionally, handle other roles or unauthorized access
    abort(403, 'Unauthorized action.');
}





}
