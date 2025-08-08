@php
    $titles = [
        'create' => 'Tambah Pengguna',
        'edit' => 'Edit Pengguna',
        'show' => 'Detail Pengguna',
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
        <form action="{{ $formType === 'create' ? route('admin.users.store') : route('admin.users.update', $userData->id) }}" method="POST">
        @csrf
        @if($formType === 'edit')
            @method('PUT')
        @endif
    @endif

    {{-- Bagian field ini akan tetap tampil di semua mode --}}
    <div>
        <label for="name">Nama:</label>
        <input type="text" name="name" id="name" value="{{ old('name', $userData->name ?? '') }}" {{ $formType === 'show' ? 'disabled' : 'required' }} >
        @error('name')
            <span style="color: red;">{{ $message }}</span>
        @enderror
    </div>

    <div>
        <label for="email">Email:</label>
        <input type="email" name="email" id="email" value="{{ old('email', $userData->email ?? '') }}" {{ $formType === 'show' ? 'disabled' : 'required' }}>
        @error('email')
            <span style="color: red;">{{ $message }}</span>
        @enderror
    </div>

    @if($formType !== 'show')
        <div>
            <label for="password">Password:</label>
            <input type="password" name="password" id="password" {{ $formType === 'create' ? 'required' : '' }} >
        </div>

        <div>
            <label for="password_confirmation">Konfirmasi Password:</label>
            <input type="password" name="password_confirmation" id="password_confirmation" {{ $formType === 'create' ? 'required' : '' }} >
        </div>
    @endif

    <div>
        <label for="role">Role:</label>
        <select name="role" id="role" {{ $formType === 'show' ? 'disabled' : 'required' }} >
            <option value="">Pilih Role</option>
            <option value="User" @selected(old('role', 'User') == ($userData->role ?? ''))>User</option>
            <option value="Admin" @selected(old('role', 'Admin') == ($userData->role ?? ''))>Admin</option>
        </select>
        @error('role')
            <span style="color: red;">{{ $message }}</span>
        @enderror
    </div>

    <br>
    @if($formType !== 'show')
        <button type="submit">
            {{ $formType === 'create' ? 'Tambah Pengguna' : 'Simpan Perubahan' }}
        </button>
        </form> {{-- Tutup form HANYA jika bukan mode 'show' --}}
    @endif
    <br>
    <a href="{{ route('admin.users') }}">Kembali</a>
    
    <br>
@endsection