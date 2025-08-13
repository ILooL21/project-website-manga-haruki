@extends('dashboard-untuk-ilul.layouts.main')

<link rel="stylesheet" href="https://cdn.datatables.net/2.3.2/css/dataTables.dataTables.css" />

@section('content')
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

    <!-- Page Section -->
    <div class="container mx-auto p-4 lg:p-8 xl:max-w-7xl">
        <div class="flex flex-col rounded-lg border border-zinc-200 bg-white">
            <div class="p-5">
                <!-- Toolbar -->
                <div class="mb-4 flex items-center justify-between">
                    <p class="text-sm text-zinc-500">Tarik ikon di kolom kiri untuk mengatur urutan. Klik "Simpan Urutan" untuk melihat payload yang akan dikirim.</p>
                    <button id="btnSaveOrder" type="button" class="inline-flex items-center gap-2 rounded-lg bg-purple-600 px-4 py-2 text-sm font-semibold text-white hover:bg-purple-700">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="size-5">
                            <path fill-rule="evenodd" d="M3.5 10a6.5 6.5 0 1113 0 6.5 6.5 0 01-13 0zm9.78-2.28a.75.75 0 00-1.06-1.06L9 9.88 7.78 8.66a.75.75 0 10-1.06 1.06l1.75 1.75a.75.75 0 001.06 0l3.75-3.75z" clip-rule="evenodd" />
                        </svg>
                        <span>Simpan Urutan</span>
                    </button>
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
                                        <img src="{{ asset('images/' . $page->image_url) }}" alt="Page {{ $page->page_number }}" class="h-auto w-32 rounded">
                                    </td>
                                    <td class="p-3 text-start">
                                        <div class="flex items-center space-x-2">
                                            <button type="button" class="rounded border border-zinc-300 px-2 py-1 text-xs text-zinc-700 hover:bg-zinc-50" title="Edit">
                                                Edit
                                            </button>
                                            <button type="button" class="rounded border border-red-300 px-2 py-1 text-xs text-red-600 hover:bg-red-50" title="Delete">
                                                Delete
                                            </button>
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
@endsection

<!-- jQuery and DataTables JS -->
<script src="https://code.jquery.com/jquery-3.7.1.js"></script>
<script src="https://cdn.datatables.net/2.3.2/js/dataTables.js"></script>
<!-- RowReorder extension -->
<link rel="stylesheet" href="https://cdn.datatables.net/rowreorder/1.5.0/css/rowReorder.dataTables.css" />
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
            ]
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
                url: '{{ route('chapter.pages.reorder', $chapter->id) }}',
                method: 'POST',
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