@extends('NavbarMO')
@section('content')
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,100..900;1,100..900&display=swap');

        body {
            font-family: 'Montserrat', sans-serif;
            background-color: #EEEEEE;
        }

        table {
            width: 90%;
            margin: 11px auto;
        }

        th,
        td {
            text-align: center;
            vertical-align: middle;
        }

        tr:nth-child(even) {
            background-color: #FEECE2;
        }

        tr:nth-child(odd) {
            background-color: #F7DED0;
        }

        .profile {
            display: flex;
            align-items: center;
            justify-content: flex-end;
            margin-right: 10%;
            position: relative;
        }

        .dropdown {
            display: none;
            position: absolute;
            top: 60px;
            right: 0;
            background-color: #fff;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.2);
            z-index: 1;
        }

        .dropdown button {
            display: block;
            width: 100%;
            text-align: left;
            padding: 10px;
            border: none;
            background: none;
            cursor: pointer;
        }

        .dropdown button:hover {
            background-color: #f9f9f9;
        }

        .btn-search {
            background-color: #FFBE98;
            padding: 5px 15px;
            border-radius: 10px;
        }

        .btn-search:hover {
            background-color: #000000;
            padding: 5px 15px;
            border-radius: 10px;
            color: white;
        }

        .container-total {
            height: 80px;
            width: 100%;
            background-color: white;
            border-radius: 10px;
            padding: 10px 10px;
            box-shadow: 5px 5px 5px rgba(0, 0, 0, 0.5);

        }

        .container-total h1 {
            font-weight: 600;
            font-size: 50px
        }

        .center-content {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            height: 100%;
            margin-right: 20px;
        }

        .container-all {
            display: flex;
        }

        .icon-table {
            display: flex;
            justify-content: center;
            margin-bottom: 40px;
            margin-top: 10px;
        }
    </style>

    <body>
        <main class="p-4">
            <div class="row" style="margin-left: 80px; margin-top: 80px">
                <div class="col-6 title">
                    <h1 style="font-weight: 800">Konfirmasi Pembayaran</h1>
                    <p style="font-size: 25px; font-weight: 200;">Hi MO, Welcome in Dashboard!</p>
                </div>
                <div class="col-6">
                    <div class="profile">
                        <img src="image/pictureProfile.png" alt="" width="80px">
                        <p style="padding-top: 10px">MO</p>
                        <div class="dropdown" id="dropdownMenu">
                            <button onclick="toggleDropdown()">Profile</button>
                            <button onclick="logout()">Logout</button>
                        </div>
                        <img id="arrowIcon" src="image/iconArrowBottom.png" alt="" width="20px"
                            style=" margin-left: 5px; cursor: pointer" onclick="toggleDropdown()">
                    </div>
                </div>
            </div>

            <div class="row" style="margin-left: 80px; margin-top: 40px; margin-bottom: 30px">
                <div class="col-8 container-all">
                    <div class="container-total center-content">
                        <h5>Total Confirm</h5>
                        <h1 style="color:#50CD4E">254</h1>
                    </div>

                    <div class="container-total center-content">
                        <h5>Total Confirm</h5>
                        <h1 style="color: #1137FF">54</h1>
                    </div>

                    <div class="container-total center-content">
                        <h5>Total Confirm</h5>
                        <h1 style="color: #FF0000">54</h1>
                    </div>
                </div>
                <div class="col-4" style="padding-top: 90px">
                    <div style=" display: flex; margin-right: 10%;">
                        <a href="" style=" margin-right: 10px; color: #000000; font-weight: 500;">Search</a>
                        <input style="border-radius: 22px; padding-left: 10px; width: 100%; margin-right: 10%"
                            type="text">
                    </div>
                </div>
            </div>
            {{-- untuk menampilkan with --}}
            @if (session('success'))
                <div class="alert alert-success">
                    {{ session('success') }}
                </div>
            @endif
            {{-- with error (array) --}}
            @if (session('error'))
                <div class="alert alert-danger">
                    @foreach (session('error') as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </div>
            @endif
            <div class="card shadow">
                <div class="card-header">
                    <h2>Daftar Pesanan</h2>
                </div>
                <div class="card-body table-responsive">
                    <table class="table table-bordered" style="width: 90%; margin: 11px auto; border-collapse: collapse;" id="table">
                        <thead>
                            <tr style="background-color: #E2BFB3; height: 80px;">
                                <th>NO</th>
                                <th>PEMBELI</th>
                                <th>NO TRANSAKSI</th>
                                <th>TOTAL HARGA</th>
                                <th>STATUS</th>
                                <th>BAHAN BAKU</th>
                                <th>ACTION</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($transaksis as $transaksi)
                            <tr style="height: 60px;">
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $transaksi->user->name }}</td>
                                <td>{{ $transaksi->no_transaksi }}</td>
                                <td>{{ $transaksi->total_harga }}</td>
                                <td style="color: #f91313">{{ $transaksi->status_transaksi }}</td>
                                <td>
                                    {{-- Tombol menampilkan modal pemakaian bahan baku --}}
                                    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modal{{ $transaksi->id }}">
                                        Lihat Bahan Baku
                                    </button>
                                    {{-- Modal pemakaian bahan baku --}}
                                    <div class="modal fade" id="modal{{ $transaksi->id }}" tabindex="-1" aria-labelledby="modalLabel{{ $transaksi->id }}" aria-hidden="true">
                                        <div class="modal-dialog modal-lg">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title" id="modalLabel{{ $transaksi->id }}">Pemakaian Bahan Baku</h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                </div>
                                                <div class="modal-body table-responsive">

                                                    <?php
                                                    $stok_bahan =[];
                                                    ?>
                                                    @foreach ($transaksi->detailTransaksis as $detail)
                                                    @if($detail->is_hampers)
                                                    <p style="font-weight: 600" class="h4">Bahan baku untuk hampers: {{ $detail->hampers->nama }}</p>
                                                    @foreach ($detail->hampers->details as $det)
                                                    <p style="font-weight: 400">Nama produk: {{ $det->dukpro->nama }}</p>
                                                    <table class="table table-bordered" style="width: 90%; margin: 11px auto; border-collapse: collapse;">
                                                        <thead>
                                                            <tr style="background-color: #E2BFB3; height: 80px;">
                                                                <th>NO</th>
                                                                <th>BAHAN BAKU</th>
                                                                <th>DIBUTUHKAN</th>
                                                                <th>STOK</th>
                                                                <th>STOK AKHIR</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            <?php $no = 1; ?>
                                                            @foreach ($det->dukpro->bahanBakus as $bb)
                                                            <tr style="height: 60px;">
                                                                <td>{{ $no }}</td>
                                                                <td>{{ $bb->nama_bahan_baku }}</td>
                                                                <td>{{ $detail->jumlah_produk * $bb->pivot->jumlah}}</td>
                                                                <td>{{ $stok_bahan[$bb->id] ?? $bb->stok_bahan_baku }}</td>
                                                                <?php
                                                                // insert bahan sesuai id bahan baku
                                                                if(!array_key_exists($bb->id, $stok_bahan)){
                                                                    $stok_bahan[$bb->id] = $bb->stok_bahan_baku - ($detail->jumlah_produk * $bb->pivot->jumlah);
                                                                }else{
                                                                    $stok_bahan[$bb->id] -= $detail->jumlah_produk * $bb->pivot->jumlah;
                                                                }
                                                                ?>
                                                                <td>{{ $stok_bahan[$bb->id] }}</td>
                                                            </tr>
                                                            <?php
                                                            $no++;

                                                            ?>
                                                            @endforeach
                                                        </tbody>
                                                    </table>
                                                    @endforeach
                                                    @else
                                                    <p style="font-weight: 600" class="h4">Bahan baku untuk produk: {{ $detail->produk->nama }}</p>
                                                    <table class="table table-bordered" style="width: 90%; margin: 11px auto; border-collapse: collapse;">
                                                        <thead>
                                                            <tr style="background-color: #E2BFB3; height: 80px;">
                                                                <th>NO</th>
                                                                <th>BAHAN BAKU</th>
                                                                <th>DIBUTUHKAN</th>
                                                                <th>STOK</th>
                                                                <th>STOK AKHIR</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            <?php $no = 1; ?>
                                                            @foreach ($detail->produk->bahanBakus as $bb)
                                                            <tr style="height: 60px;">
                                                                <td>{{ $no }}</td>
                                                                <td>{{ $bb->nama_bahan_baku }}</td>
                                                                <td>{{ $detail->jumlah_produk * $bb->pivot->jumlah}}</td>
                                                                <td>{{ $stok_bahan[$bb->id] ?? $bb->stok_bahan_baku }}</td>
                                                                <?php
                                                                // insert bahan sesuai id bahan baku
                                                                if(!array_key_exists($bb->id, $stok_bahan)){
                                                                    $stok_bahan[$bb->id] = $bb->stok_bahan_baku - ($detail->jumlah_produk * $bb->pivot->jumlah);
                                                                }else{
                                                                    $stok_bahan[$bb->id] -= $detail->jumlah_produk * $bb->pivot->jumlah;
                                                                }
                                                                ?>
                                                                <td>{{ $stok_bahan[$bb->id] }}</td>
                                                            </tr>
                                                            <?php
                                                            $no++;

                                                            ?>
                                                            @endforeach
                                                        </tbody>
                                                    </table>
                                                    @endif
                                                    @endforeach
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    {{-- Input untuk cek --}}
                                    <input type="checkbox" name="cek{{ $transaksi->id }}" id="cek{{ $transaksi->id }}" item-id="{{ $transaksi->id }}">
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                    {{-- form dummy --}}
                    <form action="{{ route('mo.pemrosesanpesanan.accept') }}" method="post" id="form">
                        @csrf
                        @method('put')
                        <div id="list_trans_id">
                            {{-- <input type="hidden" name="trans_id[]"> --}}
                        </div>
                        {{-- tombol konfirmasi pesanan --}}
                        <button type="button" id="konfirm-btn" class="btn btn-primary" style="margin-left: 10px">Konfirmasi Pesanan</button>
                        {{-- tombol pilih semua --}}
                        <button type="button" class="btn btn-primary" style="margin-left: 10px" id="pilihSemua">Pilih Semua</button>
                    </form>
                </div>
            </div>


            <div class="icon-table">
                <a href="" style="margin-right: 80%"><img src="image/icon_left_double.png" width="23px"
                        alt=""></a>
                <a href=""><img src="image/icon_right_double.png" width="23px" alt=""></a>
            </div>
        </main>

        <script>
            function toggleDropdown() {
                var dropdown = document.getElementById('dropdownMenu');
                dropdown.style.display = dropdown.style.display === 'none' ? 'block' : 'none';

                var arrowIcon = document.getElementById('arrowIcon');
                arrowIcon.classList.toggle('rotate');
            }

            function logout() {
                alert('Logout clicked');
            }
        </script>
        <script>
            $(document).ready(function() {
                // data table
                $('#table').DataTable();
                // check uncheck
                $('input[type="checkbox"]').click(function() {
                    var id = $(this).attr('item-id');
                    var list_trans_id = $('#list_trans_id');
                    if ($(this).is(':checked')) {
                        // tambahkan input hidden
                        list_trans_id.append('<input type="hidden" name="trans_id[]" value="' + id + ' id="trans_id_' + id + '">');
                    }else {
                        // hapus input hidden
                        $('#trans_id_' + id).remove();
                    }
                });
                // pilih semua
                $('#pilihSemua').click(function() {
                    var cek = $('input[type="checkbox"]');
                    var list_trans_id = $('#list_trans_id');
                    cek.prop('checked', !cek.prop('checked'));
                    cek.each(function() {
                        var id = $(this).attr('item-id');
                        if ($(this).is(':checked')) {
                            // tambahkan input hidden
                            list_trans_id.append('<input type="hidden" name="trans_id[]" value="' + id + ' id="trans_id_' + id + '">');

                        } else {
                            // hapus input hidden
                            $('#trans_id_' + id).remove();
                        }
                    });
                });
                // tombol konfirmasi pesanan
                $('#konfirm-btn').click(function() {
                    //cek apakah ada input hidden
                    var form = $('#form');
                    if ($('#list_trans_id').children().length > 0) {
                        form.submit();
                    } else {
                        alert('Pilih pesanan terlebih dahulu');
                    }
                });

            });


        </script>
    </body>
@endsection
