@extends('NavbarMO')
@section('content')

    <body>
        <main>
            <div class="col-12 pl-5 pr-5 mb-5 mt-4">
                {{-- handle valide $request --}}
                @if ($errors->any())
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
                @if (session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        {{ session('success') }}
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                @endif
                {{-- with error --}}
                @if (session('error'))
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
                        <h3>Laporan Pengeluaran</h3>
                        {{-- bulan --}}
                        <h>{{ Date('F Y') }}</h>
                        {{-- tahun --}}
                    </div>
                    <div class="card-body">
                        <table class="table table-bordered table-striped" id="tx">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Tanggal</th>
                                    <th>Nama</th>
                                    <th>Jumlah/Stok</th>
                                    <th>Kategori/Satuan</th>
                                    <th>Harga</th>
                                    <th>Total Digunakan</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php $no = 1; @endphp
                                @foreach ($datas as $data)
                                    <tr>
                                        <td>{{ $no++ }}</td>
                                        <td>{{ $data->tanggal_pengeluaran }}</td>
                                        <td>{{ $data->nama_pengeluaran }}</td>
                                        {{-- <td>{{ $data->harga_pengeluaran }}</td> --}}
                                        <td>-</td>
                                        <td>{{ $data->kategori_pengeluaran }}</td>
                                        <td>{{ $data->harga_pengeluaran }}</td>
                                        <td>-</td>
                                    </tr>
                                @endforeach
                                @foreach ($bahanBakus as $bahanBaku)
                                    <tr>
                                        <td>{{ $no++ }}</td>
                                        <td>{{ date('Y-m-d') }}</td>
                                        <td>{{ $bahanBaku->nama_bahan_baku }}</td>
                                        <td>{{ $bahanBaku->stok_bahan_baku }}</td>
                                        <td>{{ $bahanBaku->satuan_bahan_baku }}</td>
                                        <td>{{ number_format($bahanBaku->harga_bahan_baku * $bahanBaku->stok_bahan_baku, 0, ',', '.') }}
                                        </td>
                                        <td>{{ $bahanBaku->total_digunakan }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                            <tfoot>
                                <tr>
                                    <th colspan="5" style="text-align:right">Total Pengeluaran:</th>
                                    <th id="total-pengeluaran">Rp.
                                        {{ number_format($datas->sum('harga_pengeluaran'), 0, ',', '.') }}</th>
                                    <th></th>
                                </tr>
                                <tr>
                                    <th colspan="5" style="text-align:right">Total Harga Bahan Baku:</th>
                                    <th id="total-bahan-baku">Rp.
                                        {{ number_format($bahanBakus->sum(function ($bahanBaku) {return $bahanBaku->harga_bahan_baku * $bahanBaku->stok_bahan_baku;}),0,',','.') }}
                                    </th>
                                    <th></th>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>
        </main>
        <script>
            $(document).ready(function() {
                $('#tx').DataTable({
                    "language": {
                        "zeroRecords": "Tidak ada data"
                    },
                    "footerCallback": function(row, data, start, end, display) {
                        var api = this.api();

                        // Menghitung total semua kolom 'Harga' untuk Pengeluaran Lain
                        var totalPengeluaran = api
                            .column(5)
                            .data()
                            .reduce(function(a, b) {
                                return a + parseFloat(b.replace(/[\Rp,\.]/g, ''));
                            }, 0);

                        // Menghitung total semua kolom 'Harga' untuk Bahan Baku
                        var totalBahanBaku = api
                            .column(5)
                            .data()
                            .reduce(function(a, b) {
                                return a + parseFloat(b.replace(/[\Rp,\.]/g, ''));
                            }, 0);

                        // Update kolom footer dengan total
                        $(api.column(5).footer()).html('Rp. ' + totalPengeluaran.toLocaleString('id-ID', {
                            minimumFractionDigits: 0
                        }));
                        $(api.column(5).footer()).html('Rp. ' + totalBahanBaku.toLocaleString('id-ID', {
                            minimumFractionDigits: 0
                        }));
                    }
                });
            });
        </script>
    </body>
@endsection
