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

        return view('mo.laporan.penjualan', compact('transaksis', 'produkTerjual'));
    }

    public function stok_bb()
    {
        $bahanBakus = BahanBaku::all();

        return view('mo.laporan.stok_bb', compact('bahanBakus'));
    }

    public function pemasukan()
    {
        $datas = PemasukanPerusahaan::whereMonth('created_at', date('m'))->get();
        return view('mo.laporan.pemasukan', compact('datas'));
    }

    public function pengeluaran()
    {
        $tanggalSekarang = now()->format('Y-m-d');
        $bahanBakus = BahanBaku::all();
        $pengeluaranLain = PencatatanPengeluaranLain::where('tanggal_pengeluaran', $tanggalSekarang)->get();

        // Filter bahan baku selain listrik dan air
        $bahanBakusLain = $bahanBakus->filter(function ($bahanBaku) {
            return !in_array($bahanBaku->nama_bahan_baku, ['Listrik', 'Air']);
        });

        // Menghitung total harga bahan baku
        $totalHargaBahanBaku = $bahanBakusLain->sum(function ($bahanBaku) {
            return $bahanBaku->harga_bahan_baku * $bahanBaku->stok_bahan_baku;
        });

        // Periksa apakah entri untuk pembelian bahan baku sudah ada untuk tanggal hari ini
        $existingPembelian = PencatatanPengeluaranLain::where('nama_pengeluaran', 'Pembelian Bahan Baku')
            ->where('tanggal_pengeluaran', $tanggalSekarang)
            ->exists();

        // Jika belum ada entri, maka buat entri baru
        if (!$existingPembelian) {
            $pengeluaranBahanBaku = new PencatatanPengeluaranLain();
            $pengeluaranBahanBaku->nama_pengeluaran = "Pembelian Bahan Baku";
            $pengeluaranBahanBaku->harga_pengeluaran = $totalHargaBahanBaku;
            $pengeluaranBahanBaku->tanggal_pengeluaran = $tanggalSekarang;
            $pengeluaranBahanBaku->kategori_pengeluaran = "Pembelian";
            $pengeluaranBahanBaku->save();
        }

        // Ambil data pengeluaran lain dan bahan baku
        $datas = PencatatanPengeluaranLain::whereMonth('created_at', date('m'))->get();

        return view('mo.laporan.pengeluaran', compact('datas', 'bahanBakus'));
    }
}
