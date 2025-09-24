@extends('admin.layouts.app')

@section('title','Edit User')

@section('content')
    <div x-data="{ 
        name:'{{ $userData->name }}', 
        email:'{{ $userData->email }}', 
        role:'{{ $userData->role }}'
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
            <form action="{{ route('admin.users.update', $userData->id) }}"
                 method="POST" class="space-y-4 py-5">
                @csrf
                @method('PUT')
                <div>
                    <label for="name" class="mb-1 block text-sm font-medium text-zinc-700">Nama</label>
                    <input x-model.trim="name"type="text" id="name" name="name" required
                        class="w-full rounded-lg border border-zinc-300 bg-white px-3 py-2 text-sm text-zinc-900 placeholder-zinc-400 focus:border-purple-500 focus:outline-none focus:ring-2 focus:ring-purple-500"
                        placeholder="Misal: Action" />
                </div>
                <div>
                    <label for="email" class="mb-1 block text-sm font-medium text-zinc-700">Email</label>
                    <input x-model.trim="email" type="email" id="email" name="email" required
                        class="w-full rounded-lg border border-zinc-300 bg-white px-3 py-2 text-sm text-zinc-900 placeholder-zinc-400 focus:border-purple-500 focus:outline-none focus:ring-2 focus:ring-purple-500"
                        placeholder="Misal: action@example.com" />
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
                    <input type="password" id="password" name="password"
                    class="w-full rounded-lg border border-zinc-300 bg-white px-3 py-2 text-sm text-zinc-900 placeholder-zinc-400 focus:border-purple-500 focus:outline-none focus:ring-2 focus:ring-purple-500"
                    placeholder="Masukkan password user" />
                </div>
                <div class="flex items-center justify-end gap-2 pt-2">
                    <button type="button" onclick="window.location='{{ route('admin.users') }}'"
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

