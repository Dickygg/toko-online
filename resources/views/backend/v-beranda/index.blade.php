@extends('backend.v-layout.app')
@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body border-top">
                <h5 class="card-title">{{$judul}}</h5>
                <div class="alert-success p-2" role="alert">
                    <h4 class="alert-heading">
                        Selamat Datang, <b>{{Auth::user()->nama}}</b>
                    </h4>
                    Aplikasi Toko Online dengan hak akses yang anda miliki sebagai
                    <b>
                        @if (Auth::user()->role == 1)
                        Super admin
                        @elseif (Auth::user()->role == 0)
                        Admin
                        @endif
                    </b>
                    ini adalah halaman utama dari aplikasi ini.
                    <hr>
                    <p class="mb-0 fw-bold">Toko Online Bersama</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection