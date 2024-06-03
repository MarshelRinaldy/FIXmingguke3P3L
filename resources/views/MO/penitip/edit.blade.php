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
                    <h3>Ubah Data Penitip </h3>
                    {{-- kembali --}}
                    <a href="{{ route('penitip.index') }}" class="btn btn-primary">Kembali</a>
                </div>
            </div>
            <div class="card-body">
                <form action="{{ route('penitip.update', $penitip->id) }}" method="POST">
                    @method('PUT')
                    @csrf
                    <div class="form-group row">
                        <label for="nama" class="col-sm-2 col-form-label">Nama</label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control" id="nama" name="nama" value="{{ $penitip->nama }}">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="alamat" class="col-sm-2 col-form-label">Alamat</label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control" id="alamat" name="alamat" value="{{ $penitip->alamat }}">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="no_ktp" class="col-sm-2 col-form-label">No KTP</label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control" id="no_ktp" name="no_ktp" value="{{ $penitip->no_ktp }}">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="no_telp" class="col-sm-2 col-form-label">No Telp</label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control" id="no_telp" name="no_telp" value="{{ $penitip->no_telp }}">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="mulai_kontrak" class="col-sm-2 col-form-label">Mulai Kontrak</label>
                        <div class="col-sm-10">
                            <input type="date" class="form-control" id="mulai_kontrak" name="mulai_kontrak" value="{{ $penitip->mulai_kontrak }}">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="akhir_kontrak" class="col-sm-2 col-form-label">Akhir Kontrak</label>
                        <div class="col-sm-10">
                            <input type="date" class="form-control" id="akhir_kontrak" name="akhir_kontrak" value="{{ $penitip->akhir_kontrak }}">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="status" class="col-sm-2 col-form-label">Status</label>
                        <div class="col-sm-10">
                            <select class="form-control" id="status" name="status">
                                <option value="aktif" {{ $penitip->status == 'Aktif' ? 'selected' : '' }}>Aktif</option>
                                <option value="tidak aktif" {{ $penitip->status == 'Tidak Aktif' ? 'selected' : '' }}>Tidak Aktif</option>
                            </select>
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-sm-10">
                            <button type="submit" class="btn btn-primary">Simpan Data</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
    </main>
</body>
@endsection

