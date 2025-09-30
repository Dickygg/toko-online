@extends('backend.v-layout.app')
@section('content')
<!-- contentAwal -->
<h3> Halaman {{$judul}} </h3>
<a href="{{ route('backend.kategori.create') }}">
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
                                <th>Nama Kategori</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        @foreach ($kategori as $row)
                        <tbody>
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{$row->nama_kategori}}</td>
                                <td>
                                    <div class="d-flex">
                                        <a href="{{ route('backend.kategori.edit', $row->id) }}" title="Ubah Data">
                                            <button type="button" class="btn btn-cyan btn-sm"><i class="fas fa-edit"></i> Ubah</button>
                                        </a>
                                        <form action="{{ route('backend.kategori.destroy', $row->id) }}" method="POST" style="display:inline-block">
                                            @method('delete')
                                            @csrf
                                            <button type="submit" class="btn btn-danger btn-sm show_confirm" data-konf-delete="{{$row->nama}}" title="Hapus Data"><i class="fas fa-trash"></i> Hapus</button>
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