@extends('admin.layouts.app')

@section('title', 'Edit Chapter')

@section('content')
<div x-data="{ 
    title: '{{ $chapter->title }}', 
    chapter_number: '{{ $chapter->chapter_number }}',
    release_date: '{{ $chapter->release_date ? $chapter->release_date->format('Y-m-d') : "" }}',
}">
    <div class="container mx-auto px-4 pt-6 lg:px-8 lg:pt-8">
        <div class="flex flex-col gap-2 text-center sm:flex-row sm:items-center sm:justify-between sm:text-start">
            <div class="grow">
                <h1 class="mb-1 text-xl font-bold text-zinc-500">Edit Chapter</h1>
                <h2 class="text-sm font-medium text-zinc-500">
                    Edit the details of the chapter
                </h2>
            </div>
        </div>
        <form action="{{ route('admin.chapters.update', $chapter->id) }}" method="POST" class="space-y-4 py-5">
            @csrf
            @method('PUT')
            <div>
                <label for="title" class="mb-1 block text-sm font-medium text-zinc-700">Judul</label>
                <input x-model.trim="title" type="text" id="title" name="title" required
                    class="w-full rounded-lg border border-zinc-300 bg-white px-3 py-2 text-sm text-zinc-900 placeholder-zinc-400 focus:border-purple-500 focus:outline-none focus:ring-2 focus:ring-purple-500"
                    placeholder="Misal: Pertarungan Dimulai" />
            </div>
            <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                <div>
                    <label for="chapter_number" class="mb-1 block text-sm font-medium text-zinc-700">Nomor Chapter</label>
                    <input x-model.number="chapter_number" type="number" step="0.1" id="chapter_number" name="chapter_number" required
                        class="w-full rounded-lg border border-zinc-300 bg-white px-3 py-2 text-sm text-zinc-900 placeholder-zinc-400 focus:border-purple-500 focus:outline-none focus:ring-2 focus:ring-purple-500" />
                        <p class="mt-1 text-xs text-zinc-500">Bisa menggunakan desimal (contoh: 10.5) bila perlu.</p>
                </div>
                <div>
                    <label for="release_date" class="mb-1 block text-sm font-medium text-zinc-700">Tanggal Rilis</label>
                    <input x-model.date="release_date" type="date" id="release_date" name="release_date"
                        class="w-full rounded-lg border border-zinc-300 bg-white px-3 py-2 text-sm text-zinc-900 placeholder-zinc-400 focus:border-purple-500 focus:outline-none focus:ring-2 focus:ring-purple-500" />
                    <p class="mt-1 text-xs text-zinc-500">Gunakan input tanggal bawaan browser (format dapat berbeda per perangkat).</p>
                </div>
            </div>
            <div class="flex items-center justify-end gap-2 pt-2">
                <button type="button" onclick="window.location='{{ route('admin.mangas.show', $chapter->manga_id) }}'"
                        class="rounded-lg border border-zinc-300 bg-white px-4 py-2 text-sm font-semibold text-zinc-700 hover:bg-zinc-50 hover:cursor-pointer">
                    Batal
                </button>
                <button type="submit"
                        class="rounded-lg bg-purple-600 px-4 py-2 text-sm font-semibold text-white hover:bg-purple-700">Simpan</button>
            </div>
        </form>
    </div>
</div>
@endsection