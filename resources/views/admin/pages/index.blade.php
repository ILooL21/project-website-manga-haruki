@extends('admin.layouts.app')

@section('title', 'Tabel Data Page')

@section('styles')
    <link rel="stylesheet" href="https://cdn.datatables.net/2.3.2/css/dataTables.dataTables.css" />
    <link rel="stylesheet" href="https://cdn.datatables.net/rowreorder/1.5.0/css/rowReorder.dataTables.css" />
    <style>
        /* Menyembunyikan elemen dengan x-cloak sampai Alpine.js selesai dimuat */
        [x-cloak] { display: none !important; }
    </style>
@endsection

@section('content')

<div x-data="{ open:false}">
    <!-- Page Heading -->
    <div class="container mx-auto px-4 pt-6 lg:px-8 lg:pt-8">
        <div class="flex flex-col gap-2 text-center sm:flex-row sm:items-center sm:justify-between sm:text-start">
            <div class="grow">
                <h1 class="mb-1 text-xl font-bold text-zinc-500">Pages of Chapter {{ $chapter->chapter_number }}</h1>
                <h2 class="text-sm font-medium text-zinc-500">
                    A detailed list of all pages for this chapter.
                </h2>
            </div>
        </div>
    </div>
    <!-- END Page Heading -->

    <!-- Modal Tambah Pages -->
    <div x-cloak x-show="open" class="fixed inset-0 z-50 flex items-center justify-center" @keydown.escape.window="open=false">
        <!-- overlay -->
        <div class="absolute inset-0 bg-black/50" @click="open=false"></div>

        <!-- modal box -->
        <div class="relative z-10 w-full max-w-md rounded-lg bg-white text-zinc-900 shadow-xl">
            <!-- header -->
            <div class="flex items-center justify-between border-b border-zinc-100 px-5 py-3">
                <h3 class="font-semibold">Tambah Pages</h3>
                <button type="button" @click="open=false" class="rounded p-1 hover:bg-zinc-100">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="size-5">
                        <path fill-rule="evenodd"
                            d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z"
                            clip-rule="evenodd"/>
                    </svg>
                </button>
            </div>

            <!-- form -->
            <form action="{{ route('admin.chapters.pages.store', $chapter->id) }}" method="POST" enctype="multipart/form-data" class="space-y-4 p-5">
                @csrf
                <div>
                    <label for="image" class="mb-1 block text-sm font-medium text-zinc-700">Upload Gambar</label>
                    <input type="file" id="image" name="images[]" accept="image/*" required multiple
                        class="w-full rounded-lg border border-zinc-300 bg-white px-3 py-2 text-sm text-zinc-900 focus:border-purple-500 focus:outline-none focus:ring-2 focus:ring-purple-500"/>
                    <p class="mt-1 text-xs text-zinc-500">Pilih file gambar untuk halaman baru.</p>
                </div>

                <div class="flex items-center justify-end gap-2 pt-2">
                    <button type="button" @click="open=false"
                            class="rounded-lg border border-zinc-300 bg-white px-4 py-2 text-sm font-semibold text-zinc-700 hover:bg-zinc-50">Batal</button>
                    <button type="submit" onclick="loading(event, this)"
                            class="rounded-lg bg-purple-600 px-4 py-2 text-sm font-semibold text-white hover:bg-purple-700">Simpan</button>
                </div>
            </form>
        </div>
    </div>
    <!-- END Modal Tambah Pages -->


    <!-- Page Section -->
    <div class="container mx-auto p-4 lg:p-8 xl:max-w-7xl">
        <div class="flex flex-col rounded-lg border border-zinc-200 bg-white">
            <div class="p-5">
                <!-- Toolbar -->
                <div class="mb-4 flex items-center justify-between">
                    <p class="text-sm text-zinc-500">Tarik ikon di kolom kiri untuk mengatur urutan. Klik "Simpan Urutan" untuk melihat payload yang akan dikirim.</p>
                    <div class="flex gap-2">
                        <!-- Tombol Tambah -->
                        <button @click="open=true" type="button" class="inline-flex items-center gap-2 rounded-lg bg-green-600 px-4 py-2 text-sm font-semibold text-white hover:bg-green-700">
                            <svg xmlns="http://www.w3.org/2000/svg" class="size-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                            </svg>
                            <span>Tambah Pages</span>
                        </button>

                        <!-- Tombol Simpan Urutan -->
                        <button id="btnSaveOrder" type="button" class="inline-flex items-center gap-2 rounded-lg bg-purple-600 px-4 py-2 text-sm font-semibold text-white hover:bg-purple-700">
                            <svg xmlns="http://www.w3.org/2000/svg" class="size-5" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M3.5 10a6.5 6.5 0 1113 0 6.5 6.5 0 01-13 0zm9.78-2.28a.75.75 0 00-1.06-1.06L9 9.88 7.78 8.66a.75.75 0 10-1.06 1.06l1.75 1.75a.75.75 0 001.06 0l3.75-3.75z" clip-rule="evenodd" />
                            </svg>
                            <span>Simpan Urutan</span>
                        </button>
                    </div>
                </div>

                <div id="orderPreview" class="mb-4 hidden rounded-md border border-zinc-200 bg-zinc-50 p-3 text-xs text-zinc-700"></div>
                <!-- Responsive Table Container -->
                <div class="min-w-full overflow-x-auto rounded-sm text-black">
                    <!-- Pages Table -->
                    <table id="pagesTable" class="min-w-full align-middle text-sm">
                        <thead>
                            <tr class="border-b-2 border-zinc-100">
                                <th class="w-12 px-3 py-2 text-start text-sm font-semibold tracking-wider text-zinc-700 uppercase">Urut</th>
                                <th class="px-3 py-2 text-start text-sm font-semibold tracking-wider text-zinc-700 uppercase">Page Number</th>
                                <th class="px-3 py-2 text-start text-sm font-semibold tracking-wider text-zinc-700 uppercase">Image</th>
                                <th class="px-3 py-2 text-start text-sm font-semibold tracking-wider text-zinc-700 uppercase">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($pages as $page)
                                <tr class="border-b border-zinc-100 hover:bg-zinc-50" data-page-id="{{ $page->id }}">
                                    <td class="reorder-handle p-3 text-start text-zinc-400 cursor-move">
                                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="size-5">
                                            <path fill-rule="evenodd" d="M8.25 6a.75.75 0 01.75-.75h6a.75.75 0 010 1.5h-6A.75.75 0 018.25 6zm0 6a.75.75 0 01.75-.75h6a.75.75 0 010 1.5h-6a.75.75 0 01-.75-.75zm0 6a.75.75 0 01.75-.75h6a.75.75 0 010 1.5h-6a.75.75 0 01-.75-.75z" clip-rule="evenodd" />
                                        </svg>
                                    </td>
                                    <td class="p-3 text-start font-semibold text-zinc-600 page-number-cell">{{ $page->page_number }}</td>
                                    <td class="p-3 text-start">
                                        <x-cloudinary::image
                                            public-id="{{ $page->image_public_id }}"
                                            alt="Cover Image for page {{ $page->page_number }}"
                                            class="cloudinary_cover_preview h-auto w-full max-w-xs rounded-lg border-2 border-zinc-200 object-cover shadow-lg"
                                            fallback-src="https://placehold.co/300x420/e2e8f0/94a3b8?text=No+Cover"
                                        />
                                    </td>
                                    <td class="p-3 text-start">
                                        <div class="flex items-center space-x-2">
                                            <button class="text-blue-500 hover:text-blue-700 hover:cursor-pointer" title="Edit Genre">
                                                <a href="{{ route('admin.chapters.pages.edit', $page->id) }}">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                                        <path d="M17.414 2.586a2 2 0 00-2.828 0L7 10.172V13h2.828l7.586-7.586a2 2 0 000-2.828z" />
                                                        <path fill-rule="evenodd" d="M2 6a2 2 0 012-2h4a1 1 0 010 2H4v10h10v-4a1 1 0 112 0v4a2 2 0 01-2 2H4a2 2 0 01-2-2V6z" clip-rule="evenodd" />
                                                    </svg>
                                                </a>
                                            </button>
                                            <form action="{{ route('admin.chapters.pages.delete', $page->id) }}" method="POST" class="inline" title="Delete Page" onsubmit="return confirm('Apakah Anda yakin ingin menghapus halaman ini?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" onclick="loading(this, true)" class="text-red-500 hover:text-red-700">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5  hover:cursor-pointer" viewBox="0 0 20 20" fill="currentColor">
                                                        <path fill-rule="evenodd" d="M6 3a1 1 0 00-1 1v1H3a1 1 0 000 2h1v9a2 2 0 002 2h8a2 2 0 002-2V7h1a1 1 0 100-2h-2V4a1 1 0 00-1-1H6zm3 4a1 1 0 012 0v6a1 1 0 01-2 0V7zm4 0a1 1 0 012 0v6a1 1 0 01-2 0V7z" clip-rule="evenodd" />
                                                    </svg>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                    <!-- END Pages Table -->
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
    <!-- jQuery and DataTables JS -->
<script src="https://code.jquery.com/jquery-3.7.1.js"></script>
<script src="https://cdn.datatables.net/2.3.2/js/dataTables.js"></script>
<script src="https://cdn.datatables.net/rowreorder/1.5.0/js/dataTables.rowReorder.js"></script>
<script>
    $(document).ready(function() {
        const table = $('#pagesTable').DataTable({
            responsive: false,
            paging: false,
            searching: false,
            info: false,
            rowReorder: {
                selector: 'td.reorder-handle',
                update: false // don't attempt to write back to a data source mid-drag
            },
            ordering: false,
            columnDefs: [
                { targets: [0, 3], orderable: false },
            ],
            "language": {
                "emptyTable": "Tidak ada data pages ditemukan.",
            },
        });

        function renumberRows() {
            $('#pagesTable tbody tr[data-page-id]').each(function(index) {
                $(this).find('td.page-number-cell').text(index + 1);
            });
        }

        // Defer renumbering until after DataTables finishes DOM updates
        table.on('row-reorder', function() {
            requestAnimationFrame(renumberRows);
        });

        // Initial numbering sync (in case server numbers differ)
        renumberRows();

        // Build and show a preview payload and send it to server when clicking Save Order
        $('#btnSaveOrder').on('click', function() {
            const ordered = [];
            // Read DOM order directly; RowReorder rearranges DOM even with update:false
            $('#pagesTable tbody tr[data-page-id]').each(function(index) {
                const id = $(this).data('page-id');
                if (id !== undefined && id !== null && id !== '') {
                    ordered.push({ id: Number(id), position: index + 1 });
                }
            });
            const $box = $('#orderPreview');
            $box.removeClass('hidden').text('Payload urutan (contoh untuk POST): ' + JSON.stringify({ order: ordered }, null, 2));

            // Send to backend
            $.ajax({
                url: '{{ route('admin.chapters.pages.reorder', $chapter->id) }}',
                method: 'PUT',
                data: {
                    _token: '{{ csrf_token() }}',
                    order: ordered
                }
            })
            .done(function(res){
                $box.append('\nStatus: ' + (res.message || 'OK'));
                // Refresh from server to confirm DB persistence
                setTimeout(function(){ location.reload(); }, 300);
            })
            .fail(function(xhr){
                const msg = (xhr.responseJSON && xhr.responseJSON.message) ? xhr.responseJSON.message : 'Gagal menyimpan urutan';
                $box.append('\nError: ' + msg);
            });
        });
    });
</script>
@endsection