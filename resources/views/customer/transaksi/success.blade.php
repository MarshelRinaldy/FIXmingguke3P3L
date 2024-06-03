@extends('layouts.app')
{{-- css --}}
@push('css')
@endpush
@section('content')
    <div class="container mt-5">
        <div class="card">
            <div class="card-header">
                Informasi Transaksi
            </div>
            <div class="card-body">
                @if ($transaksi->metode_pembayaran == "cash")
                <div class="alert alert-success" role="alert">
                    Transaksi sukses
                </div>
                @else
                <div class="alert alert-info" role="alert">
                    Silahkan kirim ke rekening berjumlah Rp. {{$transaksi->jumlah_transaksi}} ke nomor <strong>32940u23984823947</strong> atas nama <strong>Umar</strong>.
                </div>
                <form action="{{ route('transaksi.upload_bukti') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="form-group">
                        <label for="buktiTransfer">Upload Bukti Transfer</label>
                        <input type="file" class="form-control-file" id="buktiTransfer" name="buktiTransfer">
                    </div>
                    <input type="hidden" name="id" value="{{$transaksi->id}}">
                    <button type="submit" class="btn btn-primary">Kirim Bukti Transfer</button>
                </form>
                <p class="mt-3">
                    Terima kasih telah melakukan transaksi. Mohon untuk segera mengunggah bukti transfer agar proses verifikasi dapat dilakukan dengan cepat.
                </p>
                @endif


                <a href="{{ route('transaksi.nota', ['id' => $transaksi->id]) }}" class="button">Lihat Nota</a>
            </div>
        </div>
    </div>
@endsection
{{-- js --}}
@push('js')
@endpush
