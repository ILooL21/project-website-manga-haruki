<!-- filepath: d:\Kumpulan Program dan Projek\project-website-manga-haruki\resources\views\landing-page\manga_detail.blade.php -->
@extends('landing-page.layouts.main')

@section('content')
<div class="container mx-auto px-4 my-12">
    <!-- Header Section -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <!-- Manga Cover -->
        <div class="md:col-span-1 flex justify-center">
            <img src="{{ asset('images/yuji.webp') }}" alt="Manga Cover" class="rounded-lg shadow-md w-[300px] h-[400px] object-cover">
        </div>
        <!-- Manga Info -->
        <div class="md:col-span-2">
            <h1 class="text-3xl font-bold text-white mb-4">I'm Not That Kind of Talent</h1>
            <p class="text-gray-400 mb-6">
                Dia adalah mantan pembunuh terhebat yang bisa pergi ke mana saja tanpa terdeteksi. Namun, dia sendiri tidak tahu bahwa dia adalah seseorang yang kuat dan kejam ketika masuk ke medan perang.
            </p>
            <div class="flex flex-wrap gap-2 mb-4">
                <span class="px-3 py-1 rounded bg-purple-900/30 text-purple-300 text-sm">Action</span>
                <span class="px-3 py-1 rounded bg-purple-900/30 text-purple-300 text-sm">Fantasy</span>
                <span class="px-3 py-1 rounded bg-purple-900/30 text-purple-300 text-sm">Drama</span>
                <!-- Tambahkan genre lainnya -->
            </div>
            <div class="flex items-center gap-4 mb-4">
                <span class="text-gray-400">Author: <span class="text-white">Sang Hero</span></span>
                <span class="text-gray-400">Artist: <span class="text-white">John Doe</span></span>
                <span class="text-gray-400">Status: <span class="text-white">Ongoing</span></span>
            </div>
            <div class="flex gap-4">
                <button class="btn btn-primary">Baca</button>
                <button class="btn btn-outline">Bookmark</button>
                <button class="btn btn-outline">Tambah ke Read List</button>
            </div>
        </div>
    </div>

    <!-- Chapter List Section -->
    <div class="mt-12">
        <h2 class="text-2xl font-bold text-white mb-4">Daftar Chapter</h2>
        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-4">
            <!-- Contoh Chapter -->
            <a href="#" class="block bg-[#1a1625] p-4 rounded-lg shadow-md hover:bg-purple-900/20">
                <h3 class="text-lg font-semibold text-white">Chapter 91</h3>
                <p class="text-gray-400 text-sm">2 hari lalu</p>
            </a>
            <a href="#" class="block bg-[#1a1625] p-4 rounded-lg shadow-md hover:bg-purple-900/20">
                <h3 class="text-lg font-semibold text-white">Chapter 90</h3>
                <p class="text-gray-400 text-sm">1 minggu lalu</p>
            </a>
            <a href="#" class="block bg-[#1a1625] p-4 rounded-lg shadow-md hover:bg-purple-900/20">
                <h3 class="text-lg font-semibold text-white">Chapter 89</h3>
                <p class="text-gray-400 text-sm">2 minggu lalu</p>
            </a>
            <!-- Tambahkan lebih banyak chapter -->
        </div>
        <!-- Pagination -->
        <div class="flex justify-center mt-6 gap-3">
            <button class="btn btn-outline">1</button>
            <button class="btn btn-primary">2</button>
            <button class="btn btn-outline">3</button>
        </div>
    </div>

    <!-- Comment Section -->
    <div class="mt-12">
        <h2 class="text-2xl font-bold text-white mb-4">Komentar</h2>
        <div class="bg-[#1a1625] p-4 rounded-lg shadow-md">
            <textarea class="textarea textarea-bordered w-full mb-4" placeholder="Tulis komentar..."></textarea>
            <button class="btn btn-primary w-full">Kirim</button>
        </div>
        <!-- Contoh Komentar -->
        <div class="mt-6">
            <div class="flex items-start gap-4 mb-4">
                <img src="{{ asset('images/user-avatar.webp') }}" alt="User Avatar" class="w-12 h-12 rounded-full">
                <div>
                    <h3 class="text-white font-semibold">Riee Velidged</h3>
                    <p class="text-gray-400 text-sm">1 jam lalu</p>
                    <p class="text-gray-300 mt-2">Hasilnya sangat bagus!</p>
                </div>
            </div>
            <!-- Tambahkan lebih banyak komentar -->
        </div>
    </div>
</div>
@endsection