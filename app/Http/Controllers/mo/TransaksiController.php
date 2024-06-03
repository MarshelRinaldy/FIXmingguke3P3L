<?php

namespace App\Http\Controllers\mo;

use App\Models\Dukpro;
use App\Models\Transaksi;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\CustomerSaldo;
use App\Models\PemasukanPerusahaan;
use App\Models\PromoPoint;
use DateTime;


class TransaksiController extends Controller
{

    public function show_konfirmasi_pesanan()
    {
        $transaksis = Transaksi::with('user')
            ->where('status_transaksi', 'menunggu konfirmasi mo')
            ->get();

        return view('mo.konfirmasiPesanan', compact('transaksis'));
    }

    public function konfirmasi_pesanan_accept($id)
    {
        $transaksi = Transaksi::find($id);

        if (!$transaksi) {
            return response()->json(['message' => 'Transaction not found'], 404);
        }

        $detailTransaksis = $transaksi->detailTransaksis;
        $error = [];
        $isPreorder = false;
        $isAvailable = false;

        // if (is_null($detailTransaksis) || $detailTransaksis->isEmpty()) {
        //     return response()->json(['message' => 'No transaction details found'], 404);
        // }

        foreach ($detailTransaksis as $detailTransaksi) {
            $produk = $detailTransaksi->produk;
            $bahan_baku = $produk->bahanBakus;

            foreach ($bahan_baku as $bahan) {
                $stok = $bahan->stok_bahan_baku;
                $jumlah = $detailTransaksi->jumlah_produk * $bahan->pivot->jumlah;

                if ($stok < $jumlah) {
                    $error[] = 'Stok ' . $bahan->nama_bahan_baku . ' tidak mencukupi. Stok tersisa ' . $stok . ' dibutuhkan ' . $jumlah;
                }
            }

            // Check product status
            if ($produk->status == 'Preorder') {
                $isPreorder = true;
            } else if ($produk->status == 'Available') {
                $isAvailable = true;
            }
        }

        if (count($error) > 0) {
            return redirect()->route('mo.show_konfirmasi_pesanan')->with('error', $error);
        }

        // Set transaction status based on product status
        if ($isPreorder) {
            $transaksi->status_transaksi = "menunggu pemrosesan pesanan";
        } else if ($isAvailable) {
            $transaksi->status_transaksi = "sedang dikemas";
        }

        $transaksi->save();
        //kurangi stok bahan baku
        foreach ($detailTransaksis as $detailTransaksi) {
            $produk = $detailTransaksi->produk;
            $bahan_baku = $produk->bahanBakus;
            foreach ($bahan_baku as $bahan) {
                $stok = $bahan->stok_bahan_baku;
                $jumlah = $detailTransaksi->jumlah_produk * $bahan->pivot->jumlah;
                $bahan->stok_bahan_baku = $stok - $jumlah;
                $bahan->save();
            }
        }

        // Setiap pemesanan dengan kelipatan 10.000 mendapatkan 1 poin.
        // Setiap pemesanan dengan kelipatan 100.000 mendapatkan 15 poin.
        // Setiap pemesanan dengan kelipatan 500.000 mendapatkan 75 poin.
        // Setiap pemesanan dengan kelipatan 1.000.000 mendapatkan 200 poin.
        // Poin dikali 2 untuk pemesanan yang dilakukan di hari ulang tahun customer, dengan  periode H-3 sampai dengan H+3.
        // Menghitung poin berdasarkan kelipatan yang berbeda
        $pembelian = $transaksi->total_harga;
        $poin = 0;
        if ($pembelian >= 1000000) {
            $poin += intdiv($pembelian, 1000000) * 200;
            $pembelian %= 1000000;
        }
        if ($pembelian >= 500000) {
            $poin += intdiv($pembelian, 500000) * 75;
            $pembelian %= 500000;
        }
        if ($pembelian >= 100000) {
            $poin += intdiv($pembelian, 100000) * 15;
            $pembelian %= 100000;
        }
        if ($pembelian >= 10000) {
            $poin += intdiv($pembelian, 10000);
        }

        $tanggal_lahir = $transaksi->user->date_of_birth;
        $tanggal_transaksi = $transaksi->tanggal_transaksi;
        // Menghitung periode H-3 sampai H+3 dari tanggal lahir
        $dob = new DateTime($tanggal_lahir);
        $tanggal_transaksi = new DateTime($tanggal_transaksi);

        $start = (clone $dob)->modify('-3 days');
        $end = (clone $dob)->modify('+3 days');

        // Sesuaikan tahun transaksi dengan tahun tanggal lahir
        $start->setDate($tanggal_transaksi->format('Y'), $start->format('m'), $start->format('d'));
        $end->setDate($tanggal_transaksi->format('Y'), $end->format('m'), $end->format('d'));

        // Jika transaksi dalam periode H-3 sampai H+3
        if ($tanggal_transaksi >= $start && $tanggal_transaksi <= $end) {
            $poin *= 2;
        }

        // Menambahkan poin ke user
        $point = new PromoPoint();
        $point->user_id = $transaksi->user_id;
        $point->nama = $transaksi->user->name;
        $point->jumlah_point = $poin;
        $point->tanggal_dimulai = now();
        $point->tanggal_berakhir = now()->addMonth();
        $point->deskripsi = 'Poin dari transaksi ' . $transaksi->id;
        $point->save();


        return redirect()->route('mo.show_konfirmasi_pesanan')->with('success', 'Pesanan berhasil diterima dan segera di proses');
    }

    // public function konfirmasi_pesanan_reject($id)
    // {
    //     $transaksi = Transaksi::find($id);

    //     if (!$transaksi) {
    //         return response()->json(['message' => 'Transaction not found'], 404);
    //     }
    //     //tambah saldo customer
    //     $saldo = new CustomerSaldo();
    //     $saldo->user_id = $transaksi->user_id;
    //     $saldo->jenis_transaksi = 'refund';
    //     $saldo->jumlah = $transaksi->total_harga;
    //     $saldo->saldo = $transaksi->user->saldo + $transaksi->total_harga;
    //     $saldo->keterangan = 'Refund pesanan ' . $transaksi->id;
    //     $saldo->status = 'success';
    //     $saldo->save();

    //     $transaksi->status_transaksi = "ditolak";
    //     $transaksi->save();

    //     $transaksi = Transaksi::find($id);

    //     if (!$transaksi) {
    //         return response()->json(['message' => 'Transaction not found'], 404);
    //     }

    //     // Mengakses detail transaksi untuk mendapatkan informasi stok bahan baku
    //     foreach ($transaksi->detailTransaksis as $detailTransaksi) {
    //         foreach ($detailTransaksi->produk->bahan_bakus as $bahan) {
    //             // Perbarui nilai stok bahan baku
    //             // if (!array_key_exists($item->id, $stok)) {
    //             //     $stok_bahan[$bahan->id] = $bahan->stok_bahan_baku - ($detailTransaksi->jumlah * $bahan->pivot->jumlah);
    //             // } else {
    //             //     $stok_bahan[$bahan->id] -= $detailTransaksi->jumlah * $bahan->pivot->jumlah;
    //             // }

    //             if (isset($stok_bahan[$bahan->id])) {
    //                 $stok_bahan[$bahan->id] -= $detailTransaksi->jumlah * $bahan->pivot->jumlah;
    //             } else {
    //                 $stok_bahan[$bahan->id] = $bahan->stok_bahan_baku - ($detailTransaksi->jumlah * $bahan->pivot->jumlah);
    //             }
    //         }
    //     }

    //     return redirect()->route('mo.show_konfirmasi_pesanan')->with('success', 'Pesanan berhasil ditolak');
    // }

    public function konfirmasi_pesanan_reject($id)
    {
        $transaksi = Transaksi::find($id);

        if (!$transaksi) {
            return response()->json(['message' => 'Transaction not found'], 404);
        }

        //tambah saldo customer
        $saldo = new CustomerSaldo();
        $saldo->user_id = $transaksi->user_id;
        $saldo->jenis_transaksi = 'refund';
        $saldo->jumlah = $transaksi->total_harga;
        $saldo->saldo = $transaksi->user->saldo + $transaksi->total_harga;
        $saldo->keterangan = 'Refund pesanan ' . $transaksi->id;
        $saldo->status = 'success';
        $saldo->save();

        $transaksi->status_transaksi = "ditolak";
        $transaksi->save();

        $transaksi = Transaksi::find($id);

        if (!$transaksi) {
            return response()->json(['message' => 'Transaction not found'], 404);
        }

        // Mengakses detail transaksi untuk mendapatkan informasi stok bahan baku
        // foreach ($transaksi->detailTransaksis as $detailTransaksi) {
        //     foreach ($detailTransaksi->produk->bahan_bakus as $bahan) {
        //         // Perbarui nilai stok bahan baku
        //         if (!array_key_exists($item->id, $stok)) {
        //             $stok_bahan[$bahan->id] = $bahan->stok_bahan_baku - ($detailTransaksi->jumlah * $bahan->pivot->jumlah);
        //         } else {
        //             $stok_bahan[$bahan->id] -= $detailTransaksi->jumlah * $bahan->pivot->jumlah;
        //         }

        //         // if (isset($stok_bahan[$bahan->id])) {
        //         //     $stok_bahan[$bahan->id] -= $detailTransaksi->jumlah * $bahan->pivot->jumlah;
        //         // } else {
        //         //     $stok_bahan[$bahan->id] = $bahan->stok_bahan_baku - ($detailTransaksi->jumlah * $bahan->pivot->jumlah);
        //         // }
        //     }
        // }

        // foreach ($transaksi->detailTransaksis as $detailTransaksi) {
        //     foreach ($detailTransaksi->produk->bahan_bakus as $bahan) {
        //         // Perbarui nilai stok bahan baku
        //         if (!array_key_exists($bahan->id, $stok_bahan)) {
        //             $stok_bahan[$bahan->id] = $bahan->stok_bahan_baku - ($detailTransaksi->jumlah * $bahan->pivot->jumlah);
        //         } else {
        //             $stok_bahan[$bahan->id] -= $detailTransaksi->jumlah * $bahan->pivot->jumlah;
        //         }

        //         // if (isset($stok_bahan[$bahan->id])) {
        //         //     $stok_bahan[$bahan->id] -= $detailTransaksi->jumlah * $bahan->pivot->jumlah;
        //         // } else {
        //         //     $stok_bahan[$bahan->id] = $bahan->stok_bahan_baku - ($detailTransaksi->jumlah * $bahan->pivot->jumlah);
        //         // }
        //     }
        // }

        // $stok_bahan = [];  // Inisialisasi array kosong untuk menyimpan stok bahan baku

        // foreach ($transaksi->detailTransaksis as $detailTransaksi) {
        //     foreach ($detailTransaksi->produk->bahan_bakus as $bahan) {
        //         // Perbarui nilai stok bahan baku
        //         if (!array_key_exists($bahan->id, $stok_bahan)) {
        //             $stok_bahan[$bahan->id] = $bahan->stok_bahan_baku - ($detailTransaksi->jumlah * $bahan->pivot->jumlah);
        //         } else {
        //             $stok_bahan[$bahan->id] -= $detailTransaksi->jumlah * $bahan->pivot->jumlah;
        //         }
        //     }
        // }

        $stok_bahan = [];  // Inisialisasi array kosong untuk menyimpan stok bahan baku

        foreach ($transaksi->detailTransaksis as $detailTransaksi) {
            if (isset($detailTransaksi->produk) && isset($detailTransaksi->produk->bahan_bakus)) {
                foreach ($detailTransaksi->produk->bahan_bakus as $bahan) {
                    if (isset($bahan->id) && isset($bahan->stok_bahan_baku) && isset($bahan->pivot->jumlah)) {
                        // Perbarui nilai stok bahan baku
                        if (!array_key_exists($bahan->id, $stok_bahan)) {
                            $stok_bahan[$bahan->id] = $bahan->stok_bahan_baku - ($detailTransaksi->jumlah * $bahan->pivot->jumlah);
                        } else {
                            $stok_bahan[$bahan->id] -= $detailTransaksi->jumlah * $bahan->pivot->jumlah;
                        }
                    }
                }
            }
        }

        return redirect()->route('mo.show_konfirmasi_pesanan')->with('success', 'Pesanan berhasil ditolak');
    }
}
