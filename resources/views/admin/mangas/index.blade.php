@extends('admin.layouts.app')

@section('title', 'Tabel Data Manga')

@section('styles')
    <link rel="stylesheet" href="https://cdn.datatables.net/2.3.2/css/dataTables.dataTables.css" />
    <style>
        /* Menyembunyikan elemen dengan x-cloak sampai Alpine.js selesai dimuat */
        [x-cloak] {
            display: none !important;
        }
    </style>
@endsection

@section('content')

    <div
        x-data='{ open: {{ old() ? 'true' : 'false' }}, title: "{{ old('title', '') }}", description: "{{ old('description', '') }}", status: "{{ old('status', 'Ongoing') }}", genresText: "{{ old('genres', '') }}", authorName: "{{ old('author_name', '') }}", reset(){ this.title=""; this.description=""; this.status="Ongoing"; this.genresText=""; this.authorName = ""; } }'>
        <!-- Page Heading -->
        <div class="container mx-auto px-4 pt-6 lg:px-8 lg:pt-8">
            <div class="flex flex-col gap-2 text-center sm:flex-row sm:items-center sm:justify-between sm:text-start">
                <div class="grow">
                    <h1 class="mb-1 text-xl font-bold text-zinc-500">Manga List</h1>
                    <h2 class="text-sm font-medium text-zinc-500">
                        A detailed list of all mangas in the system.
                    </h2>
                </div>
                <div class="flex justify-center sm:justify-end">
                    <button type="button" @click="open = true; reset()"
                        class="inline-flex items-center gap-2 rounded-lg bg-purple-600 px-4 py-2 text-sm font-semibold text-white hover:bg-purple-700 active:bg-purple-800">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="size-5">
                            <path
                                d="M10 3.5a.75.75 0 01.75.75v5h5a.75.75 0 010 1.5h-5v5a.75.75 0 01-1.5 0v-5h-5a.75.75 0 010-1.5h5v-5A.75.75 0 0110 3.5z" />
                        </svg>
                        <span>Tambah Manga</span>
                    </button>
                </div>
            </div>
        </div>
        <!-- END Page Heading -->

        <!-- Modal: Add Manga -->
        <div x-cloak x-show="open" class="fixed inset-0 z-50 flex items-center justify-center"
            @keydown.escape.window="open=false">
            <div class="absolute inset-0 bg-black/50" @click="open=false"></div>
            <div class="relative z-10 w-full max-w-xl rounded-lg bg-white text-zinc-900 shadow-xl">
                <div class="flex items-center justify-between border-b border-zinc-100 px-5 py-3">
                    <h3 class="font-semibold">Tambah Manga</h3>
                    <button type="button" @click="open=false" class="rounded p-1 hover:bg-zinc-100">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="size-5">
                            <path fill-rule="evenodd"
                                d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z"
                                clip-rule="evenodd" />
                        </svg>
                    </button>
                </div>
                <form action="{{ route('admin.mangas.store') }}" method="POST" enctype="multipart/form-data"
                    class="space-y-4 p-5">
                    @csrf
                    {{-- show validation errors --}}
                    @if ($errors->any())
                        <div class="mb-2 rounded border border-red-200 bg-red-50 p-3 text-red-700">
                            <strong>Terjadi kesalahan:</strong>
                            <ul class="mt-1 list-disc list-inside">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    {{-- show server error if any --}}
                    @if(session('server_error'))
                        <div class="mb-2 rounded border border-red-200 bg-red-50 p-3 text-red-700">
                            <strong>Server error:</strong>
                            <div class="truncate">{{ session('server_error') }}</div>
                        </div>
                    @endif
                    <div>
                        <label for="title" class="mb-1 block text-sm font-medium text-zinc-700">Judul</label>
                        <input x-model.trim="title" type="text" id="title" name="title" required
                            class="w-full rounded-lg border border-zinc-300 bg-white px-3 py-2 text-sm text-zinc-900 placeholder-zinc-400 focus:border-purple-500 focus:outline-none focus:ring-2 focus:ring-purple-500"
                            placeholder="Misal: Mercenary Enrollment" />
                    </div>
                    <div>
                        <label for="description" class="mb-1 block text-sm font-medium text-zinc-700">Deskripsi</label>
                        <textarea x-model.trim="description" id="description" name="description" rows="4"
                            class="w-full rounded-lg border border-zinc-300 bg-white px-3 py-2 text-sm text-zinc-900 placeholder-zinc-400 focus:border-purple-500 focus:outline-none focus:ring-2 focus:ring-purple-500"
                            placeholder="Ringkasan singkat manga"></textarea>
                    </div>
                    <div>
                        <label for="author_name" class="mb-1 block text-sm font-medium text-zinc-700">Nama Author</label>
                        <input x-model.trim="authorName" type="text" id="author_name" name="author_name" required
                            class="w-full rounded-lg border border-zinc-300 bg-white px-3 py-2 text-sm text-zinc-900 placeholder-zinc-400 focus:border-purple-500 focus:outline-none focus:ring-2 focus:ring-purple-500"
                            placeholder="Misal: Mangos" />
                    </div>
                    <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                        <div>
                            <label for="status" class="mb-1 block text-sm font-medium text-zinc-700">Status</label>
                            <select id="status" name="status" x-model="status"
                                class="w-full rounded-lg border border-zinc-300 bg-white px-3 py-2 text-sm text-zinc-900 focus:border-purple-500 focus:outline-none focus:ring-2 focus:ring-purple-500">
                                <option value="Ongoing">Ongoing</option>
                                <option value="Completed">Completed</option>
                                <option value="Hiatus">Hiatus</option>
                            </select>
                        </div>
                        <div>
                            <label for="cover_image" class="mb-1 block text-sm font-medium text-zinc-700">Cover
                                Image</label>
                            <input type="file" id="cover_image" name="cover_image" accept="image/*"
                                class="w-full rounded-lg border border-zinc-300 bg-white px-3 py-2 text-sm text-zinc-900 file:mr-4 file:rounded file:border-0 file:bg-purple-50 file:px-3 file:py-2 file:text-sm file:font-semibold file:text-purple-700 hover:file:bg-purple-100" />
                        </div>
                    </div>

                    @isset($allGenres)
                        <div>
                            <label for="genre_ids" class="mb-1 block text-sm font-medium text-zinc-700">Genres</label>
                            <select multiple id="genre_ids" name="genre_ids[]"
                                class="w-full rounded-lg border border-zinc-300 bg-white px-3 py-2 text-sm text-zinc-900 focus:border-purple-500 focus:outline-none focus:ring-2 focus:ring-purple-500">
                                @foreach ($allGenres as $g)
                                    <option value="{{ $g->id }}">{{ $g->name }}</option>
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
                            <p class="mt-1 text-xs text-zinc-500">Jika daftar genre tersedia, akan muncul pilihan multipilih
                                otomatis.</p>
                        </div>
                    @endisset

                    <div class="flex items-center justify-end gap-2 pt-2">
                        <button type="button" @click="open=false"
                            class="rounded-lg border border-zinc-300 bg-white px-4 py-2 text-sm font-semibold text-zinc-700 hover:bg-zinc-50">Batal</button>
                        <button type="submit" onclick="loading(event, this)"
                            class="rounded-lg bg-purple-600 px-4 py-2 text-sm font-semibold text-white hover:bg-purple-700">Simpan</button>
                    </div>
                </form>
            </div>
        </div>
        <!-- END Modal: Add Manga -->

        <!-- Page Section -->
        <div class="container mx-auto p-4 lg:p-8 xl:max-w-7xl">
            <div class="flex flex-col rounded-lg border border-zinc-200 bg-white">
                <div
                    class="flex flex-col items-center justify-between gap-4 border-b border-zinc-100 p-5 text-center sm:flex-row sm:text-start">
                    <div>
                        <h3 class="text-sm font-medium text-zinc-600">
                            Cari Manga.
                        </h3>
                    </div>
                </div>
                <div class="p-5">
                    <!-- Responsive Table Container -->
                    <div class="min-w-full overflow-x-auto rounded-sm text-black">
                        <!-- Manga Table -->
                        <table id="myTable" class="min-w-full align-middle text-sm">
                            <thead>
                                <tr class="border-b-2 border-zinc-100">
                                    <th
                                        class="px-3 py-2 text-start text-sm font-semibold tracking-wider text-zinc-700 uppercase">
                                        No</th>
                                    <th
                                        class="px-3 py-2 text-start text-sm font-semibold tracking-wider text-zinc-700 uppercase">
                                        Title</th>
                                    <th
                                        class="px-3 py-2 text-start text-sm font-semibold tracking-wider text-zinc-700 uppercase">
                                        Chapters</th>
                                    <th
                                        class="px-3 py-2 text-start text-sm font-semibold tracking-wider text-zinc-700 uppercase">
                                        Author</th>
                                    <th
                                        class="px-3 py-2 text-start text-sm font-semibold tracking-wider text-zinc-700 uppercase">
                                        Genre</th>
                                    <th
                                        class="px-3 py-2 text-start text-sm font-semibold tracking-wider text-zinc-700 uppercase">
                                        Status</th>
                                    <th
                                        class="px-3 py-2 text-start text-sm font-semibold tracking-wider text-zinc-700 uppercase">
                                        Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($mangas as $manga)
                                    <tr class="border-b border-zinc-100 hover:bg-zinc-50">
                                        <td class="p-3 text-start font-semibold text-zinc-600">{{ $loop->iteration }}</td>
                                        <td class="p-3 text-start font-semibold text-zinc-600">{{ $manga->title }}</td>
                                        <td class="p-3 text-start font-semibold text-zinc-600">{{ $manga->chapters_count }}
                                        </td>
                                        <td class="p-3 text-start font-semibold text-zinc-600">
                                            {{ $manga->author_name ?? '-' }}</td>
                                        <td class="p-3 text-start">
                                            @foreach ($manga->genres as $genre)
                                                <span
                                                    class="inline-block bg-gray-200 text-gray-800 text-xs font-medium mr-1 px-2.5 py-0.5 rounded">{{ $genre->name }}</span>
                                            @endforeach
                                        </td>
                                        <td class="p-3 text-start text-zinc-600">{{ $manga->status }}</td>
                                        <td class="p-3 text-start">
                                            <div class="flex items-center space-x-2">
                                                <a href="{{ route('admin.mangas.show', $manga->id) }}"
                                                    class="text-green-500 hover:underline" title="View Detail Manga">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5"
                                                        viewBox="0 0 20 20" fill="currentColor">
                                                        <path
                                                            d="M10 3C5 3 1.73 7.11 1.05 10.29a1 1 0 000 .42C1.73 12.89 5 17 10 17s8.27-4.11 8.95-7.29a1 1 0 000-.42C18.27 7.11 15 3 10 3zm0 12c-3.87 0-7.19-3.13-7.94-6C2.81 6.13 6.13 3 10 3s7.19 3.13 7.94 6c-.75 2.87-4.07 6-7.94 6zm0-9a3 3 0 100 6 3 3 0 000-6zm0 5a2 2 0 110-4 2 2 0 010 4z" />
                                                    </svg>
                                                </a>
                                                <a href="{{ route('admin.mangas.edit', $manga->id) }}"
                                                    class="text-blue-500 hover:underline" title="Edit Manga">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5"
                                                        viewBox="0 0 20 20" fill="currentColor">
                                                        <path
                                                            d="M17.414 2.586a2 2 0 00-2.828 0L7 10.172V13h2.828l7.586-7.586a2 2 0 000-2.828z" />
                                                        <path fill-rule="evenodd"
                                                            d="M2 6a2 2 0 012-2h4a1 1 0 010 2H4v10h10v-4a1 1 0 112 0v4a2 2 0 01-2 2H4a2 2 0 01-2-2V6z"
                                                            clip-rule="evenodd" />
                                                    </svg>
                                                </a>
                                                <form action="{{ route('admin.mangas.destroy', $manga->id) }}"
                                                    method="POST" class="inline" title="Delete Manga">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" onclick="loading(event, this, 'Apakah Anda yakin ingin menghapus manga ini?')"
                                                        class="text-red-500 hover:underline hover:cursor-pointer">
                                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5"
                                                            viewBox="0 0 20 20" fill="currentColor">
                                                            <path fill-rule="evenodd"
                                                                d="M6 3a1 1 0 00-1 1v1H3a1 1 0 000 2h1v9a2 2 0 002 2h8a2 2 0 002-2V7h1a1 1 0 100-2h-2V4a1 1 0 00-1-1H6zm3 4a1 1 0 012 0v6a1 1 0 01-2 0V7zm4 0a1 1 0 012 0v6a1 1 0 01-2 0V7z"
                                                                clip-rule="evenodd" />
                                                        </svg>
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                        <!-- END Manga Table -->
                    </div>
                </div>
            </div>
        </div>
        <!-- END Page Section -->
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
                    "emptyTable": "Tidak ada data manga ditemukan.",
                    "zeroRecords": "Tidak ada manga yang cocok ditemukan."
                },
                lengthMenu: [5, 10, 25, 100, -1]
            });
        });
    </script>
@endsection
