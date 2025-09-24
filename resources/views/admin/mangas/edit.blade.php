@extends('admin.layouts.app')

@section('title', 'Edit Manga')

@section('content')
    <div x-data="{ 
        title:'{{ $mangaData->title }}', 
        description:'{{ $mangaData->description }}', 
        status:'{{ $mangaData->status }}',
        genresText:'{{ $mangaData->genres ? $mangaData->genres->pluck('name')->join(', ') : "" }}'
    }">
        <div class="container mx-auto px-4 pt-6 lg:px-8 lg:pt-8">
            <div class="flex flex-col gap-2 text-center sm:flex-row sm:items-center sm:justify-between sm:text-start">
                <div class="grow">
                    <h1 class="mb-1 text-xl font-bold text-zinc-500">Edit User</h1>
                    <h2 class="text-sm font-medium text-zinc-500">
                        Edit the details of the user
                    </h2>
                </div>
            </div>
            <form action="{{ route('admin.mangas.update', $mangaData->id) }}" enctype="multipart/form-data"
                 method="POST" class="space-y-4 py-5">
                @csrf
                @method('PUT')
                <div>
                    <label for="cover_image_input" class="mb-1 block text-sm font-medium text-zinc-700">Cover Image</label>
                    <div class="mb-2">
                        {{-- Gambar lama dari Cloudinary --}}
                        <x-cloudinary::image
                            public-id="{{ $mangaData->image_public_id }}"
                            alt="Cover Image for {{ $mangaData->title }}"
                            class="cloudinary_cover_preview h-auto w-full max-w-xs rounded-lg border-2 border-zinc-200 object-cover shadow-lg"
                            fallback-src="https://placehold.co/300x420/e2e8f0/94a3b8?text=No+Cover"
                        />

                        {{-- Preview upload (awal: hidden) --}}
                        <img src="" alt="Cover Preview" id="cover_image_preview"
                            class="hidden h-48 w-auto rounded-lg border border-zinc-300">
                    </div>

                    <input type="file" id="cover_image_input" name="cover_image" accept="image/*"
                        class="w-full rounded-lg border border-zinc-300 bg-white px-3 py-2 text-sm text-zinc-900
                                file:mr-4 file:rounded file:border-0 file:bg-purple-50 file:px-3 file:py-2
                                file:text-sm file:font-semibold file:text-purple-700 hover:file:bg-purple-100" />


                    <p class="mt-1 text-xs text-zinc-500">
                        Upload cover image baru untuk mengganti yang lama. Biarkan kosong untuk mempertahankan gambar saat ini.
                    </p>
                </div>
                <div>
                    <label for="title" class="mb-1 block text-sm font-medium text-zinc-700">Nama</label>
                    <input x-model.trim="title"type="text" id="title" name="title" required
                        class="w-full rounded-lg border border-zinc-300 bg-white px-3 py-2 text-sm text-zinc-900 placeholder-zinc-400 focus:border-purple-500 focus:outline-none focus:ring-2 focus:ring-purple-500"
                        placeholder="Masukkan Judul Manga" />
                </div>
                <div>
                    <label for="description" class="mb-1 block text-sm font-medium text-zinc-700">Deskripsi</label>
                    <textarea x-model.trim="description" id="description" name="description" rows="4"
                              class="w-full rounded-lg border border-zinc-300 bg-white px-3 py-2 text-sm text-zinc-900 placeholder-zinc-400 focus:border-purple-500 focus:outline-none focus:ring-2 focus:ring-purple-500"
                              placeholder="Ringkasan singkat manga"></textarea>
                </div>
                <div>
                    <label for="status" class="mb-1 block text-sm font-medium text-zinc-700">Status</label>
                    <select x-model.trim="status" id="status" name="status"
                            class="w-full rounded-lg border border-zinc-300 bg-white px-3 py-2 text-sm text-zinc-900 focus:border-purple-500 focus:outline-none focus:ring-2 focus:ring-purple-500">
                        <option value="Ongoing">Ongoing</option>
                        <option value="Completed">Completed</option>
                        <option value="Hiatus">Hiatus</option>
                    </select>
                </div>

                @isset($allGenres)
                <div>
                    <label for="genre_ids" class="mb-1 block text-sm font-medium text-zinc-700">Genres</label>
                    <select multiple id="genre_ids" name="genre_ids[]"
                            class="w-full rounded-lg border border-zinc-300 bg-white px-3 py-2 text-sm text-zinc-900 focus:border-purple-500 focus:outline-none focus:ring-2 focus:ring-purple-500">
                        @foreach ($allGenres as $g)
                            <option value="{{ $g->id }}" @selected($mangaData->genres->contains($g->id))>{{ $g->name }}</option>
                        @endforeach
                    </select>
                    <p class="mt-1 text-xs text-zinc-500">Tahan Ctrl/Cmd untuk memilih banyak genre.</p>
                </div>
                @else
                <div>
                    <label for="genres" class="mb-1 block text-sm font-medium text-zinc-700">Genres (opsional)</label>
                    <input x-model.trim="genresText" type="text" id="genres" name="genres"
                           class="w-full rounded-lg border border-zinc-300 bg-white px-3 py-2 text-sm text-zinc-900 placeholder-zinc-400 focus:border-purple-500 focus:outline-none focus:ring-2 focus:ring-purple-500"
                           placeholder="Pisahkan dengan koma, contoh: Action, Drama" />
                    <p class="mt-1 text-xs text-zinc-500">Jika daftar genre tersedia, akan muncul pilihan multipilih otomatis.</p>
                </div>
                @endisset

                <div class="flex items-center justify-end gap-2 pt-2">
                    <button type="button" onclick="window.location='{{ route('admin.mangas') }}'"
                            class="rounded-lg border border-zinc-300 bg-white px-4 py-2 text-sm font-semibold text-zinc-700 hover:bg-zinc-50 hover:cursor-pointer">
                        <a>Batal</a>
                    </button>
                    <button type="submit" onclick="loading(event, this)"
                            class="rounded-lg bg-purple-600 px-4 py-2 text-sm font-semibold text-white hover:bg-purple-700">Simpan</button>
                </div>
            </form>
        </div>
    </div>
@endsection

@section('scripts')
<script>
    document.getElementById('cover_image_input').addEventListener('change', function(event) {
        const file = event.target.files[0];
        const cloudinaryImg = document.querySelector('.cloudinary_cover_preview');
        const preview = document.getElementById('cover_image_preview');

        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                // tampilkan preview
                preview.src = e.target.result;
                preview.classList.remove('hidden');

                // sembunyikan cloudinary image
                if (cloudinaryImg) {
                    cloudinaryImg.classList.add('hidden');
                }
            };
            reader.readAsDataURL(file);
        }
    });
</script>
@endsection

