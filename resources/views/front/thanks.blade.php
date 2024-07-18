@extends('front.layouts.app')

@section('content')
    <section class="container">
        <div class="col-md-12 text-center py-5">
            @if(Session::has('success'))
            <div class="alert alert-success">
                {{ Session::get('success') }}
            </div>
            @endif
            <h1>TERIMAKASIH BANYAK SUDAH MEMESAN DI HAYSISTA SHOP"</h1>
            <p> Nomor pesanan Kamu : {{ $orderId }}</p>
        </div>
    </section>
@endsection