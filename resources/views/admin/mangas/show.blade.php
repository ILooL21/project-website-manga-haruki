@extends('admin.layouts.app')

@section('title', 'Detail Manga')

@section('styles')
    <link rel="stylesheet" href="https://cdn.datatables.net/2.3.2/css/dataTables.dataTables.css" />
@endsection

@section('content')
    <div class="container mx-auto max-w-4xl py-6">
        <div >
            <a href="{{ route('admin.mangas') }}"
               class="rounded-lg border border-zinc-300 bg-white px-6 py-2 text-sm font-semibold text-zinc-700 shadow-sm hover:bg-zinc-50">
                Kembali
            </a>
        </div>
        
        <!-- Bagian Atas: Judul dan Gambar -->
        <div class="text-center">
            <h1 class="mb-4 text-3xl font-bold text-zinc-800 md:text-4xl">
                {{ $mangaData->title }}
            </h1>
            <div class="mb-6 flex justify-center">
                <x-cloudinary::image
                    public-id="{{ $mangaData->image_public_id }}"
                    alt="Cover Image for {{ $mangaData->title }}"
                    class="h-auto w-full max-w-xs rounded-lg border-2 border-zinc-200 object-cover shadow-lg"
                    fallback-src="https://placehold.co/300x420/e2e8f0/94a3b8?text=No+Cover"
                />
            </div>
        </div>

        <!-- Bagian Tengah: Metadata (Status dan Genre) -->
        <div class="mt-6 space-y-4 text-start">
            <div>
                <h3 class="text-sm font-semibold uppercase tracking-wider text-zinc-500">Status</h3>
                <p class="mt-1 text-lg font-medium text-zinc-800">{{ $mangaData->status }}</p>
            </div>
            <div>
                <h3 class="text-sm font-semibold uppercase tracking-wider text-zinc-500">Genres</h3>
                @if($mangaData->genres->isNotEmpty())
                    @foreach ($mangaData->genres as $genre)
                        <span class="inline-block bg-gray-200 text-gray-800 text-xs font-medium mr-1 px-2.5 py-0.5 rounded">{{ $genre->name }}</span>
                    @endforeach
                @else
                    <p class="mt-1 text-lg text-zinc-500">Belum ada Genre</p>
                @endif
            </div>
        </div>

        <!-- Bagian Bawah: Deskripsi -->
        <div class="mt-8 pb-8">
            <h3 class="text-start text-sm font-semibold uppercase tracking-wider text-zinc-500">Deskripsi</h3>
            <div class="prose prose-zinc mt-2 max-w-none text-justify text-sm text-zinc-900">
                <p>{{ $mangaData->description ?? 'Tidak ada deskripsi.' }}</p>
            </div>
        </div>

        <div x-data="{ open:false, title:'', chapter_number:'', release_date:'', init(){
            // Suggest next chapter number based on current rows
            this.chapter_number = {{ $lastChapter }};
        }, reset(){ this.title=''; this.release_date=''; this.init(); } }">
            <!-- Page Heading -->
            <div class="container">
                <div class="flex flex-col gap-2 text-center sm:flex-row sm:items-center sm:justify-between sm:text-start">
                    <div class="grow">
                        <h1 class="mb-1 text-xl font-bold text-zinc-500">List Chapters</h1>
                        <h2 class="text-sm font-medium text-zinc-500">
                            A detailed list of all chapters for this manga.
                        </h2>
                    </div>
                    <div class="flex justify-center sm:justify-end">
                        <button type="button" @click="open = true; reset()"
                                class="inline-flex items-center gap-2 rounded-lg bg-purple-600 px-4 py-2 text-sm font-semibold text-white hover:bg-purple-700 active:bg-purple-800">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="size-5">
                                <path d="M10 3.5a.75.75 0 01.75.75v5h5a.75.75 0 010 1.5h-5v5a.75.75 0 01-1.5 0v-5h-5a.75.75 0 010-1.5h5v-5A.75.75 0 0110 3.5z" />
                            </svg>
                            <span>Tambah Chapter</span>
                        </button>
                    </div>
                </div>
            </div>
            <!-- END Page Heading -->

            <!-- Modal: Add Chapter -->
            <div x-cloak x-show="open" class="fixed inset-0 z-50 flex items-center justify-center" @keydown.escape.window="open=false">
                <div class="absolute inset-0 bg-black/50" @click="open=false"></div>
                <div class="relative z-10 w-full max-w-xl rounded-lg bg-white text-zinc-900 shadow-xl">
                    <div class="flex items-center justify-between border-b border-zinc-100 px-5 py-3">
                        <h3 class="font-semibold">Tambah Chapter</h3>
                        <button type="button" @click="open=false" class="rounded p-1 hover:bg-zinc-100">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="size-5">
                                <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd" />
                            </svg>
                        </button>
                    </div>
                    <form action="{{ route('admin.chapters.store', $mangaData->id) }}" method="POST" class="space-y-4 p-5">
                        @csrf
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
                                <input type="date" id="release_date" name="release_date"
                                    class="w-full rounded-lg border border-zinc-300 bg-white px-3 py-2 text-sm text-zinc-900 placeholder-zinc-400 focus:border-purple-500 focus:outline-none focus:ring-2 focus:ring-purple-500" />
                                <p class="mt-1 text-xs text-zinc-500">Gunakan input tanggal bawaan browser (format dapat berbeda per perangkat).</p>
                            </div>
                        </div>
                        <div class="flex items-center justify-end gap-2 pt-2">
                            <button type="button" @click="open=false"
                                    class="rounded-lg border border-zinc-300 bg-white px-4 py-2 text-sm font-semibold text-zinc-700 hover:bg-zinc-50">Batal</button>
                            <button type="submit"
                                    class="rounded-lg bg-purple-600 px-4 py-2 text-sm font-semibold text-white hover:bg-purple-700">Simpan</button>
                        </div>
                    </form>
                </div>
            </div>
            <!-- END Modal: Add Chapter -->

            <!-- Page Section -->
            <div class="container py-4 lg:py-8 xl:max-w-7xl">
                <div class="flex flex-col rounded-lg border border-zinc-200 bg-white">
                    <div class="p-5">
                        <!-- Responsive Table Container -->
                        <div class="min-w-full overflow-x-auto rounded-sm text-black">
                            <!-- Chapters Table -->
                            <table id="myTable" class="min-w-full align-middle text-sm">
                                <thead>
                                    <tr class="border-b-2 border-zinc-100">
                                        <th class="px-3 py-2 text-start text-sm font-semibold tracking-wider text-zinc-700 uppercase">No</th>
                                        <th class="px-3 py-2 text-start text-sm font-semibold tracking-wider text-zinc-700 uppercase">Chapter Number</th>
                                        <th class="px-3 py-2 text-start text-sm font-semibold tracking-wider text-zinc-700 uppercase">Title</th>
                                        <th class="px-3 py-2 text-start text-sm font-semibold tracking-wider text-zinc-700 uppercase">Release Date</th>
                                        <th class="px-3 py-2 text-start text-sm font-semibold tracking-wider text-zinc-700 uppercase">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($chapters as $chapter)
                                        <tr class="border-b border-zinc-100 hover:bg-zinc-50">
                                            <td class="p-3 text-start font-semibold text-zinc-600">{{ $loop->iteration }}</td>
                                            <td class="p-3 text-start font-semibold text-zinc-600">{{ $chapter->chapter_number }}</td>
                                            <td class="p-3 text-start font-semibold text-zinc-600">{{ $chapter->title }}</td>
                                            <td class="p-3 text-start text-zinc-600">{{ $chapter->release_date }}</td>
                                            <td class="p-3 text-start">
                                                <div class="flex items-center space-x-2">
                                                    <a href="{{ route('admin.chapters.edit', $chapter->id) }}" class="text-blue-500 hover:underline" title="Edit Chapter">
                                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                                            <path d="M17.414 2.586a2 2 0 00-2.828 0L7 10.172V13h2.828l7.586-7.586a2 2 0 000-2.828z" />
                                                    <path fill-rule="evenodd" d="M2 6a2 2 0 012-2h4a1 1 0 010 2H4v10h10v-4a1 1 0 112 0v4a2 2 0 01-2 2H4a2 2 0 01-2-2V6z" clip-rule="evenodd" />
                                                        </svg>
                                                    </a>
                                                    <form action="{{ route('admin.chapters.destroy', $chapter->id) }}" method="POST" class="inline" title="Delete Chapter" onsubmit="return confirm('Are you sure you want to delete this chapter?');">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="text-red-500 hover:underline">
                                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                                                <path fill-rule="evenodd" d="M6 3a1 1 0 00-1 1v1H3a1 1 0 000 2h1v9a2 2 0 002 2h8a2 2 0 002-2V7h1a1 1 0 100-2h-2V4a1 1 0 00-1-1H6zm3 4a1 1 0 012 0v6a1 1 0 01-2 0V7zm4 0a1 1 0 012 0v6a1 1 0 01-2 0V7z" clip-rule="evenodd" />
                                                            </svg>
                                                        </button>
                                                    </form>
                                                    <a href="{{ route('admin.chapters.pages', $chapter->id) }}" class="text-green-500 hover:underline" title="View Pages">
                                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                                            <path d="M2 5a2 2 0 012-2h12a2 2 0 012 2v10a2 2 0 01-2 2H4a2 2 0 01-2-2V5zm2 0v10h12V5H4z" />
                                                        </svg>
                                                    </a>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                            <!-- END Chapters Table -->
                        </div>
                    </div>
                </div>
            </div>
            <!-- END Page Section -->
        </div>
    </div>
    @if (session('status'))
    <div class="fixed bottom-5 right-5 z-50">
        <div class="rounded-lg bg-green-100 p-4 text-green-700 shadow-lg">
            <p><strong>Status:</strong> {{ session('status') }}</p>
            <p>{{ session('message') }}</p>
        </div>
    </div>
    @endif
@endsection

@section('scripts')
<script src="https://code.jquery.com/jquery-3.7.1.js"></script>
<script src="https://cdn.datatables.net/2.3.2/js/dataTables.js"></script>
<script>
    $(document).ready(function() {
            $('#myTable').DataTable({
                responsive: true,
                "language": {
                   "emptyTable": "Tidak ada data chapter ditemukan.",
                   "zeroRecords": "Tidak ada chapter yang cocok ditemukan."
                },
                lengthMenu: [5, 10, 25, 100, -1]
            });
        });
</script>
@endsection
