@extends('admin.layouts.app')

@section('title', 'Edit Genre')

@section('content')
<div x-data="{ 
    name:'{{ $genre->name }}', 
    slug:'{{ $genre->slug }}', 
    makeSlug(){ 
        this.slug = String(this.name || '')
            .toLowerCase()
            .trim()
            .replace(/[^a-z0-9\s-]/g,'')
            .replace(/\s+/g,'-')
            .replace(/-+/g,'-'); 

        this.name = this.name.charAt(0).toUpperCase() + this.name.slice(1);
    }
}">
    <div class="container mx-auto px-4 pt-6 lg:px-8 lg:pt-8">
        <div class="flex flex-col gap-2 text-center sm:flex-row sm:items-center sm:justify-between sm:text-start">
            <div class="grow">
                <h1 class="mb-1 text-xl font-bold text-zinc-500">Edit Genre</h1>
                <h2 class="text-sm font-medium text-zinc-500">
                    Edit the details of the genre
                </h2>
            </div>
        </div>
        <form action="{{ route('admin.genres.update', $genre->id) }}" method="POST" class="space-y-4 py-5">
            @csrf
            @method('PUT')
            <div>
                <label for="name" class="mb-1 block text-sm font-medium text-zinc-700">Nama</label>
                <input x-model.trim="name" @input="makeSlug()" type="text" id="name" name="name" required
                    class="w-full rounded-lg border border-zinc-300 bg-white px-3 py-2 text-sm text-zinc-900 placeholder-zinc-400 focus:border-purple-500 focus:outline-none focus:ring-2 focus:ring-purple-500"
                    placeholder="Misal: Action" />
            </div>
            <div>
                <label for="slug" class="mb-1 block text-sm font-medium text-zinc-700">Slug</label>
                <input x-model.trim="slug" type="text" id="slug" name="slug" required
                    class="w-full rounded-lg border border-zinc-300 bg-white px-3 py-2 text-sm text-zinc-900 placeholder-zinc-400 focus:border-purple-500 focus:outline-none focus:ring-2 focus:ring-purple-500"
                    placeholder="action" />
                <p class="mt-1 text-xs text-zinc-500">Slug dibuat otomatis dari nama, bisa diubah manual.</p>
            </div>
            <div class="flex items-center justify-end gap-2 pt-2">
                <button type="button" onclick="window.location='{{ route('admin.genres') }}'"
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