@extends('layouts.app')
{{-- css --}}
@push('css')
@endpush
@section('content')
    <div class="container mt-5" style="margin-top: 100px !important;">
        <h2 class="text-center">Nota Pemesanan</h2>
        <div class="card shadow">
            <div class="card-body">
                <h5 class="card-title"><strong>Atma Kitchen</strong></h5>
                <p class="card-text">Jl. Centralpark No. 10 Yogyakarta</p>

                <table class="table table-bordered">
                    <tr>
                        <th>No Nota:</th>
                        <td>{{ $transaksi->no_transaksi }}
                        </td>
                    </tr>
                    <tr>
                        <th>Tanggal Pesan:</th>
                        <td>{{ $transaksi->created_at->format('d/m/Y H:i') }}</td>
                    </tr>
                    <tr>
                        <th>Status Transaksi:</th>
                        <td>{{ $transaksi->status_transaksi }}</td>
                    </tr>
                    <tr>
                        <th>Customer:</th>
                        <td>{{ $user->email }} / {{ $user->name }}</td>
                    </tr>
                    <tr>
                        <th>Alamat Pengantaran:</th>
                        <td>{{ $transaksi->alamat_pengantaran }}</td>
                    </tr>
                    <tr>
                        <th>Delivery:</th>
                        <td>{{ $transaksi->jenis_delivery }}</td>
                    </tr>
                </table>

                <h5>Rincian Pesanan</h5>
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Nama Produk</th>
                            <th>Jumlah</th>
                            <th>Harga</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $total_sebelum_potongan = 0; ?>
                        {{-- 'orderItems' => $transaksi->detailTransaksis --}}
                        @foreach ($orderItems as $item)
                            <?php
                        if($item->is_hampers){

                            // $details = $item->hampers->details;
                            // foreach($details as $detail){
                            //     $produk = $detail->dukpro;
                            //     ?>
                            <tr>
                                <td>{{ $item->hampers->nama }}</td>
                                <td>{{ $item->jumlah_produk }}</td>
                                <td>{{ number_format($item->hampers->harga, 0, ',', '.') }}</td>
                            </tr>
                            {{-- <tr>
                                    <td>{{ $produk->nama }}</td>
                                    <td>{{ $item->jumlah_produk }}</td>
                                    <td>{{ number_format($produk->harga, 0, ',', '.') }}</td>
                                </tr> --}}
                            <?php
                            //     $total_sebelum_potongan += $produk->harga * $item->jumlah_produk;
                            // }
                            $total_sebelum_potongan += $item->hampers->harga * $item->jumlah_produk;
                        }else{
                        ?>
                            <tr>
                                <td>{{ $item->produk->nama }}</td>
                                <td>{{ $item->jumlah_produk }}</td>
                                <td>{{ number_format($item->produk->harga, 0, ',', '.') }}</td>
                            </tr>
                            <?php
                        $total_sebelum_potongan += $item->produk->harga * $item->jumlah_produk;
                        }
                        ?>
                        @endforeach
                    </tbody>
                </table>
                <?php
                $point = $order->total_price - $total_sebelum_potongan;
                ?>
                <table class="table table-bordered">
                    <tr>
                        <th>Total:</th>
                        <td>{{ number_format($order->total_price - $point, 0, ',', '.') }}</td>
                    </tr>
                    <tr>
                        <th>Ongkos Kirim:</th>
                        <td>{{ number_format($transaksi->ongkos_kirim, 0, ',', '.') }}</td>
                    </tr>
                    <tr>
                        <th>Potongan:</th>
                        <td>{{ number_format($transaksi->potongan, 0, ',', '.') }}</td>
                    </tr>
                    <tr>
                        <th>Pemakaian Point:</th>
                        <td>{{ number_format($point, 0, ',', '.') }}</td>
                    </tr>
                    <tr>
                        <th>Total Bayar:</th>
                        <td><strong>{{ number_format($transaksi->jumlah_transaksi, 0, ',', '.') }}</strong></td>
                    </tr>
                </table>

                <p>Poin dari pesanan ini: {{ $poin }}<br>
                    Total poin customer: {{ $total_point }}</p>
            </div>
        </div>
    </div>
@endsection
{{-- js --}}
@push('js')
@endpush
