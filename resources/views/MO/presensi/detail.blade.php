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
                <h3>Data Presensi {{$data->name}}
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
                            <th>Jam Masuk</th>
                            <th>Jam Keluar</th>
                            <th>Status</th>
                            <th>Ketarangan</th>

                        </tr>
                    </thead>
                    <tbody>

                        @foreach($data->user->presensi as $datax)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $datax->tanggal }}</td>
                            <td>{{ $datax->jam_masuk }}</td>
                            <td>{{ $datax->jam_keluar }}</td>
                            <td>{{ $datax->status }}</td>
                            <td>{{ $datax->keterangan }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
                {{-- perhitungan gaji --}}
                <div class="row">
                    <div class="col-12">
                        {{-- keterangan gaji harian, bonus, total hadir --}}
                        <p>Gaji Harian : Rp. {{ number_format($data->gaji,0,',','.') }}</p>
                        <p>Bonus : Rp. {{ number_format($data->bonus,0,',','.') }}</p>
                        <p>Total Hadir : {{ $data->user->presensi_hadir_bulan_ini }}</p>
                        {{-- total gaji --}}
                        <h3>Total Gaji : Rp. @if($data->user->presensi_hadir_bulan_ini == 0)
                            0
                            @else
                            {{ number_format(($data->user->presensi_hadir_bulan_ini * $data->gaji) + $data->bonus,0,',','.') }}
                            @endif
                        </h3>
                    </div>
                </div>
            </div>
        </div>
    </div>
    </main>
    <script>
        $(document).ready(function() {
            $('#tx').DataTable([{
                @if($data->user->get_presensi_bulan_ini== 0)
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

