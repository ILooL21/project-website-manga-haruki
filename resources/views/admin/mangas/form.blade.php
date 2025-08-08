@php
    $titles = [
        'create' => 'Tambah Manga',
        'edit' => 'Edit Manga',
        'show' => 'Detail Manga',
    ];
    $title = $titles[$formType];
@endphp

@extends('admin.layouts.app')

@section('title', $title)

@section('content')
    <h2>{{ $title }}</h2>
    <br>

    {{-- 1. Mulai form HANYA jika bukan mode 'show' --}}
    @if($formType !== 'show')
        <form action="{{ $formType === 'create' ? route('admin.mangas.store') : route('admin.mangas.update', $mangaData->id) }}" method="POST">
        @csrf
        @if($formType === 'edit')
            @method('PUT')
        @endif
    @endif

    <br>
    @if($formType === 'show')
        <div>
            <label for="cover">Cover:</label>
            <img src="{{ $mangaData->cover }}" alt="Cover" style="width: 100px;">
        </div>
    @else
        <div>
            <label for="cover">Cover:</label>
            <input type="file" name="cover" id="cover" {{ $formType === 'show' ? 'disabled' : 'required' }}>
            @error('cover_image')
                <span style="color: red;">{{ $message }}</span>
            @enderror
        </div>
    @endif

    <div>
        <label for="title">Judul:</label>
        <input type="text" name="title" id="title" value="{{ old('title', $mangaData->title ?? '') }}" {{ $formType === 'show' ? 'disabled' : 'required' }}>
        @error('title')
            <span style="color: red;">{{ $message }}</span>
        @enderror
    </div>

    <div>
        <label for="description">Deskripsi:</label>
        <textarea name="description" id="description" {{ $formType === 'show' ? 'disabled' : 'required' }} >{{ old('description', $mangaData->description ?? '') }}</textarea>
        @error('description')
            <span style="color: red;">{{ $message }}</span>
        @enderror
    </div>

    <div>
        <label for="status">Status:</label>
        <select name="status" id="status" {{ $formType === 'show' ? 'disabled' : 'required' }}>
            <option value="Ongoing" @selected(old('status', $mangaData->status ?? '') == 'Ongoing')>Ongoing</option>
            <option value="Completed" @selected(old('status', $mangaData->status ?? '') == 'Completed')>Completed</option>
        </select>
        @error('status')
            <span style="color: red;">{{ $message }}</span>
        @enderror
    </div>

    <br>
    @if($formType !== 'show')
        <button type="submit">
            {{ $formType === 'create' ? 'Tambah Manga' : 'Simpan Perubahan' }}
        </button>
    </form> 
    @endif
    <br>
    <a href="{{ route('admin.mangas') }}">Kembali</a>

    <br>
@endsection