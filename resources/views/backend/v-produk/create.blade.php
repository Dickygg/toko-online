@extends('backend.v-layout.app')
@section('content')
<!-- Content Start -->
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <form class="form-horizontal" action="{{ route('backend.produk.store') }}"
                    method="post" enctype="multipart/form-data">
                    @csrf
                    <div class="card-body">
                        <h4 class="card-title"> {{$judul}} </h4>
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Foto</label>
                                    <img class="foto-preview">
                                    <input type="file" name="foto" class="form-control
@error('foto') is-invalid @enderror" onchange="previewFoto()">
                                    @error('foto')
                                    <div class="invalid-feedback alert-danger">{{ $message
}}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-8">
                                <div class="form-group">
                                    <label>Kategori</label>
                                    <select name="kategori_id" class="form-control @error('kategori_id') is-invalid @enderror">
                                        <option value=""> - Pilih kategori -
                                        </option>
                                        @foreach($kategori as $k)
                                        <option value="{{$k->id}}"> {{$k->nama_kategori}}
                                        </option>
                                        @endforeach
                                    </select>
                                    @error('kategori_id')
                                    <span class="invalid-feedback alert-danger"
                                        role="alert">
                                        {{ $message }}
                                    </span>
                                    @enderror
                                </div>
                                <div class="form-group">
                                    <label>Nama Produk</label>
                                    <input type="text" name="nama_produk" value="{{ old('nama_produk')}}" class="form-control @error('nama_produk') is-invalid @enderror" placeholder="Masukkan Nama Produk">
                                    @error('nama_produk')
                                    <span class="invalid-feedback alert-danger"
                                        role="alert">
                                        {{ $message }}
                                    </span>
                                    @enderror
                                </div>
                                <div class="form-group">
                                    <label>Detail</label>
                                    <textarea type="text" name="detail" value="{{ old('detail')}}" class="form-control @error('detail') is-invalid @enderror" placeholder="Masukkan detail" id="ckeditor"></textarea>
                                    @error('detail')
                                    <span class="invalid-feedback alert-danger"
                                        role="alert">
                                        {{ $message }}
                                    </span>
                                    @enderror
                                </div>
                                <div class="form-group">
                                    <label>Harga</label>
                                    <input type="text" onkeypress="return hanyaAngka(event)" name="harga" value="{{ old('harga') }}" class="form-control @error('harga') is-invalid @enderror" placeholder="Masukkan Nomor harga">
                                    @error('harga')
                                    <span class="invalid-feedback alert-danger"
                                        role="alert">
                                        {{ $message }}
                                    </span>
                                    @enderror
                                </div>
                                <div class="form-group">
                                    <label>Stock</label>
                                    <input type="text" onkeypress="return hanyaAngka(event)" name="stock" class="form-control @error('stock') is-invalid @enderror" placeholder="Masukkan stock">
                                    @error('stock')
                                    <span class="invalid-feedback alert-danger"
                                        role="alert">
                                        {{ $message }}
                                    </span>
                                    @enderror
                                </div>
                                <div class="form-group">
                                    <label>Berat</label>
                                    <input type="text" name="berat" onkeypress="return hanyaAngka(event)"
                                        class="form-control @error('berat') is-invalid @enderror" placeholder="Masukkan berat">
                                    @error('berat')
                                    <span class="invalid-feedback alert-danger"
                                        role="alert">
                                        {{ $message }}
                                    </span>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="border-top">
                        <div class="card-body">
                            <button type="submit" class="btn btn-primary">Simpan</button>
                            <a href="{{ route('backend.user.index') }}">
                                <button type="button" class="btn btnsecondary">Kembali</button>
                            </a>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<!-- start End -->
@endsection