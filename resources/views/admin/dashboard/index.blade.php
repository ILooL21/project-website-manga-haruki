@extends('admin.layouts.app')
@section('title', 'Dashboard')
@section('content')
    <h2>Dashboard</h2>
    <br>
    <p>Welcome to the admin panel!</p>
    <br>
    <h3>
        Hello, {{ auth()->user()->name }}! You are logged in as an {{ auth()->user()->role }}.
    </h3>
@endsection