@extends('admin.layouts.app')

@section('title', 'Edit Iklan')

@section('content')
<div x-data="{ section:'{{ addslashes($iklan->section) }}', link:'{{ addslashes($iklan->link ?? '') }}' }">
    <div class="container mx-auto px-4 pt-6 lg:px-8 lg:pt-8">
        <div class="flex flex-col gap-2 text-center sm:flex-row sm:items-center sm:justify-between sm:text-start">
            <div class="grow">
                <h1 class="mb-1 text-xl font-bold text-zinc-500">Edit Iklan</h1>
                <h2 class="text-sm font-medium text-zinc-500">Ubah detail iklan</h2>
            </div>
        </div>
    <form action="{{ route('admin.iklan.update', urlencode(\Illuminate\Support\Facades\Crypt::encryptString($iklan->id))) }}" method="POST" enctype="multipart/form-data" class="space-y-4 py-5">
            @csrf
            @method('PUT')

            <div>
                <label for="section" class="mb-1 block text-sm font-medium text-zinc-700">Section</label>
                <input x-model.trim="section" type="text" id="section" name="section" required
                    class="w-full rounded-lg border border-zinc-300 bg-white px-3 py-2 text-sm text-zinc-900 placeholder-zinc-400 focus:border-purple-500 focus:outline-none focus:ring-2 focus:ring-purple-500"
                    placeholder="Misal: sidebar" />
            </div>

            <div>
                <label class="mb-1 block text-sm font-medium text-zinc-700">Gambar saat ini</label>
                @if($iklan->image_path)
                    <div class="mt-2">
                        <img src="{{ asset('storage/' . $iklan->image_path) }}" alt="iklan" class="max-h-36 rounded border" />
                    </div>
                @else
                    <div class="mt-2 text-sm text-zinc-500">Belum ada gambar.</div>
                @endif
            </div>

            <div>
                <label for="image" class="mb-1 block text-sm font-medium text-zinc-700">Ganti gambar (opsional)</label>
          <input type="file" id="image" name="image" accept="image/*" class="file-input file-input-bordered w-full bg-white text-zinc-900"
              onchange="document.getElementById('file-name').textContent = this.files[0] ? this.files[0].name : 'Belum ada file dipilih'" aria-describedby="file-name" />
                <p id="file-name" class="mt-1 text-sm text-zinc-500">Belum ada file dipilih</p>
            </div>

            <div>
                <label for="link" class="mb-1 block text-sm font-medium text-zinc-700">Link (opsional)</label>
                <input x-model.trim="link" type="url" id="link" name="link"
                    class="w-full rounded-lg border border-zinc-300 bg-white px-3 py-2 text-sm text-zinc-900 placeholder-zinc-400 focus:border-purple-500 focus:outline-none focus:ring-2 focus:ring-purple-500"
                    placeholder="https://example.com" />
            </div>

            <div class="flex items-center justify-end gap-2 pt-2">
                <button type="button" onclick="window.location='{{ route('admin.iklan') }}'"
                        class="rounded-lg border border-zinc-300 bg-white px-4 py-2 text-sm font-semibold text-zinc-700 hover:bg-zinc-50 hover:cursor-pointer">Batal</button>
                <button type="submit" class="rounded-lg bg-purple-600 px-4 py-2 text-sm font-semibold text-white hover:bg-purple-700">Simpan</button>
            </div>
        </form>
    </div>
</div>
@endsection
