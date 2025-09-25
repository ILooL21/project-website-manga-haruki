@extends('admin.layouts.app')

@section('title', 'Tabel Data User')

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
    <div x-data="{
        open: {{ old() ? 'true' : 'false' }},
        name: '{{ old('name', '') }}',
        email: '{{ old('email', '') }}',
        role: '{{ old('role', '') }}',
    }">
        <!-- Page Heading -->
        <div class="container mx-auto px-4 pt-6 lg:px-8 lg:pt-8">
            <div class="flex flex-col gap-2 text-center sm:flex-row sm:items-center sm:justify-between sm:text-start">
                <div class="grow">
                    <h1 class="mb-1 text-xl font-bold text-zinc-500">Users List</h1>
                    <h2 class="text-sm font-medium text-zinc-500">
                        A detailed list of all users in the system.
                    </h2>
                </div>
                <div class="flex justify-center sm:justify-end">
                    <button type="button" @click="open = true; name=''; email=''; role='';"
                        class="inline-flex items-center gap-2 rounded-lg bg-purple-600 px-4 py-2 text-sm font-semibold text-white hover:bg-purple-700 active:bg-purple-800">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="size-5">
                            <path
                                d="M10 3.5a.75.75 0 01.75.75v5h5a.75.75 0 010 1.5h-5v5a.75.75 0 01-1.5 0v-5h-5a.75.75 0 010-1.5h5v-5A.75.75 0 0110 3.5z" />
                        </svg>
                        <span>Tambah User</span>
                    </button>
                </div>
            </div>
        </div>
        <!-- END Page Heading -->

        <!-- Modal: Add User -->
        <div x-cloak x-show="open" class="fixed inset-0 z-50 flex items-center justify-center"
            @keydown.escape.window="open=false">
            <div class="absolute inset-0 bg-black/50" @click="open=false"></div>
            <div class="relative z-10 w-full max-w-md rounded-lg bg-white text-zinc-900 shadow-xl">
                <div class="flex items-center justify-between border-b border-zinc-100 px-5 py-3">
                    <h3 class="font-semibold">Tambah User</h3>
                    <button type="button" @click="open=false" class="rounded p-1 hover:bg-zinc-100">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="size-5">
                            <path fill-rule="evenodd"
                                d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z"
                                clip-rule="evenodd" />
                        </svg>
                    </button>
                </div>
                <form action="{{ route('admin.users.store') }}" method="POST" class="space-y-4 p-5">
                    @csrf
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

                    @if (session('server_error'))
                        <div class="mb-2 rounded border border-red-200 bg-red-50 p-3 text-red-700">
                            <strong>Server error:</strong>
                            <div class="truncate">{{ session('server_error') }}</div>
                        </div>
                    @endif
                    <div>
                        <label for="name" class="mb-1 block text-sm font-medium text-zinc-700">Nama</label>
                        <input x-model.trim="name" type="text" id="name" name="name" required
                            class="w-full rounded-lg border border-zinc-300 bg-white px-3 py-2 text-sm text-zinc-900 placeholder-zinc-400 focus:border-purple-500 focus:outline-none focus:ring-2 focus:ring-purple-500"
                            placeholder="Masukkan nama user" />
                    </div>
                    <div>
                        <label for="email" class="mb-1 block text-sm font-medium text-zinc-700">Email</label>
                        <input x-model.trim="email" type="email" id="email" name="email" required
                            class="w-full rounded-lg border border-zinc-300 bg-white px-3 py-2 text-sm text-zinc-900 placeholder-zinc-400 focus:border-purple-500 focus:outline-none focus:ring-2 focus:ring-purple-500"
                            placeholder="Masukkan email user" />
                    </div>
                    <div>
                        <label for="role" class="mb-1 block text-sm font-medium text-zinc-700">Role</label>
                        <select x-model="role" id="role" name="role" required
                            class="w-full rounded-lg border border-zinc-300 bg-white px-3 py-2 text-sm text-zinc-900 placeholder-zinc-400 focus:border-purple-500 focus:outline-none focus:ring-2 focus:ring-purple-500">
                            <option value="" disabled>Pilih role</option>
                            <option value="Admin">Admin</option>
                            <option value="User">User</option>
                        </select>
                    </div>
                    <div>
                        <label for="password" class="mb-1 block text-sm font-medium text-zinc-700">Password</label>
                        <input type="password" id="password" name="password" required
                            class="w-full rounded-lg border border-zinc-300 bg-white px-3 py-2 text-sm text-zinc-900 placeholder-zinc-400 focus:border-purple-500 focus:outline-none focus:ring-2 focus:ring-purple-500"
                            placeholder="Masukkan password user" />
                    </div>
                    <div class="flex items-center justify-end gap-2 pt-2">
                        <button type="button" @click="open=false"
                            class="rounded-lg border border-zinc-300 bg-white px-4 py-2 text-sm font-semibold text-zinc-700 hover:bg-zinc-50">Batal</button>
                        <button type="submit"
                            class="rounded-lg bg-purple-600 px-4 py-2 text-sm font-semibold text-white hover:bg-purple-700">Tambah</button>
                    </div>
                </form>
            </div>
        </div>
        <!-- END Modal: Add User -->

        <!-- Page Section -->
        <div class="container mx-auto p-4 lg:p-8 xl:max-w-7xl">
            <div class="flex flex-col rounded-lg border border-zinc-200 bg-white">
                <div class="p-5">
                    <div class="min-w-full overflow-x-auto rounded-sm text-black">
                        <table id="myTable" class="min-w-full align-middle text-sm">
                            <thead>
                                <tr class="border-b-2 border-zinc-100">
                                    <th
                                        class="px-3 py-2 text-start text-sm font-semibold tracking-wider text-zinc-700 uppercase">
                                        No</th>
                                    <th
                                        class="px-3 py-2 text-start text-sm font-semibold tracking-wider text-zinc-700 uppercase">
                                        Nama</th>
                                    <th
                                        class="px-3 py-2 text-start text-sm font-semibold tracking-wider text-zinc-700 uppercase">
                                        Email</th>
                                    <th
                                        class="px-3 py-2 text-start text-sm font-semibold tracking-wider text-zinc-700 uppercase">
                                        Role</th>
                                    <th
                                        class="px-3 py-2 text-start text-sm font-semibold tracking-wider text-zinc-700 uppercase">
                                        Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($users as $user)
                                    <tr class="border-b border-zinc-100 hover:bg-zinc-50">
                                        <td class="p-3 text-start font-semibold text-zinc-600">{{ $loop->iteration }}</td>
                                        <td class="p-3 text-start font-semibold text-zinc-600">{{ $user->name }}</td>
                                        <td class="p-3 text-start font-semibold text-zinc-600">{{ $user->email }}</td>
                                        <td class="p-3 text-start text-zinc-600">{{ $user->role }}</td>
                                        <td class="p-3 text-start">
                                            <div class="flex items-center gap-2">
                                                {{-- Tombol View dengan ikon --}}
                                                <button class="text-blue-500 hover:text-blue-700 hover:cursor-pointer"
                                                    title="View User">
                                                    <a href="{{ route('admin.users.show', $user->id) }}">
                                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5"
                                                            viewBox="0 0 20 20" fill="currentColor">
                                                            <path
                                                                d="M10 3C5 3 1.73 7.11 1.05 10.29a1 1 0 000 .42C1.73 12.89 5 17 10 17s8.27-4.11 8.95-7.29a1 1 0 000-.42C18.27 7.11 15 3 10 3zm0 12c-3.87 0-7.19-3.13-7.94-6C2.81 6.13 6.13 3 10 3s7.19 3.13 7.94 6c-.75 2.87-4.07 6-7.94 6zm0-9a3 3 0 100 6 3 3 0 000-6zm0 5a2 2 0 110-4 2 2 0 010 4z" />
                                                        </svg>
                                                    </a>
                                                </button>
                                                {{-- Tombol Edit dengan Ikon --}}
                                                <button class="text-blue-500 hover:text-blue-700 hover:cursor-pointer"
                                                    title="Edit User">
                                                    <a href="{{ route('admin.users.edit', $user->id) }}">
                                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5"
                                                            viewBox="0 0 20 20" fill="currentColor">
                                                            <path
                                                                d="M17.414 2.586a2 2 0 00-2.828 0L7 10.172V13h2.828l7.586-7.586a2 2 0 000-2.828z" />
                                                            <path fill-rule="evenodd"
                                                                d="M2 6a2 2 0 012-2h4a1 1 0 010 2H4v10h10v-4a1 1 0 112 0v4a2 2 0 01-2 2H4a2 2 0 01-2-2V6z"
                                                                clip-rule="evenodd" />
                                                        </svg>
                                                    </a>
                                                </button>
                                                {{-- Form Hapus dengan Ikon --}}
                                                <form action="{{ route('admin.users.destroy', $user->id) }}"
                                                    method="POST" class="inline" title="Delete User"
                                                    onsubmit="return confirm('Apakah Anda yakin ingin menghapus user ini?');">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="text-red-500 hover:text-red-700">
                                                        <svg xmlns="http://www.w3.org/2000/svg"
                                                            class="h-5 w-5  hover:cursor-pointer" viewBox="0 0 20 20"
                                                            fill="currentColor">
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
                        <!-- END Users Table -->
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

    @if(session('server_error'))
        <div class="fixed bottom-5 right-5 z-50">
            <div class="rounded-lg bg-red-100 p-4 text-red-700 shadow-lg">
                <p><strong>Server error:</strong></p>
                <p class="truncate">{{ session('server_error') }}</p>
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
                    "emptyTable": "Tidak ada data user ditemukan.",
                    "zeroRecords": "Tidak ada user yang cocok ditemukan."
                },
                lengthMenu: [5, 10, 25, 100, -1]
            });
        });
    </script>
@endsection
