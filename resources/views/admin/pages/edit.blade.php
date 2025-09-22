@extends('admin.layouts.app')

@section('title', 'Edit Page')

@section('content')
<div x-data="{ preview: '{{ asset('storage/' . $page->image_url) }}' }">
    <div class="container mx-auto px-4 pt-6 lg:px-8 lg:pt-8">
        <div class="flex flex-col gap-2 text-center sm:flex-row sm:items-center sm:justify-between sm:text-start">
            <div class="grow">
                <h1 class="mb-1 text-xl font-bold text-zinc-500">Edit Page</h1>
                <h2 class="text-sm font-medium text-zinc-500">
                    Edit detail dan gambar page ini
                </h2>
            </div>
        </div>

        <form action="{{ route('admin.chapters.pages.update', $page->id) }}" method="POST" enctype="multipart/form-data" class="space-y-4 py-5">
            @csrf
            @method('PUT')

            <!-- Preview Gambar -->
            <div>
                <label class="mb-1 block text-sm font-medium text-zinc-700">Gambar Saat Ini</label>
                <img :src="preview" alt="Cover Preview" id="cover_image_preview"
                     class="mb-2 h-auto w-full max-w-xs rounded-lg border border-zinc-300 object-cover shadow-lg hidden"
                     x-show="preview" />
                <x-cloudinary::image
                    public-id="{{ $page->image_public_id }}"
                    alt="Cover Image for page {{ $page->page_number }}"
                    class="cloudinary_cover_preview h-auto w-full max-w-xs rounded-lg border-2 border-zinc-200 object-cover shadow-lg"
                    fallback-src="https://placehold.co/300x420/e2e8f0/94a3b8?text=No+Cover"
                />
            </div>

            <!-- Input Ganti Gambar -->
            <div>
                <label for="image" class="mb-1 block text-sm font-medium text-zinc-700">Ganti Gambar (opsional)</label>
                <input type="file" id="image_input" name="image" accept="image/*"
                       @change="preview = URL.createObjectURL($event.target.files[0])"
                       class="w-full rounded-lg border border-zinc-300 bg-white px-3 py-2 text-sm text-zinc-900 focus:border-purple-500 focus:outline-none focus:ring-2 focus:ring-purple-500"/>
                <p class="mt-1 text-xs text-zinc-500">Biarkan kosong jika tidak ingin mengubah gambar.</p>
            </div>

            <!-- Action Buttons -->
            <div class="flex items-center justify-end gap-2 pt-2">
                <button type="button" onclick="window.location='{{ route('admin.chapters.pages', $page->chapter_id) }}'"
                        class="rounded-lg border border-zinc-300 bg-white px-4 py-2 text-sm font-semibold text-zinc-700 hover:bg-zinc-50 hover:cursor-pointer">
                    Batal
                </button>
                <button type="submit"
                        class="rounded-lg bg-purple-600 px-4 py-2 text-sm font-semibold text-white hover:bg-purple-700">
                    Simpan
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

@section('scripts')
<script>
    document.getElementById('image_input').addEventListener('change', function(event) {
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
