@extends('NavbarMO')
@section('content')
<body>
    <main>
    <div class="col-12 pl-5 pr-5 mb-5 mt-4">
        {{-- handle valide $request --}}
        @if($errors->any())
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            @foreach ($errors->all() as $error)
            {{ $error }}
            @endforeach
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
        @endif
        {{-- with success --}}
        @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
        @endif
        {{-- with error --}}
        @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            @foreach (session('error') as $error)
            {{ $error }}
            @endforeach
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
        @endif
        {{-- card riwayat saldo --}}
        <div class="card shadow">
            <div class="card-header">
                <div class="d-flex justify-content-between">
                    <h3>Laporan Rekap Transaksi Penitipan</h3>
                </div>
            </div>
            <div class="card-body table-responsive">
                {{-- total pemasukan --}}
                <p class="text-right mb-1">Total Pemasukan : Rp. {{ number_format($total_pemasukan) }}</p>
                {{-- total pengeluaran --}}
                <p class="text-right mb-1">Total Pengeluaran : Rp. {{ number_format($total_pengeluaran) }}</p>
                {{-- total profit --}}
                <p class="text-right h4 fw-bold">Total Profit : Rp. {{ number_format($total_profit) }}</p>
                {{-- table --}}
                <table class="table table-bordered table-striped" id="tx">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Nama</th>
                            <th>Alamat</th>
                            <th>No KTP</th>
                            <th>No Telp</th>
                            <th>Mulai Kontrak</th>
                            <th>Akhir Kontrak</th>
                            <th>Status</th>
                            <th>Profit</th>
                        </tr>
                    </thead>
                    <tbody>

                        @foreach($datas as $data)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $data->nama }}</td>
                            <td>{{ $data->alamat }}</td>
                            <td>{{ $data->no_ktp }}</td>
                            <td>{{ $data->no_telp }}</td>
                            <td>{{ $data->mulai_kontrak }}</td>
                            <td>{{ $data->akhir_kontrak }}</td>
                            <td>{{ $data->status }}</td>
                            {{-- profit 20% dari laba dibagi jumlah data --}}
                            <?php
                            $profit = $total_profit * 0.2 / $datas->count();
                            ?>
                            <td>{{ number_format($profit,0,',','.') }}</td>

                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    </main>
    <script>
        $(document).ready(function() {
            $('#tx').DataTable([{
                @if($datas->count() == 0)
                //gabungkan semua column dan berikan pesan
                "language": {
                    "zeroRecords": "Tidak ada data"
                }
                @endif
            }]);

        });
    </script>
</body>
@endsection

