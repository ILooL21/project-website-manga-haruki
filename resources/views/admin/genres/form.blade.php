@php
    $titles = [
        'create' => 'Tambah Genre',
        'edit' => 'Edit Genre',
        'show' => 'Detail Genre',
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
        <form action="{{ $formType === 'create' ? route('admin.genres.store') : route('admin.genres.update', $genre->id) }}" method="POST">
        @csrf
        @if($formType === 'edit')
            @method('PUT')
        @endif
    @endif

    {{-- Bagian field ini akan tetap tampil di semua mode --}}
    <div>
        <label for="name">Nama:</label>
        <input type="text" name="name" id="name" value="{{ old('name', $genre->name ?? '') }}" {{ $formType === 'show' ? 'disabled' : 'required' }} >
        @error('name')
            <span style="color: red;">{{ $message }}</span>
        @enderror
    </div>

    <div>
        <label for="slug">Slug:</label>
        <textarea name="slug" id="slug" {{ $formType === 'show' ? 'disabled' : 'required' }} >{{ old('slug', $genre->slug ?? '') }}</textarea>
        @error('slug')
            <span style="color: red;">{{ $message }}</span>
        @enderror
    </div>

    <br>
    @if($formType !== 'show')
        <button type="submit">
            {{ $formType === 'create' ? 'Tambah Genre' : 'Simpan Perubahan' }}
        </button>
        </form> {{-- Tutup form HANYA jika bukan mode 'show' --}}
    @endif
    <br>
    <a href="{{ route('admin.genres') }}">Kembali</a>
    
    <br>
@endsection