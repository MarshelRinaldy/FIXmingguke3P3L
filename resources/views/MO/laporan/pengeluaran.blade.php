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
                <h3>Laporan Pengeluaran</h3>
                {{-- bulan --}}
                <h>{{Date('F Y')}}</h>
                {{-- tahun --}}
            </div>
            <div class="card-body">
                <table class="table table-bordered table-striped" id="tx">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Tanggal</th>
                            <th>Nama Pengeluaran</th>
                            <th>Jumlah</th>
                            <th>Kategori</th>
                        </tr>
                    </thead>
                    <tbody>

                        @foreach($datas as $data)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $data->tanggal_pengeluaran }}</td>
                            <td>{{ $data->nama_pengeluaran }}</td>
                            <td>{{ $data->harga_pengeluaran }}</td>
                            <td>{{ $data->kategori_pengeluaran }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                    <tfoot>
                        <tr>
                            <th colspan="3" style="text-align:right">Total:</th>
                            <th id="total-amount">Rp. {{ number_format($datas->sum('harga_pengeluaran'), 0, ',', '.') }}</th>
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
            $('#tx').DataTable([{
                @if($datas->count() == 0)
                //gabungkan semua column dan berikan pesan
                "language": {
                    "zeroRecords": "Tidak ada data"
                }
                @else
                // buat kolom total di paling bawah
                "footerCallback": function (row, data, start, end, display) {
                    var api = this.api();

                    // Menghitung total semua kolom 'Jumlah'
                    var total = api
                        .column(3)
                        .data()
                        .reduce(function (a, b) {
                            return a + parseFloat(b.replace(/[\Rp,\.]/g, ''));
                        }, 0);

                    // Update kolom footer dengan total
                    $(api.column(3).footer()).html('Rp. ' + total.toLocaleString('id-ID', {minimumFractionDigits: 0}));
                }
                @endif
            }]);

        });
    </script>
</body>
@endsection

