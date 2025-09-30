@extends('backend.v-layout.app')
@section('content')
<!-- contentAwal -->
<h3> Halaman {{$judul}} </h3>
<a href="{{ route('backend.produk.create') }}">
    <button type="button" class="btn- btn-primary mb-2"><i class="fas fa-plus"></i> Tambah</button>
</a>

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <h5 class="card-title">{{$judul}}</h5>
                <div class="table-responsive">
                    <table id="zero_config" class="table table-striped table-bordered">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Kategori</th>
                                <th>Status</th>
                                <th>Nama Produk</th>
                                <th>Harga</th>
                                <th>Stok Barang</th>
                                <th>Berat</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        @foreach ($produk as $row)
                        <tbody>
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{$row->kategori->nama_kategori}}</td>
                                <td>@if ($row->status == 1)
                                    <span class="badge badge-success"></i>Publish</span>
                                    @elseif($row->status == 0)
                                    <span class="badge badge-secondary">blok</span>
                                    @endif
                                </td>
                                <td>{{$row->nama_produk}}</td>
                                <td>Rp. {{number_format($row->harga,0, ',' , '.')}}
                                </td>
                                <td>{{$row->stock}}</td>
                                <td>{{$row->berat}}</td>
                                <td>
                                    <div class="d-flex">
                                        <a href="{{ route('backend.produk.edit', $row->id) }}" title="Ubah Data">
                                            <button type="button" class="btn btn-cyan btn-sm"><i class="fas fa-edit"></i> Ubah</button>
                                        </a>
                                        <a href="{{ route('backend.produk.show', $row->id) }}" title="Ubah Data">
                                            <button type="button" class="btn btn-warning btn-sm"><i class="fas fa-plus"></i> Ubah</button>
                                        </a>
                                        <form action="{{ route('backend.produk.destroy', $row->id) }}" method="POST" style="display:inline-block">
                                            @method('delete')
                                            @csrf
                                            <button type="submit" class="btn btn-danger btn-sm show_confirm" data-konf-delete="{{$row->nama_produk}}" title="Hapus Data"><i class="fas fa-trash"></i> Hapus</button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

            </div>
        </div>
    </div>
</div>

<!-- contentAkhir -->
@endsection