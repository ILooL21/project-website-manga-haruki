@extends('dashboard-untuk-ilul.layouts.main')

<link rel="stylesheet" href="https://cdn.datatables.net/2.3.2/css/dataTables.dataTables.css" />

@section('content')
<div x-data="{ 
    open:false, 
    name:'', 
    slug:'', 
    makeSlug(){ 
        this.slug = String(this.name || '')
            .toLowerCase()
            .trim()
            .replace(/[^a-z0-9\s-]/g,'')
            .replace(/\s+/g,'-')
            .replace(/-+/g,'-'); 
    } 
}">
    <!-- Page Heading -->
    <div class="container mx-auto px-4 pt-6 lg:px-8 lg:pt-8">
        <div class="flex flex-col gap-2 text-center sm:flex-row sm:items-center sm:justify-between sm:text-start">
            <div class="grow">
                <h1 class="mb-1 text-xl font-bold text-zinc-500">Genres List</h1>
                <h2 class="text-sm font-medium text-zinc-500">
                    A detailed list of all genres in the system.
                </h2>
            </div>
            <div class="flex justify-center sm:justify-end">
                <button type="button" @click="open = true; name=''; slug='';"
                        class="inline-flex items-center gap-2 rounded-lg bg-purple-600 px-4 py-2 text-sm font-semibold text-white hover:bg-purple-700 active:bg-purple-800">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="size-5">
                        <path d="M10 3.5a.75.75 0 01.75.75v5h5a.75.75 0 010 1.5h-5v5a.75.75 0 01-1.5 0v-5h-5a.75.75 0 010-1.5h5v-5A.75.75 0 0110 3.5z" />
                    </svg>
                    <span>Tambah Genre</span>
                </button>
            </div>
        </div>
    </div>
    <!-- END Page Heading -->

    <!-- Modal: Add Genre -->
    <div x-cloak x-show="open" class="fixed inset-0 z-50 flex items-center justify-center" @keydown.escape.window="open=false">
        <div class="absolute inset-0 bg-black/50" @click="open=false"></div>
        <div class="relative z-10 w-full max-w-md rounded-lg bg-white text-zinc-900 shadow-xl">
            <div class="flex items-center justify-between border-b border-zinc-100 px-5 py-3">
                <h3 class="font-semibold">Tambah Genre</h3>
                <button type="button" @click="open=false" class="rounded p-1 hover:bg-zinc-100">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="size-5">
                        <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd" />
                    </svg>
                </button>
            </div>
            <form action="{{ route('admin.genres.store') }}" method="POST" class="space-y-4 p-5">
                @csrf
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
                    <button type="button" @click="open=false"
                            class="rounded-lg border border-zinc-300 bg-white px-4 py-2 text-sm font-semibold text-zinc-700 hover:bg-zinc-50">Batal</button>
                    <button type="submit"
                            class="rounded-lg bg-purple-600 px-4 py-2 text-sm font-semibold text-white hover:bg-purple-700">Simpan</button>
                </div>
            </form>
        </div>
    </div>
    <!-- END Modal: Add Genre -->

    <!-- Page Section -->
    <div class="container mx-auto p-4 lg:p-8 xl:max-w-7xl">
        <div class="flex flex-col rounded-lg border border-zinc-200 bg-white">
            <div class="p-5">
                <!-- Responsive Table Container -->
                <div class="min-w-full overflow-x-auto rounded-sm text-black">
                    <!-- Genres Table -->
                    <table id="myTable" class="min-w-full align-middle text-sm">
                        <thead>
                            <tr class="border-b-2 border-zinc-100">
                                <th class="px-3 py-2 text-start text-sm font-semibold tracking-wider text-zinc-700 uppercase">Name</th>
                                <th class="px-3 py-2 text-start text-sm font-semibold tracking-wider text-zinc-700 uppercase">Slug</th>
                                <th class="px-3 py-2 text-start text-sm font-semibold tracking-wider text-zinc-700 uppercase">Total Mangas</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($genres as $genre)
                                <tr class="border-b border-zinc-100 hover:bg-zinc-50">
                                    <td class="p-3 text-start font-semibold text-zinc-600">{{ $genre->name }}</td>
                                    <td class="p-3 text-start font-semibold text-zinc-600">{{ $genre->slug }}</td>
                                    <td class="p-3 text-start text-zinc-600">{{ $genre->mangas_count }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                    <!-- END Genres Table -->
                </div>
            </div>
        </div>
    </div>
    <!-- END Page Section -->
</div>
@endsection

<!-- jQuery and DataTables JS -->
<script src="https://code.jquery.com/jquery-3.7.1.js"></script>
<script src="https://cdn.datatables.net/2.3.2/js/dataTables.js"></script>
<script>
    $(document).ready(function() {
        $('#myTable').DataTable({
            responsive: true
        });
    });
</script>