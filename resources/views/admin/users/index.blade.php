@extends('admin.layouts.app')

@section('title', 'Tabel Data Pengguna')

@section('content')
<div style="border: 1px solid #ccc; padding: 20px; margin-bottom: 20px;">
    <h2>Tabel Data Pengguna</h2>
    <br>
    <p>Berikut adalah daftar pengguna terdaftar:</p>
    <br>
    <a href="{{ route('admin.users.create') }}" class="btn btn-success">Tambah Pengguna</a>
    <br>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>ID</th>
                <th>Nama</th>
                <th>Email</th>
                <th>Role</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @if($users->count() === 0)
                <tr>
                    <td colspan="5" class="text-center">Tidak ada pengguna lain yang ditemukan.</td>
                </tr>
            @else
                @foreach($users as $user)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $user->name }}</td>
                        <td>{{ $user->email }}</td>
                    <td>{{ $user->role }}</td>
                    <td>
                       <a href="{{ route('admin.users.show', $user->id) }}" class="btn btn-info">Lihat</a>
                       <a href="{{ route('admin.users.edit', $user->id) }}" class="btn btn-primary">Edit</a>
                       <form action="{{ route('admin.users.destroy', $user->id) }}" method="POST" style="display:inline;">
                           @csrf
                           @method('DELETE')
                           <button type="submit" class="btn btn-danger" onclick="return confirm('Apakah Anda yakin ingin menghapus pengguna ini?')">Hapus</button>
                       </form>
                    </td>
                </tr>
                @endforeach
            @endif
        </tbody>
    </table>
</div>

    @if (session('status'))
        <br>
        <h2>Pesan Status</h2>
        <p>Status: {{ session('status') }}</p>
        <p>Message: {{ session('message') }}</p>
    @endif
@endsection