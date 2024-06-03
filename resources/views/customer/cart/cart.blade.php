
@extends('layouts.app')
@push('css')
<style>


    .title {
        margin-bottom: 5vh;
    }

    .card {
        margin: auto;
        max-width: 950px;
        width: 90%;
        box-shadow: 0 6px 20px 0 rgba(0, 0, 0, 0.19);
        border-radius: 1rem;
        border: transparent;
    }

    @media(max-width:767px) {
        .card {
            margin: 3vh auto;
        }
    }

    .cart {
        background-color: #fff;
        padding: 4vh 5vh;
        border-bottom-left-radius: 1rem;
        border-top-left-radius: 1rem;
    }

    @media(max-width:767px) {
        .cart {
            padding: 4vh;
            border-bottom-left-radius: unset;
            border-top-right-radius: 1rem;
        }
    }

    .summary {
        background-color: #ddd;
        border-top-right-radius: 1rem;
        border-bottom-right-radius: 1rem;
        padding: 4vh;
        color: rgb(65, 65, 65);
    }

    @media(max-width:767px) {
        .summary {
            border-top-right-radius: unset;
            border-bottom-left-radius: 1rem;
        }
    }

    .summary .col-2 {
        padding: 0;
    }

    .summary .col-10 {
        padding: 0;
    }

    .row {
        margin: 0;
    }

    .title b {
        font-size: 1.5rem;
    }

    .main {
        margin: 0;
        padding: 2vh 0;
        width: 100%;
    }

    .col-2,
    .col {
        padding: 0 1vh;
    }

    a {
        padding: 0 1vh;
    }

    .close {
        margin-left: auto;
        font-size: 0.7rem;
    }

    img {
        width: 3.5rem;
    }

    .back-to-shop {
        margin-top: 4.5rem;
    }

    h5 {
        margin-top: 4vh;
    }

    hr {
        margin-top: 1.25rem;
    }

    form {
        padding: 2vh 0;
    }

    select {
        border: 1px solid rgba(0, 0, 0, 0.137);
        padding: 1.5vh 1vh;
        margin-bottom: 4vh;
        outline: none;
        width: 100%;
        background-color: rgb(247, 247, 247);
    }

    input {
        border: 1px solid rgba(0, 0, 0, 0.137);
        padding: 1vh;
        margin-bottom: 4vh;
        outline: none;
        width: 100%;
        background-color: rgb(247, 247, 247);
    }

    input:focus::-webkit-input-placeholder {
        color: transparent;
    }

    .btn {
        background-color: #000;
        border-color: #000;
        color: white;
        width: 100%;
        font-size: 0.7rem;
        margin-top: 4vh;
        padding: 1vh;
        border-radius: 0;
    }

    .btn:focus {
        box-shadow: none;
        outline: none;
        box-shadow: none;
        color: white;
        -webkit-box-shadow: none;
        -webkit-user-select: none;
        transition: none;
    }

    .btn:hover {
        color: white;
    }

    a {
        color: black;
    }

    a:hover {
        color: black;
        text-decoration: none;
    }

    #code {
        background-image: linear-gradient(to left, rgba(255, 255, 255, 0.253), rgba(255, 255, 255, 0.185)), url("https://img.icons8.com/small/16/000000/long-arrow-right.png");
        background-repeat: no-repeat;
        background-position-x: 95%;
        background-position-y: center;
    }
</style>
@endpush
@section('content')
<main>
    <article>
        <div class="card mt-5">
            <div class="row">
                <div class="col-md-8 cart">
                    <div class="title">
                        <div class="row">
                            <div class="col">
                                <h4><b>Shopping Cart</b></h4>
                            </div>
                            <div class="col align-self-center text-right text-muted">{{ count($cart['products'] ?? []) + count($cart['hampers'] ?? []) }} items</div>
                        </div>
                    </div>
                    @if (session('discount'))
                        <div class="alert alert-success">
                            Promo applied! You received a discount of Rp. {{ session('discount') }}.
                        </div>
                    @endif
                    <!-- Display products in the cart -->
                    @if (isset($cart['products']))
                        @foreach ($cart['products'] as $id => $details)
                            <div class="row border-top border-bottom">
                                <div class="row main align-items-center">
                                    <div class="col-2"><img class="img-fluid" src="{{ asset('storage/dukpro/' . $details['image']) }}"></div>
                                    <div class="col">
                                        <div class="row text-muted">{{ $details['nama'] }}</div>
                                    </div>
                                    <div class="col">
                                        <form action="{{ route('customer.cart.update') }}" method="POST">
                                            @csrf
                                            <input type="hidden" name="id" value="{{ $id }}">
                                            <a href="#" onclick="updateQuantity(this, -1)">-</a>
                                            <input type="number" name="quantity" value="{{ $details['quantity'] }}" class="border">
                                            <a href="#" onclick="updateQuantity(this, 1)">+</a>
                                        </form>
                                    </div>
                                    <div class="col">Rp. {{ $details['harga'] * $details['quantity'] }}
                                        <form action="{{ route('customer.cart.remove') }}" method="POST" style="display:inline">
                                            @csrf
                                            <input type="hidden" name="id" value="{{ $id }}">
                                            <button type="submit" class="close btn btn-link p-0 m-0">&times;</button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    @endif
                    <!-- Display hampers in the cart -->
                    @if (isset($cart['hampers']))
                        @foreach ($cart['hampers'] as $id => $details)
                            <div class="row border-top border-bottom">
                                <div class="row main align-items-center">
                                    <div class="col-2"><img class="img-fluid" src="{{ asset('storage/hampers/' . $details['image']) }}"></div>
                                    <div class="col">
                                        <div class="row text-muted">{{ $details['nama'] }}</div>
                                    </div>
                                    <div class="col">
                                        <form action="{{ route('customer.cart.update') }}" method="POST">
                                            @csrf
                                            <input type="hidden" name="id" value="{{ $id }}">
                                            <a href="#" onclick="updateQuantity(this, -1)">-</a>
                                            <input type="number" name="quantity" value="{{ $details['quantity'] }}" min="1" class="border">
                                            <a href="#" onclick="updateQuantity(this, 1)">+</a>
                                        </form>
                                    </div>
                                    <div class="col">Rp. {{ $details['harga'] * $details['quantity'] }}
                                        <form action="{{ route('customer.cart.remove') }}" method="POST" style="display:inline">
                                            @csrf
                                            <input type="hidden" name="id" value="{{ $id }}">
                                            <button type="submit" class="close btn btn-link p-0 m-0">&times;</button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    @endif
                    <div class="back-to-shop"><a href="{{ url('/') }}">&leftarrow;</a><span class="text-muted">Back to shop</span></div>
                </div>
                <div class="col-md-4 summary">
                    <div>
                        <h5><b>Summary</b></h5>
                    </div>
                    <hr>
                    <div class="row">
                        <div class="col" style="padding-left:0;">ITEMS {{ count($cart['products'] ?? []) + count($cart['hampers'] ?? []) }}</div>
                        <div class="col text-right">Rp. {{ array_reduce($cart['products'] ?? [], function($carry, $item) { return $carry + $item['harga'] * $item['quantity']; }, 0) + array_reduce($cart['hampers'] ?? [], function($carry, $item) { return $carry + $item['harga'] * $item['quantity']; }, 0) }}</div>
                    </div>
                    {{-- <form action="{{ route('customer.cart.applyPromo') }}" method="POST">
                        @csrf
                        <div class="row">
                            @foreach ($promoPoints as $promo)
                            <div class="col-12 mb-2">
                                <div class="row">
                                    <div class="col">{{ $promo['nama'] }} - {{ $promo['jumlah_point'] }} Poin</div>
                                    <div class="col text-right">
                                        @if (session('claimed_promo_ids') && in_array($promo['id'], session('claimed_promo_ids')))
                                        <button type="button" class="btn btn-success btn-sm">Diklaim</button>
                                        <input type="hidden" name="jenis" value="remove">
                                        @else
                                        <input type="hidden" name="jenis" value="add">
                                        <button type="submit" class="btn btn-primary btn-sm" name="promo_id" value="{{ $promo['id'] }}">Klaim ini</button>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </div>
                        <input type="hidden" name="total_price" value="{{ array_reduce($cart['products'] ?? [], function($carry, $item) { return $carry + $item['harga'] * $item['quantity']; }, 0) + array_reduce($cart['hampers'] ?? [], function($carry, $item) { return $carry + $item['harga'] * $item['quantity']; }, 0) }}">
                    </form> --}}
                    @if (session('status_claim') == 'true')
                    <form action="{{ route('customer.cart.applyPromoPoint') }}" method="POST">
                        @csrf
                        <div class="row">
                            <?php $point = \App\Models\PromoPoint::where('nama', auth()->user()->name)->get();
                            $jumlah_point = 0;
                            foreach ($point as $p) {
                                $jumlah_point += $p->jumlah_point;
                            }
                            ?>

                            <div class="col-12 mb-2">
                                <div class="row">
                                    <div class="col">Point Anda - {{ $jumlah_point }} Poin</div>
                                    <div class="col text-right">
                                        <input type="hidden" name="jenis" value="remove">
                                        <button type="submit" class="btn btn-danger btn-sm">Diklaim</button>
                                    </div>
                                </div>
                            </div>

                        </div>
                        <input type="hidden" name="point_user" value="{{ $jumlah_point }}">
                        <input type="hidden" name="total_price" value="{{ array_reduce($cart['products'] ?? [], function($carry, $item) { return $carry + $item['harga'] * $item['quantity']; }, 0) + array_reduce($cart['hampers'] ?? [], function($carry, $item) { return $carry + $item['harga'] * $item['quantity']; }, 0) }}">
                    </form>
                    @else
                    <form action="{{ route('customer.cart.applyPromoPoint') }}" method="POST">
                        @csrf
                        <?php

                        session(['claimed_promo_ids' => []]);
                        session(['total_discount' => 0]);
                        session(['total_price_after_discount' => 0]);
                        ?>
                        <div class="row">
                            <?php $point = \App\Models\PromoPoint::where('nama', auth()->user()->name)->get();
                            $jumlah_point = 0;
                            foreach ($point as $p) {
                                $jumlah_point += $p->jumlah_point;
                            }
                            ?>

                            <div class="col-12 mb-2">
                                <div class="row">
                                    <div class="col">Point Anda - {{ $jumlah_point }} Poin</div>
                                    @if($jumlah_point >1)
                                    <div class="col text-right">
                                        <button type="submit" class="btn btn-primary btn-sm" name="promo_id" value="">Klaim</button>
                                    </div>
                                    @endif
                                </div>
                            </div>

                        </div>
                        <input type="hidden" name="point_user" value="{{ $jumlah_point }}">
                        <input type="hidden" name="total_price" value="{{ array_reduce($cart['products'] ?? [], function($carry, $item) { return $carry + $item['harga'] * $item['quantity']; }, 0) + array_reduce($cart['hampers'] ?? [], function($carry, $item) { return $carry + $item['harga'] * $item['quantity']; }, 0) }}">
                    </form>
                    @endif
                    <form>

                        <p>GIVE CODE</p>
                        <input id="code" placeholder="Enter your code">
                    </form>
                    <div class="row" style="border-top: 1px solid rgba(0,0,0,.1); padding: 2vh 0;">
                        <div class="col">TOTAL PRICE</div>
                        <div class="col text-right">Rp. {{ array_reduce($cart['products'] ?? [], function($carry, $item) { return $carry + $item['harga'] * $item['quantity']; }, 0) + array_reduce($cart['hampers'] ?? [], function($carry, $item) { return $carry + $item['harga'] * $item['quantity']; }, 0) }}</div>
                    </div>
                <form action="{{route('customer.transaksi.create')}}" method="get">
                    @csrf
                    <button class="btn">CHECKOUT</button>
                </form>
                </div>
            </div>
        </div>
    </article>
</main>
    {{-- push js --}}
@endsection
@push('js')
<script>
    function updateQuantity(element, change) {
        let form = element.closest('form');
        let input = form.querySelector('input[name="quantity"]');
        input.value = parseInt(input.value) + change;
        if (input.value < 1) {
            input.value = 1;
        }
        form.submit();
    }
    </script>
@endpush
