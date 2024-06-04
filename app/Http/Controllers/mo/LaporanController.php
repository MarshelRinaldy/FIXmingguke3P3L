<?php

namespace App\Http\Controllers\mo;

use DateTime;
use App\Models\BahanBaku;
use App\Models\Transaksi;
use App\Models\PromoPoint;
use Illuminate\Http\Request;
use App\Models\CustomerSaldo;
use App\Models\PemasukanPerusahaan;
use App\Http\Controllers\Controller;
use App\Models\catatBB;
use App\Models\PencatatanPengeluaranLain;

class LaporanController extends Controller
{

    public function penjualan()
    {
        // tahun dan bulan sekarang
        $tahun = date('Y');
        $bulan = date('m');
        $transaksis = Transaksi::with('detailTransaksis.produk', 'detailTransaksis.hampers')
            ->where('status_transaksi', 'selesai')
            ->whereYear('tanggal_transaksi', $tahun)
            ->whereMonth('tanggal_transaksi', $bulan)
            ->get();

        // Inisialisasi array untuk menghitung jumlah produk yang terjual
        $produkTerjual = [];
        $detail_transaksi = [];

        // Iterasi melalui setiap transaksi dan hitung jumlah produk yang terjual
        foreach ($transaksis as $transaksi) {
            foreach ($transaksi->detailTransaksis as $detail) {
                if ($detail->is_hampers) {
                    $hampers = $detail->hampers;
                    $hampers_detail = $hampers->details;
                    foreach ($hampers_detail as $hamper) {
                        $produkId = $hamper->dukpro_id;
                        $jumlah = $detail->jumlah;
                        $detail_transaksi[] = $produkId;
                        if (isset($produkTerjual[$produkId])) {
                            $produkTerjual[$produkId] += $jumlah;
                        } else {
                            $produkTerjual[$produkId] = $jumlah;
                        }
                    }
                } else {
                    $produkId = $detail->produk_id;
                    $jumlah = $detail->jumlah_produk;
                    $detail_transaksi[] = $produkId;
                    if (isset($produkTerjual[$produkId])) {
                        $produkTerjual[$produkId] += $jumlah;
                    } else {
                        $produkTerjual[$produkId] = $jumlah;
                    }
                }
            }
        }

        $user = auth()->user();

    if ($user->role === 'owner') {
        return view('owner.penjualan', compact('transaksis', 'produkTerjual'));
    } elseif ($user->role === 'mo') {
        return view('mo.laporan.penjualan', compact('transaksis', 'produkTerjual'));
    }

        
    }

    public function stok_bb()
    {
        $bahanBakus = BahanBaku::all();


         $user = auth()->user();

        if ($user->role === 'owner') {
         return view('owner.stok_bb', compact('bahanBakus'));
        } elseif ($user->role === 'mo') {
            return view('mo.laporan.stok_bb', compact('bahanBakus'));
        }

       
    }

    public function pemasukan()
    {
        $datas = PemasukanPerusahaan::whereMonth('created_at', date('m'))->get();


         $user = auth()->user();

        if ($user->role === 'owner') {
            return view('owner.pemasukan', compact('datas'));
        } elseif ($user->role === 'mo') {
            return view('mo.laporan.pemasukan', compact('datas'));
        }

        
    }

    public function pengeluaran()
{
    $tanggalSekarang = now()->format('Y-m-d');
    $bahanBakus = catatBB::all();
    $pengeluaranLain = PencatatanPengeluaranLain::where('tanggal_pengeluaran', $tanggalSekarang)->get();

    // Hitung total biaya bahan baku
    $totalBiayaBB = 0;
    foreach ($bahanBakus as $bb) {
        $totalBiayaBB += $bb->jumlah * $bb->harga;
    }

    // Ambil data pengeluaran lain dan bahan baku
    $datas = PencatatanPengeluaranLain::whereMonth('created_at', date('m'))->get();

    $user = auth()->user();

    if ($user->role === 'owner') {
        return view('owner.pengeluaran', compact('datas', 'bahanBakus', 'totalBiayaBB'));
    } elseif ($user->role === 'mo') {
        return view('mo.laporan.pengeluaran', compact('datas', 'bahanBakus', 'totalBiayaBB'));
    }
}
}
