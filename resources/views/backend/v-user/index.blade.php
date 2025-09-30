@extends('backend.v-layout.app')
@section('content')
<!-- contentAwal -->
<h3> Halaman {{$judul}} </h3>
<a href="{{ route('backend.user.create') }}">
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
                                <th>email</th>
                                <th>Nama</th>
                                <th>Role</th>
                                <th>Status</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        @foreach ($index as $row)
                        <tbody>
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{$row->email}}</td>
                                <td>{{$row->nama}}</td>
                                <td>@if($row->role == 1)
                                    <span class="badge badge-primary"></i>Super Admin</span>
                                    @elseif($row->role == 0)
                                    <span class="badge badge-success"></i>Admin</span>
                                    @endif
                                </td>
                                <td>@if ($row->status == 1)
                                    <span class="badge badge-success"></i>Aktif</span>
                                    @elseif($row->status == 0)
                                    <span class="badge badge-secondary">No-Aktif</span>
                                    @endif
                                </td>
                                <td>
                                    <div class="d-flex">
                                        <a href="{{ route('backend.user.edit', $row->id) }}" title="Ubah Data">
                                            <button type="button" class="btn btn-cyan btn-sm"><i class="fas fa-edit"></i> Ubah</button>
                                        </a>
                                        <form action="{{ route('backend.user.destroy', $row->id) }}" method="POST" style="display:inline-block">
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