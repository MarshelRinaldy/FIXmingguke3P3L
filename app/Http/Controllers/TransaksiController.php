<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Hampers;
use App\Models\BahanBaku;
use App\Models\Transaksi;
use Illuminate\Http\Request;
use App\Models\HampersDetail;
use App\Models\BahanBakuUsage;
use App\Http\Controllers\Controller;

class TransaksiController extends Controller
{

    public function show_pengiriman(){
        $transaksis = Transaksi::with('user')
        ->where('status_transaksi', 'menunggu konfirmasi ongkir')
        ->get();

        // dd($transaksis);

    return view('admin.inputJarakPengiriman', compact('transaksis'));
    }

    public function update_pengiriman($id)
    {
      
        $transaksi = Transaksi::with('user')->findOrFail($id);

      
        return view('admin.updatePengiriman', compact('transaksi'));
    }

    public function input_pengiriman(Request $request, $id, $total_harga)
    {
       
        $transaksi = Transaksi::find($id);

        if (!$transaksi) {
            return response()->json(['message' => 'Transaction not found'], 404);
        }

        $jarak = $request->input('jarak'); 
        $biaya_ongkir = 0;

        if ($jarak <= 5) {
            $biaya_ongkir = 10000;
        } elseif ($jarak <= 10) {
            $biaya_ongkir = 15000;
        } elseif ($jarak <= 15) {
            $biaya_ongkir = 20000;
        } else {
            $biaya_ongkir = 25000;
        }

       
        $transaksi->jarak_delivery = $jarak;
        $transaksi->biaya_ongkir = $biaya_ongkir;
        $transaksi->total_harga = $total_harga + $biaya_ongkir;
        $transaksi->status_transaksi = "menunggu pembayaran";

        $transaksi->save(); 

        return redirect()->route('show_pengiriman')->with('success', 'Transaksi berhasil dikonfirmasi dan akan menunggu pembayaran');
    }

    public function show_konfirmasi_pesanan(){
        $transaksis = Transaksi::with('user')
        ->where('status_transaksi', 'menunggu konfirmasi')
        ->get();

        return view('admin.konfirmasiPesanan', compact('transaksis'));
    }

    public function detail_konfirmasi_pesanan($id)
    {
        $transaksi = Transaksi::with('user')->findOrFail($id);
        return view('admin.detailKonfirmasiPesanan', compact('transaksi'));
    }

    public function show_pesanan_diproses(){
         $transaksis = Transaksi::where('status_transaksi', 'sedang dikemas')->get();
    return view('admin.showPesananDiproses', compact('transaksis'));
    }

    public function show_pesanan_telat_bayar(){
    
    $now = Carbon::now();
    
    $transaksis = Transaksi::where('status_transaksi', 'menunggu pembayaran')
                            ->where('created_at', '<', $now->subDay())
                            ->get();

    return view('admin.showPesananTelatPembayaran', compact('transaksis'));
}

    public function batalkan_pesanan_telat_bayar($id) {
   
    $transaksi = Transaksi::findOrFail($id);

    
    $detailTransaksis = $transaksi->detailTransaksis;

    
    foreach ($detailTransaksis as $detail) {
        if ($detail->produk && !$detail->is_hampers) {
           
            $produk = $detail->produk;
            $produk->stok += $detail->jumlah_produk;
            $produk->save();
        } elseif ($detail->hampers) {
          
            $hampers = $detail->hampers;
            $hampers->stok += $detail->jumlah_produk;
            $hampers->save();
        }
    }

   
    $transaksi->status_transaksi = 'Dibatalkan';
    $transaksi->save();

    return redirect()->route('show_pesanan_telat_bayar')->with('success', 'Berhasil Membatalkan Transaksi Tersebut');
}

public function pesanan_siap_dikirim_dipickup($id)
{
    // Find the transaction and ensure it exists
    $transaksi = Transaksi::findOrFail($id);

    // Retrieve all detail transactions related to the transaction
    $detailTransaksis = $transaksi->detailTransaksis;

    foreach ($detailTransaksis as $detailTransaksi) {
        // Check if the detail transaction is a hamper
        if ($detailTransaksi->is_hampers) {
            // Get the hampers details associated with this hamper
            $hampersDetails = HampersDetail::where('hampers_id', $detailTransaksi->produk_id)->get();

            foreach ($hampersDetails as $hampersDetail) {
                $dukpro = $hampersDetail->dukpro;

                // Only proceed if the dukpro status is 'Preorder'
                if ($dukpro->status === 'Preorder') {
                    // Get all recipes related to the product in the hamper
                    $reseps = $dukpro->bahanBakus;

                    foreach ($reseps as $resep) {
                        // Find the related BahanBaku
                        $bahanBaku = BahanBaku::find($resep->pivot->bahan_baku_id);

                        // Update the total_digunakan attribute and record usage
                        if ($bahanBaku) {
                            $jumlahDigunakan = $resep->pivot->jumlah * $detailTransaksi->jumlah_produk;
                            $bahanBaku->total_digunakan += $jumlahDigunakan;
                            $bahanBaku->save();

                            // Record the usage in the BahanBakuUsage table
                            BahanBakuUsage::create([
                                'bahan_baku_id' => $bahanBaku->id,
                                'transaksi_id' => $transaksi->id,
                                'tanggal_transaksi' => $transaksi->tanggal_transaksi,
                                'jumlah_digunakan' => $jumlahDigunakan,
                            ]);
                        }
                    }
                }
            }
        } else {
            // Get the product related to the detail transaction
            $produk = $detailTransaksi->produk;

            // Only proceed if the product status is 'Preorder'
            if ($produk->status === 'Preorder') {
                // Get all recipes related to the product
                $reseps = $produk->bahanBakus;

                foreach ($reseps as $resep) {
                    // Find the related BahanBaku
                    $bahanBaku = BahanBaku::find($resep->pivot->bahan_baku_id);

                    // Update the total_digunakan attribute and record usage
                    if ($bahanBaku) {
                        $jumlahDigunakan = $resep->pivot->jumlah * $detailTransaksi->jumlah_produk;
                        $bahanBaku->total_digunakan += $jumlahDigunakan;
                        $bahanBaku->save();

                        // Record the usage in the BahanBakuUsage table
                        BahanBakuUsage::create([
                            'bahan_baku_id' => $bahanBaku->id,
                            'transaksi_id' => $transaksi->id,
                            'tanggal_transaksi' => $transaksi->tanggal_transaksi,
                            'jumlah_digunakan' => $jumlahDigunakan,
                        ]);
                    }
                }
            }
        }
    }

    // Update the transaction status
    $transaksi->status_transaksi = 'sudah dikonfirmasi';
    $transaksi->save();

    // Redirect to the 'show_pesanan_diproses' route
    return redirect()->route('show_pesanan_diproses');
}

    
}