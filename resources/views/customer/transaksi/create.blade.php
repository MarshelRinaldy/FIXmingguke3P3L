<?php
use Carbon\Carbon;
?>
@extends('layouts.app')
{{-- css --}}
@push('css')
@endpush
@section('content')
<main>
    <article>
        <div class="container mt-5" style="margin-top: 100px !important;">
            <div class="card shadow mt-5">
                <div class="card-header">
                    <h2>Form Transaksi</h2>
                </div>
                <div class="card-body">
                    <form action="{{ route('customer.transaksi.store') }}" method="POST">
                        @csrf
                        <div class="form-group">
                            <label for="metode_pembayaran">Metode Pembayaran</label>
                           <select name="metode_pembayaran" class="form-control" id="">
                            <option value="cash">Cash</option>
                            <option value="bca">BCA</option>
                           </select>
                        </div>
                        {{-- <div class="form-group">
                            <label for="tanggal_transaksi">Tanggal Transaksi</label> --}}
                            <input type="hidden" class="form-control" id="tanggal_transaksi" value="{{Carbon::now()}}" name="tanggal_transaksi" required>
                        {{-- </div> --}}

                        <div class="form-group">
                            <label for="status_pengantaran">Status Pengantaran</label>
                            <select class="form-control" id="status_pengantaran" name="status_pengantaran" required>
                                <option value="delivery">Delivery</option>
                                <option value="ambil_sendiri">Ambil Sendiri</option>
                            </select>
                        </div>

                        <div class="form-group" id="jenis_delivery_group">
                            <label for="jenis_delivery">Jenis Delivery</label>
                            <select class="form-control" id="jenis_delivery" name="jenis_delivery" required>
                                <option value="kurir a">Kurir A</option>
                                <option value="kurir b">Kurir A</option>
                            </select>
                        </div>
                        <div class="form-group" id="alamat_pengantaran_group">
                            <label for="alamat_pengantaran">Alamat Pengantaran</label>
                            <input type="text" class="form-control" id="alamat_pengantaran" name="alamat_pengantaran" required>
                        </div>
                        <button type="submit" class="btn btn-primary col-12 mt-3">Submit</button>
                    </form>
                </div>
            </div>

        </div>
    </article>
</main>

@endsection
{{-- js --}}
@push('js')
<script>
    $(document).ready(function(){
        $('#status_pengantaran').change(function(){
            if($(this).val() == 'ambil_sendiri'){
                $('#jenis_delivery_group').hide();
                $('#jarak_delivery_group').hide();
                $('#alamat_pengantaran_group').hide();
                $('#jenis_delivery_group').hide();
                $('#jenis_delivery').prop('required', false);
                $('#jarak_delivery').prop('required', false);
                $('#alamat_pengantaran').prop('required', false);

            } else {
                $('#jenis_delivery_group').show();
                $('#jarak_delivery_group').show();
                $('#alamat_pengantaran_group').show();
                $('#jenis_delivery_group').show();
                $('#jenis_delivery').prop('required', true);
                $('#jarak_delivery').prop('required', true);
                $('#alamat_pengantaran').prop('required', true);
            }
        });

        // Trigger change event on page load
        $('#status_pengantaran').trigger('change');
    });
</script>
@endpush
