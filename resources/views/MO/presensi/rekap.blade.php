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
                <h3>Rekap Gaji Bulanan </h3>
                {{-- bulan --}}
                <h>{{Date('F Y')}}</h>
                {{-- tahun --}}
            </div>
            <div class="card-body">
                <table class="table table-bordered table-striped" id="tx">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>NIP</th>
                            <th>Nama</th>
                            <th>Kehadiran</th>
                            <th>Gaji Harian</th>
                            <th>Bonus</th>
                            <th>Total</th>
                        </tr>
                    </thead>
                    <tbody>

                        @foreach($datas as $data)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $data->nip }}</td>
                            <td>{{ $data->user->name }}</td>
                            <td>{{ $data->user->presensi_hadir_bulan_ini}}</td>
                            <td>{{ number_format($data->gaji,0,',','.') }}</td>
                            <td>{{ number_format($data->bonus,0,',','.') }}</td>
                            <td>@if($data->user->presensi_hadir_bulan_ini == 0)
                                Rp. 0
                                @else
                                {{ number_format(($data->user->presensi_hadir_bulan_ini * $data->gaji) + $data->bonus,0,',','.') }}
                                @endif
                            </td>

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

