@extends('admin.layouts.app')

@section('title', 'Dashboard')

@section('styles')
    <link rel="stylesheet" href="https://cdn.datatables.net/2.3.2/css/dataTables.dataTables.css" />
@endsection

@section('content')
<!-- Page Heading -->
    <div class="container mx-auto px-4 pt-6 lg:px-8 lg:pt-8">
        <div class="flex flex-col gap-2 text-center sm:flex-row sm:items-center sm:justify-between sm:text-start">
            <div class="grow">
                <h1 class="mb-1 text-xl font-bold text-zinc-500">Manga Dashboard</h1>
                <h2 class="text-sm font-medium text-zinc-500">
                    Welcome, here is an overview of your manga data.
                </h2>
            </div>
        </div>
    </div>
    <!-- END Page Heading -->

    <!-- Page Section -->
    <div class="container mx-auto p-4 lg:p-8 xl:max-w-7xl">
        <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-12 lg:gap-8">
            <!-- Quick Statistics -->
            <a href="javascript:void(0)"
                class="flex flex-col rounded-lg border border-zinc-200 bg-white hover:bg-zinc-50/50 active:border-purple-200 lg:col-span-3">
                <div class="flex grow items-center justify-between p-5">
                    <dl>
                        <dt class="text-2xl font-bold text-zinc-600">{{ $totalMangas }}</dt>
                        <dd class="text-sm font-medium text-zinc-500">Total Mangas</dd>
                    </dl>
                </div>
            </a>
            <a href="javascript:void(0)"
                class="flex flex-col rounded-lg border border-zinc-200 bg-white hover:bg-zinc-50/50 active:border-purple-200 lg:col-span-3">
                <div class="flex grow items-center justify-between p-5">
                    <dl>
                        <dt class="text-2xl font-bold text-zinc-600">{{ $totalChapters }}</dt>
                        <dd class="text-sm font-medium text-zinc-500">Total Chapters</dd>
                    </dl>
                </div>
            </a>
            <a href="javascript:void(0)"
                class="flex flex-col rounded-lg border border-zinc-200 bg-white hover:bg-zinc-50/50 active:border-purple-200 lg:col-span-3">
                <div class="flex grow items-center justify-between p-5">
                    <dl>
                        <dt class="text-2xl font-bold text-zinc-600">{{ $totalGenres }}</dt>
                        <dd class="text-sm font-medium text-zinc-500">Total Genres</dd>
                    </dl>
                </div>
            </a>
            <a href="javascript:void(0)"
                class="flex flex-col rounded-lg border border-zinc-200 bg-white hover:bg-zinc-50/50 active:border-purple-200 lg:col-span-3">
                <div class="flex grow items-center justify-between p-5">
                    <dl>
                        <dt class="text-2xl font-bold text-zinc-600">{{ $totalUsers }}</dt>
                        <dd class="text-sm font-medium text-zinc-500">Total Users</dd>
                    </dl>
                </div>
            </a>
            <!-- END Quick Statistics -->

            <!-- Manga Table -->
            <div class="flex flex-col rounded-lg border border-zinc-200 bg-white sm:col-span-2 lg:col-span-12">
                <div
                    class="flex flex-col items-center justify-between gap-4 border-b border-zinc-100 p-5 text-center sm:flex-row sm:text-start">
                    <div>
                        <h2 class="mb-0.5 font-semibold text-xl text-zinc-500">All Mangas</h2>
                        <h3 class="text-sm font-medium text-zinc-600">
                            A list of all mangas in the system.
                        </h3>
                    </div>
                </div>
                <div class="p-5">
                    <!-- Responsive Table Container -->
                    <div class="min-w-full overflow-x-auto rounded-sm">
                        <!-- Manga Table -->
                        <table class="min-w-full align-middle text-sm font-semibold text-zinc-600"" id="myTable">
                            <thead>
                                <tr class="border-b-2 border-zinc-100">
                                    <th class="px-3 py-2 text-start text-sm font-semibold tracking-wider text-zinc-700 uppercase">Title</th>
                                    <th class="px-3 py-2 text-start text-sm font-semibold tracking-wider text-zinc-700 uppercase">Chapters</th>
                                    <th class="px-3 py-2 text-start text-sm font-semibold tracking-wider text-zinc-700 uppercase">Genre</th>
                                    <th class="px-3 py-2 text-start text-sm font-semibold tracking-wider text-zinc-700 uppercase">Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($mangas as $manga)
                                    <tr class="border-b border-zinc-100 hover:bg-zinc-50">
                                        <td class="p-3 text-start font-semibold text-zinc-600">{{ $manga->title }}</td>
                                        <td class="p-3 text-start font-semibold text-zinc-600">{{ $manga->chapters_count }}</td>
                                        <td class="p-3 text-start ">
                                            @foreach ($manga->genres as $genre)
                                                <span class="inline-block bg-gray-200 text-gray-800 text-xs font-medium mr-1 px-2.5 py-0.5 rounded">{{ $genre->name }}</span>
                                            @endforeach
                                        </td>
                                        <td class="p-3 text-start font-semibold text-zinc-600">{{ $manga->status }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                        <!-- END Manga Table -->
                    </div>
                </div>
            </div>
            <!-- END Manga Table -->
        </div>
    </div>
    <!-- END Page Section -->
@endsection

@section('scripts')
    <script src="https://code.jquery.com/jquery-3.7.1.js"></script>
    <script src="https://cdn.datatables.net/2.3.2/js/dataTables.js"></script>
    <script>
        $(document).ready(function() {
            $('#myTable').DataTable({
                dom:'frtp',
                responsive: true,
                "language": {
                   "emptyTable": "Tidak ada data manga ditemukan.",
                   "zeroRecords": "Tidak ada manga yang cocok ditemukan."
                },
                pageLength: 10
            });

            // remove dataTables_length
            $('.dt-search').addClass('mb-4 flex justify-end');
            $('.dt-search input').addClass('bg-black text-black border border-zinc-300 rounded-md py-2 px-3');
            $('.dt-search label').addClass('text-zinc-600');

            // tambahkan margin top pada pagination dan letakkan di kanan 
            $('.dt-paging').addClass('mt-4 flex justify-end');

            $('.dt-paging nav button').addClass('text-black');
        });
    </script>
@endsection
