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
            <div class="card shadow" style="margin-top:50px">
                <div class="card-body">
                    <p class="fw-bold mb-1">Atma Kitchen</p>
                    <p class="fs-6">Jl. Centralpark No. 10 Yogyakarta</p>
                    {{-- laporan penjualan bulanan under line --}}
                    <p class="fs-5 fw-bold h4 mt-2 mb-1"><span style="border-bottom: 2px solid black;">Laporan Stok Bahan Baku</span></p>
                    {{-- tanggal cetak --}}
                    <p class="fs-6">Tanggal Cetak : {{Date('d F Y')}}</p>
                    {{-- table isinya produk, kuantitas, harga, jumlah uang --}}
                    <table class="table table-bordered table-hover table-striped">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Nama Bahan</th>
                                <th>Satuan</th>
                                <th>Stok</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if ($bahanBakus->isEmpty())
                                <tr>
                                    <td colspan="5" class="text-center">Tidak ada data</td>
                                </tr>
                            @endif
                            @foreach ($bahanBakus as $bb)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $bb->nama_bahan_baku }}</td>
                                    <td>{{ $bb->satuan_bahan_baku }}</td>
                                    <td>{{ number_format($bb->stok_bahan_baku, 0, ',', '.') }}</td>

                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </main>
    </body>
@endsection
