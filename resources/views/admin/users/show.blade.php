@extends('admin.layouts.app')

@section('title', 'Detail User')

@section('content')
    <div x-data="{ 
        name:'{{ $userData->name }}', 
        email:'{{ $userData->email }}', 
        role:'{{ $userData->role }}'
    }">
        <div class="container mx-auto px-4 pt-6 lg:px-8 lg:pt-8">
            <div class="flex flex-col gap-2 text-center sm:flex-row sm:items-center sm:justify-between sm:text-start">
                <div class="grow">
                    <h1 class="mb-1 text-xl font-bold text-zinc-500">Detail User</h1>
                    <h2 class="text-sm font-medium text-zinc-500">
                        View the details of the user
                    </h2>
                </div>
            </div>
            <div class="space-y-4 py-5">
                 <div>
                     <label for="name" class="mb-1 block text-sm font-medium text-zinc-700">Nama</label>
                     <input x-model.trim="name"type="text" id="name" name="name" disabled
                         class="w-full rounded-lg border border-zinc-300 bg-white px-3 py-2 text-sm text-zinc-900 placeholder-zinc-400 focus:border-purple-500 focus:outline-none focus:ring-2 focus:ring-purple-500"
                         placeholder="Misal: Action" />
                 </div>
                 <div>
                     <label for="email" class="mb-1 block text-sm font-medium text-zinc-700">Email</label>
                     <input x-model.trim="email" type="email" id="email" name="email" disabled
                         class="w-full rounded-lg border border-zinc-300 bg-white px-3 py-2 text-sm text-zinc-900 placeholder-zinc-400 focus:border-purple-500 focus:outline-none focus:ring-2 focus:ring-purple-500"
                         placeholder="Misal: action@example.com" />
                 </div>
                 <div>
                     <label for="role" class="mb-1 block text-sm font-medium text-zinc-700">Role</label>
                     <input x-model.trim="role" type="text" id="role" name="role" disabled
                         class="w-full rounded-lg border border-zinc-300 bg-white px-3 py-2 text-sm text-zinc-900 placeholder-zinc-400 focus:border-purple-500 focus:outline-none focus:ring-2 focus:ring-purple-500"
                         placeholder="Misal: Admin" />
                 </div>
                 <div class="flex items-center justify-end gap-2 pt-2">
                     <button type="button" @click="window.location='{{ route('admin.users') }}'"
                             class="rounded-lg border border-zinc-300 bg-white px-4 py-2 text-sm font-semibold text-zinc-700 hover:bg-zinc-50 hover:cursor-pointer">
                         <a>Kembali</a>
                     </button>
                 </div>
            </div>
        </div>
    </div>
@endsection

