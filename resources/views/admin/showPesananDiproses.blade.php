@extends('NavbarAdmin')

@section('content')
    <!-- Include Bootstrap CSS and JS -->
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

    <div class="container">
        <h2 class="text-center text-white mb-4">Daftar Pesanan yang Sedang Diproses</h2>

        @foreach ($transaksis as $transaksi)
            <div class="order-card" style="background-color: rgba(0, 0, 0, 0.5)">

                <div class="store-info">
                    <span class="store-name">No Pesanan : {{ $transaksi->no_transaksi }}</span>

                    {{-- Link ke detail toko --}}
                </div>
                <hr>
                @foreach ($transaksi->detailTransaksis as $detail)
                    <div class="order-details">
                        <img src="{{ asset('../storage/dukpro/' . $detail->produk->image) }}"
                            alt="{{ $detail->produk->nama }}" class="img-fluid ml-5" width="150px;">
                        <div class="product-info">
                            <h1 class="product-name">{{ $detail->produk->nama }}</h1>
                            {{-- Jumlah produk dalam transaksi --}}
                            <p class="order-quantity">x{{ $detail->jumlah_produk }}</p>
                            {{-- Status transaksi --}}
                            <span class="status">Pesanan sedang dikemas</span>
                        </div>
                        <div class="order-pricing">
                            {{-- Harga produk --}}
                            <h5 class="discounted-price">Rp{{ $detail->produk->harga }}</h5>
                            {{-- Checkbox --}}
                            <div class="form-check mt-2" style="padding-top: 40px;">
                                <input class="form-check-input" type="checkbox" value="" id="flexCheckDefault">
                                <label class="form-check-label" for="flexCheckDefault">
                                    Centang jika sudah dikemas
                                </label>
                            </div>
                        </div>
                    </div>
                @endforeach

                <div class="order-actions">
                    {{-- Total harga transaksi --}}
                    @if ($transaksi->status_pengantaran == 'delivery')
                        <p>Pengantaran : {{ $transaksi->alamat_pengantaran }}</p>
                        <p>Delivery : {{ $transaksi->jenis_delivery }}</p>
                        <p>Jarak : {{ $transaksi->jarak_delivery }} KM</p>
                    @else
                        <p>Diambil Sendiri</p>
                    @endif

                    <hr>
                    <p class="total-price">Biaya Ongkir: Rp. {{ $transaksi->biaya_ongkir }},00</p>
                    <p class="total-price">Total Harga: Rp. {{ $transaksi->total_harga + $transaksi->biaya_ongkir }},00</p>
                    {{-- Centered button --}}
                    <div class="button-container">
                        <button type="button" class="btn btn-primary ready-to-ship-btn"
                            data-id="{{ $transaksi->id }}">Siap
                            Dikirim/Dipickup</button>
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    <!-- Modal -->
    <div class="modal fade" id="confirmModal" tabindex="-1" role="dialog" aria-labelledby="confirmModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 style="color: black" class="modal-title" id="confirmModalLabel">Konfirmasi</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div style="color: black" class="modal-body">
                    Apakah Anda yakin pesanan ini siap dikirim/dipickup?
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary" id="confirmYes">Yes</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Form for PATCH request -->
    <form id="readyToShipForm" method="POST" style="display: none;">
        @csrf
        @method('PATCH')
    </form>

    <style>
        body {
            background: url('../assets/images/bgcake2.jpg') no-repeat center center fixed;
            background-size: cover;
            color: white;
        }

        .container {
            width: 100%;
            max-width: 1200px;
            margin: 0 auto;
            padding: 20px;
        }

        .order-card {
            border: 1px solid #000000;
            padding: 20px;
            margin-bottom: 20px;
            background-color: #ff9d2e;
            border-radius: 10px;
        }

        .store-info {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .store-name {
            font-weight: bold;
            /* font-size: 16px; */
        }

        .order-details {
            display: flex;
            margin-top: 20px;
        }

        .product-image {
            width: 100px;
            height: 100px;
            object-fit: cover;
            margin-right: 20px;
        }

        .product-info {
            flex: 1;
        }

        .product-name {
            /* font-size: 16px; */
            font-weight: bold;
        }

        .order-quantity {
            font-size: 20px;
            color: #ffffff;
        }

        .order-status {
            display: flex;
            flex-direction: column;
            align-items: flex-end;
            margin-left: 20px;
        }

        .status {
            font-size: 14px;
            color: green;
        }

        .order-pricing {
            text-align: right;
        }

        .discounted-price {
            font-weight: bold;
            color: #d9534f;
        }

        .total-price {
            font-size: 16px;
            font-weight: bold;
        }

        .order-actions {
            display: flex;
            flex-direction: column;
            align-items: flex-end;
            margin-top: 20px;
        }

        .order-actions p {
            margin-right: 10px;
        }

        .button-container {
            display: flex;
            justify-content: center;
            width: 100%;
            margin-top: 10px;
        }

        .btn-primary {
            background-color: #ff9d2e;
            border-color: #000;
        }
    </style>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            let selectedOrderId;

            document.querySelectorAll('.ready-to-ship-btn').forEach(button => {
                button.addEventListener('click', function() {
                    selectedOrderId = this.getAttribute('data-id');
                    $('#confirmModal').modal('show');
                });
            });

            document.getElementById('confirmYes').addEventListener('click', function() {
                if (selectedOrderId) {
                    const form = document.getElementById('readyToShipForm');
                    form.action = `/pesanan_siap_dikirim_dipickup/${selectedOrderId}`;
                    form.submit();
                }
            });

            // Make sure the modal can be closed properly
            $('#confirmModal').on('hidden.bs.modal', function() {
                selectedOrderId = null;
            });
        });
    </script>
@endsection
