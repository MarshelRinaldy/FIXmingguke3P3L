<?php

namespace App\Http\Controllers\mo;

use App\Models\Transaksi;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\CustomerSaldo;
use App\Models\PemasukanPerusahaan;
use App\Models\PromoPoint;
use DateTime;

class TransaksiPOController extends Controller
{

    public function pesanan(){
        $transaksis = Transaksi::with('user')
        ->where('status_transaksi', 'menunggu pemrosesan pesanan')
        ->get();

        return view('mo.pesanan.pemrosesanpesanan', compact('transaksis'));
    }

    public function pesanan_accept(Request $request)
    {
        // cek reqeust trans_id
        $request->validate([
            'trans_id' => 'required',
        ]);
        // $transaksi = Transaksi::find($id);
        $list_trans_id = [];
        foreach($request->trans_id as $id){
            $transaksi = Transaksi::find($id);
            if (!$transaksi) {
                return response()->json(['message' => 'Transaction not found'], 404);
            }
            if (!$transaksi) {
                return response()->json(['message' => 'Transaction not found'], 404);
            }
            $detailTransaksis = $transaksi->detailTransaksis;
            $error = [];
            $stok_bahan =[];
            $jumlah = 0;
            foreach ($detailTransaksis as $detailTransaksi) {
                if($detailTransaksi->is_hampers){
                    $hampers = $detailTransaksi->hampers;
                    $hampers_detail = $hampers->details;
                    foreach ($hampers_detail as $hamper) {
                        $produk = $hamper->dukpro;
                        $bahan_baku = $produk->bahanBakus;
                        foreach ($bahan_baku as $bahan) {
                            $stok = $stok_bahan[$bahan->id] ?? $bahan->stok_bahan_baku;
                            $jumlah = $detailTransaksi->jumlah * $bahan->pivot->jumlah;
                            if ($stok < $jumlah) {
                                $error[] = 'Stok ' . $bahan->nama_bahan_baku . ' tidak mencukupi. Stok tersisa ' . $stok. ' dibutuhkan ' . $jumlah;
                            }
                            if(!array_key_exists($bahan->id, $stok_bahan)){
                                $stok_bahan[$bahan->id] = $bahan->stok_bahan_baku - ($hamper->pivot->jumlah * $bahan->pivot->jumlah);
                            }else{
                                $stok_bahan[$bahan->id] -= $detailTransaksi->jumlah * $bahan->pivot->jumlah;
                            }
                        }
                    }
                }else{
                    $produk = $detailTransaksi->produk;
                    $bahan_baku = $produk->bahanBakus;
                    foreach ($bahan_baku as $bahan) {
                        $stok = $stok_bahan[$bahan->id] ?? $bahan->stok_bahan_baku;
                        $jumlah = $detailTransaksi->jumlah_produk * $bahan->pivot->jumlah;
                        if ($stok < $jumlah) {
                            $error[] = 'Stok ' . $bahan->nama_bahan_baku . ' tidak mencukupi. Stok tersisa ' . $stok. ' dibutuhkan ' . $jumlah;
                        }
                        if(!array_key_exists($bahan->id, $stok_bahan)){
                            $stok_bahan[$bahan->id] = $bahan->stok_bahan_baku - ($detailTransaksi->jumlah_produk * $bahan->pivot->jumlah);
                        }else{
                            $stok_bahan[$bahan->id] -= $detailTransaksi->jumlah_produk * $bahan->pivot->jumlah;
                        }
                    }
                }

            }
            if(count($error) > 0){
                return redirect()->route('mo.pemrosesanpesanan')->with('error', $error);
            }

            $transaksi->status_transaksi = "sedang dikemas";
            $transaksi->save();
            //kurangi stok bahan baku
            foreach ($detailTransaksis as $detailTransaksi) {
                if($detailTransaksi->is_hampers){
                    $hampers = $detailTransaksi->hampers;
                    $hampers_detail = $hampers->details;
                    foreach ($hampers_detail as $hamper) {
                        $produk = $hamper->dukpro;
                        $bahan_baku = $produk->bahanBakus;
                        foreach ($bahan_baku as $bahan) {
                            $stok = $bahan->stok_bahan_baku;
                            $jumlah = $detailTransaksi->jumlah * $bahan->pivot->jumlah;
                            $bahan->stok_bahan_baku = $stok - $jumlah;
                            $bahan->save();
                        }
                    }
                }else{
                    $produk = $detailTransaksi->produk;
                    $bahan_baku = $produk->bahanBakus;
                    foreach ($bahan_baku as $bahan) {
                        $stok = $bahan->stok_bahan_baku;
                        $jumlah = $detailTransaksi->jumlah_produk * $bahan->pivot->jumlah;
                        $bahan->stok_bahan_baku = $stok - $jumlah;
                        $bahan->save();
                    }
                }
            }
            // $pembelian = $transaksi->total_harga;
            // $poin = 0;
            // if ($pembelian >= 1000000) {
            //     $poin += intdiv($pembelian, 1000000) * 200;
            //     $pembelian %= 1000000;
            // }
            // if ($pembelian >= 500000) {
            //     $poin += intdiv($pembelian, 500000) * 75;
            //     $pembelian %= 500000;
            // }
            // if ($pembelian >= 100000) {
            //     $poin += intdiv($pembelian, 100000) * 15;
            //     $pembelian %= 100000;
            // }
            // if ($pembelian >= 10000) {
            //     $poin += intdiv($pembelian, 10000);
            // }

            // $tanggal_lahir = $transaksi->user->date_of_birth;
            // $tanggal_transaksi = $transaksi->tanggal_transaksi;
            // // Menghitung periode H-3 sampai H+3 dari tanggal lahir
            // $dob = new DateTime($tanggal_lahir);
            // $tanggal_transaksi = new DateTime($tanggal_transaksi);

            // $start = (clone $dob)->modify('-3 days');
            // $end = (clone $dob)->modify('+3 days');

            // // Sesuaikan tahun transaksi dengan tahun tanggal lahir
            // $start->setDate($tanggal_transaksi->format('Y'), $start->format('m'), $start->format('d'));
            // $end->setDate($tanggal_transaksi->format('Y'), $end->format('m'), $end->format('d'));

            // // Jika transaksi dalam periode H-3 sampai H+3
            // if ($tanggal_transaksi >= $start && $tanggal_transaksi <= $end) {
            //     $poin *= 2;
            // }

            // // Menambahkan poin ke user
            // $point = new PromoPoint();
            // // $point->user_id = $transaksi->user_id;
            // $point->nama = $transaksi->user->name;
            // $point->jumlah_point = $poin;
            // $point->tanggal_dimulai = now();
            // $point->tanggal_berakhir = now()->addMonth();
            // $point->deskripsi = 'Poin dari transaksi ' . $transaksi->id;
            // $point->save();


            return redirect()->route('mo.pemrosesanpesanan')->with('success', 'Pesanan berhasil diterima dan segera di proses');
        }
    }

}
