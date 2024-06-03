<?php

namespace App\Http\Controllers;

use App\Models\Resep;
use App\Models\Dukpro;
use App\Models\Produk;
use App\Models\BahanBaku;
use Illuminate\Http\Request;
use App\Models\BahanBakuUsage;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Redirect;

class ResepController extends Controller
{
    public function create_resep()
    {
         $produks = Dukpro::all();
         $bahanbakus = BahanBaku::all();
       
         return view('admin.tambahResep', compact('produks', 'bahanbakus'));
    }

    public function tambahResep(Request $request)
    {
        $produkId = $request->product_id;
        $bahanBakuIds = $request->bahan_baku_id;
        $jumlahs = $request->jumlah;

        if (count($bahanBakuIds) !== count($jumlahs)) {
            return redirect()->back()->withErrors(['jumlah' => 'Jumlah bahan baku tidak sesuai dengan jumlah bahan yang dipilih.']);
        }

        // Fetch the product details to check its status and stock
        $product = Dukpro::find($produkId);

        if (!$product) {
            return redirect()->back()->withErrors(['product' => 'Produk tidak ditemukan.']);
        }

        foreach ($bahanBakuIds as $key => $bahanBakuId) {
            Resep::create([
                'produk_id' => $produkId,
                'bahan_baku_id' => $bahanBakuId,
                'jumlah' => $jumlahs[$key],
            ]);

            // If the product status is not "Preorder", update BahanBakuUsage
            if ($product->status !== 'Preorder') {
                $jumlahDigunakan = $jumlahs[$key] * $product->stok;

                // Create a new BahanBakuUsage entry
                BahanBakuUsage::create([
                    'bahan_baku_id' => $bahanBakuId,
                    'transaksi_id' => null, 
                    'tanggal_transaksi' => now(),
                    'jumlah_digunakan' => $jumlahDigunakan,
                ]);

                // Update the total_digunakan field in BahanBaku
                $bahanBaku = BahanBaku::find($bahanBakuId);
                if ($bahanBaku) {
                    $bahanBaku->total_digunakan += $jumlahDigunakan;
                    $bahanBaku->save();
                }
            }
        }

        return redirect()->route('index_resep')->with('success', 'Berhasil Menambah Resep');
    }



     public function index_resep(){
        $reseps = Resep::all(); //buatkan where 
        $produks = Dukpro::all();
        $bahanbakus = BahanBaku::all();
        // dd($reseps, $produks, $bahanbakus);
        return view('admin.resep', compact('reseps', 'produks', 'bahanbakus'));
    }

    public function index_detailResep($produk){
        $reseps = Resep::all(); 
        $bahanbakus = BahanBaku::all();
        return view('admin.detailResep', compact('reseps', 'produk', 'bahanbakus'));
    }


    //dilakukan perubahan
    public function updateResep($produk){
        $reseps = Resep::all(); 
        $allproduks = Dukpro::all(); 
        $bahanbakus = BahanBaku::all();
        return view('admin.updateResep', compact('reseps', 'allproduks' , 'produk', 'bahanbakus'));
     }

    public function update_resep(Request $request, $produk)
{
    // Fetch the existing resep entries for the given product
    $existingReseps = Resep::where('produk_id', $produk)->get();
    $existingBahanBakuUsage = [];

    // Store existing usage for comparison
    foreach ($existingReseps as $resep) {
        $existingBahanBakuUsage[$resep->bahan_baku_id] = $resep->jumlah;
    }

    // Hapus semua entri resep yang memiliki produk_id sesuai dengan yang dikirim dari view
    Resep::where('produk_id', $produk)->delete();

    // Proses untuk membuat kembali entri resep berdasarkan data dari $request
    $produkId = $request->product_id;
    $bahanBakuIds = $request->bahan_baku_id;
    $jumlahs = $request->jumlah;

    if (count($bahanBakuIds) !== count($jumlahs)) {
        return redirect()->back()->withErrors(['jumlah' => 'Jumlah bahan baku tidak sesuai dengan jumlah bahan yang dipilih.']);
    }

    $product = Dukpro::find($produkId);

    if (!$product) {
        return redirect()->back()->withErrors(['product' => 'Produk tidak ditemukan.']);
    }

    foreach ($bahanBakuIds as $key => $bahanBakuId) {
        Resep::create([
            'produk_id' => $produkId,
            'bahan_baku_id' => $bahanBakuId,
            'jumlah' => $jumlahs[$key],
        ]);

        if ($product->status === 'Available') {
            $newJumlahDigunakan = $jumlahs[$key] * $product->stok;

            // Calculate the difference in usage
            $oldJumlahDigunakan = ($existingBahanBakuUsage[$bahanBakuId] ?? 0) * $product->stok;
            $diff = $newJumlahDigunakan - $oldJumlahDigunakan;

            if ($diff != 0) {
                BahanBakuUsage::create([
                    'bahan_baku_id' => $bahanBakuId,
                    'transaksi_id' => null,
                    'tanggal_transaksi' => now(),
                    'jumlah_digunakan' => $diff,
                ]);

                $bahanBaku = BahanBaku::find($bahanBakuId);
                if ($bahanBaku) {
                    $bahanBaku->total_digunakan += $diff;
                    $bahanBaku->save();
                }
            }

            // Remove from existing usage tracking array to identify removed items
            unset($existingBahanBakuUsage[$bahanBakuId]);
        }
    }

    // Handle removed items
    foreach ($existingBahanBakuUsage as $bahanBakuId => $jumlah) {
        $jumlahDigunakan = $jumlah * $product->stok;

        BahanBakuUsage::create([
            'bahan_baku_id' => $bahanBakuId,
            'transaksi_id' => null,
            'tanggal_transaksi' => now(),
            'jumlah_digunakan' => -$jumlahDigunakan,
        ]);

        $bahanBaku = BahanBaku::find($bahanBakuId);
        if ($bahanBaku) {
            $bahanBaku->total_digunakan -= $jumlahDigunakan;
            $bahanBaku->save();
        }
    }

    return redirect()->route('index_resep')->with('success', 'Resep berhasil diperbarui.');
}


    public function delete_resep($produk){

        Resep::where('produk_id', $produk)->delete();
        return redirect()->route('index_resep')->with('success', 'Resep berhasil dihapus.');

    }

    public function search_resep(Request $request)
{
    // Validasi input
    $request->validate([
        'search' => 'required',
    ]);
    $search = $request->input('search');
    $reseps = Resep::whereHas('product', function($query) use ($search) {
        $query->where('nama', 'like', '%' . $search . '%');
    })->get();

    $bahanbakus = BahanBaku::all();
    return view('admin.resep', compact('reseps', 'bahanbakus'));
    
}


}
