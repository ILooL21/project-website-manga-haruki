@extends('admin.layouts.app')

@section('title', 'Tabel Data Manga')

@section('content')
<div style="border: 1px solid #ccc; padding: 20px; margin-bottom: 20px;">
    <h2>Tabel Data Manga</h2>
    <br>
    <p>Berikut adalah daftar manga terdaftar:</p>
    <br>
    <a href="{{ route('admin.mangas.create') }}" class="btn btn-success">Tambah Manga</a>
    <br>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>ID</th>
                <th>Cover</th>
                <th>Judul</th>
                <th>Status</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @if($mangas->count() === 0)
                <tr>
                    <td colspan="5" class="text-center">Tidak ada manga lain yang ditemukan.</td>
                </tr>
            @else
                @foreach($mangas as $manga)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td><img src="{{ $manga->cover }}" alt="{{ $manga->title }}" style="width: 100px;"></td>
                        <td>{{ $manga->title }}</td>
                        <td>{{ $manga->status }}</td>
                    <td>
                       <a href="{{ route('admin.mangas.show', $manga->id) }}" class="btn btn-info">Lihat</a>
                       <a href="{{ route('admin.mangas.edit', $manga->id) }}" class="btn btn-primary">Edit</a>
                       <form action="{{ route('admin.mangas.destroy', $manga->id) }}" method="POST" style="display:inline;">
                           @csrf
                           @method('DELETE')
                           <button type="submit" class="btn btn-danger" onclick="return confirm('Apakah Anda yakin ingin menghapus manga ini?')">Hapus</button>
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