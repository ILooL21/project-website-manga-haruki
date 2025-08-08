@extends('admin.layouts.app')
@section('title', 'Dashboard')
@section('content')
<div style="border: 1px solid #ccc; padding: 20px; margin-bottom: 20px;">
    <h2>Dashboard</h2>
    <p>Selamat datang di panel admin!</p>
    <h3>
        Halo, {{ auth()->user()->name }}! Anda masuk sebagai {{ auth()->user()->role }}.
    </h3>
    <ul>
        <li>Total Data Pengguna: {{ $totalUsers }}</li>
        <li>Total Data Genre: {{ $totalGenres }}</li>
        <li>Total Data Manga: {{ $totalMangas }}</li>
    </ul>
</div>
@endsection