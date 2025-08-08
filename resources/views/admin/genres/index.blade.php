@extends('admin.layouts.app')
@section('title', 'Tabel Data Genre')
@section('content')
    <div style="border: 1px solid #ccc; padding: 20px; margin-bottom: 20px;">
        <h2>Tabel Data Genre</h2>
        <br>
        <p>Berikut adalah daftar genre terdaftar:</p>
        <br>
        <a href="{{ route('admin.genres.create') }}" class="btn btn-success">Tambah Genre</a>
        <br>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nama Genre</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @if ($genres->count() === 0)
                    <tr>
                        <td colspan="3" class="text-center">Tidak ada genre yang ditemukan.</td>
                    </tr>
                @else
                    @foreach($genres as $genre)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $genre->name }}</td>
                            <td>
                            <a href="{{ route('admin.genres.show', $genre->id) }}" class="btn btn-info">Lihat</a>
                            <a href="{{ route('admin.genres.edit', $genre->id) }}" class="btn btn-primary">Edit</a>
                            <form action="{{ route('admin.genres.destroy', $genre->id) }}" method="POST" style="display:inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger" onclick="return confirm('Apakah Anda yakin ingin menghapus genre ini?')">Hapus</button>
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
        <br>
    @endif
@endsection