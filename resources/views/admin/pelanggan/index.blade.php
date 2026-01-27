@extends('admin.layout')
@section('title','Kelola Data Pelanggan - Admin')
@section('admin_content')
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h1 class="h4 m-0">Kelola Data Pelanggan</h1>
            <a href="{{ route('admin.pelanggan.create') }}" class="btn btn-primary"><i class="bi bi-person-plus me-1"></i> Tambah Pelanggan</a>
        </div>
        @if(session('status'))
            <div class="alert alert-success">{{ session('status') }}</div>
        @endif
        <div class="card p-3">
            <div class="table-responsive">
                <table class="table align-middle">
                    <thead>
                        <tr>
                            <th>Nama</th>
                            <th>Email</th>
                            <th>No HP</th>
                            <th>Status</th>
                            <th>Alamat</th>
                            <th class="text-end">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($pelanggan as $p)
                        <tr>
                            <td>{{ $p->name }}</td>
                            <td>{{ $p->email }}</td>
                            <td>{{ $p->phone }}</td>
                            <td>
                                @if($p->is_active)
                                    <span class="badge bg-success">Aktif</span>
                                @else
                                    <span class="badge bg-danger">Diblokir</span>
                                @endif
                            </td>
                            <td>{{ $p->address }}</td>
                            <td class="text-end text-nowrap">
                                <div class="d-flex justify-content-end gap-1">
                                    <a href="{{ route('admin.impersonate', $p->id) }}" class="btn btn-sm btn-info" title="Login Sebagai User Ini" onclick="return confirm('Login sebagai user ini?')"><i class="bi bi-box-arrow-in-right text-white"></i></a>
                                    <a href="{{ route('admin.pelanggan.edit', $p) }}" class="btn btn-sm btn-warning"><i class="bi bi-pencil-square"></i></a>
                                    <form action="{{ route('admin.pelanggan.block', $p->id) }}" method="POST" class="d-inline">
                                        @csrf
                                        <button type="submit" class="btn btn-sm {{ $p->is_active ? 'btn-danger' : 'btn-success' }}" 
                                            title="{{ $p->is_active ? 'Blokir Akun' : 'Aktifkan Akun' }}"
                                            onclick="return confirm('Apakah Anda yakin ingin {{ $p->is_active ? 'memblokir' : 'mengaktifkan kembali' }} user ini?')">
                                            <i class="bi {{ $p->is_active ? 'bi-ban' : 'bi-check-circle' }}"></i>
                                        </button>
                                    </form>
                                    <form action="{{ route('admin.pelanggan.destroy', $p) }}" method="POST" class="d-inline" onsubmit="return confirm('Hapus pelanggan?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger"><i class="bi bi-trash"></i></button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="mt-2">{{ $pelanggan->links() }}</div>
        </div>
        

@endsection
