@extends('landing-page.layouts.main')

@section('title', 'My Profile')

@section('content')
<div class="bg-base-100 text-base-content min-h-screen p-4 sm:p-8">
    <div class="max-w-2xl mx-auto">

            <h1 class="text-4xl font-bold mb-8">My Profile</h1>

            <div>
                <h2 class="text-2xl font-semibold mb-4 border-b border-gray-700 pb-2">
                    Edit Profile Information
                </h2>

                <form action="{{ route('profile.update') }}" method="POST" class="space-y-6">
                    @csrf
                    @method('PUT')

                    <div>
                        <label for="name" class="block mb-2 text-sm font-medium text-gray-400">Name:</label>
                        <input type="text" id="name" name="name" value="{{ old('name', $user->name) }}" required
                            class=" w-full p-3 border border-slate-700 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                        @error('name')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="email" class="block mb-2 text-sm font-medium text-gray-400">Email:</label>
                        <span class="block mb-2 text-lg font-medium text-gray-400">{{ $user->email }}</span>
                        @error('email')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <button type="submit" onclick="loading(event, this)"
                            class="btn btn-primary w-full md:w-auto py-2">
                        Update Profile
                    </button>
                </form>
            </div>

            <hr class="border-slate-700 my-10">

            <div>
                <h2 class="text-2xl font-semibold mb-4 border-b border-gray-700 pb-2">
                    Change Password
                </h2>
                <form action="{{ route('profile.change_password') }}" method="POST" class="space-y-6">
                    @csrf
                    @method('PUT')

                    <div>
                        <label for="new_password" class="block mb-2 text-sm font-medium text-gray-400">New Password:</label>
                        <input type="password" id="new_password" name="new_password" required
                            class="w-full p-3 border border-slate-700 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>

                    <div>
                        <label for="new_password_confirmation" class="block mb-2 text-sm font-medium text-gray-400">Confirm New Password:</label>
                        <input type="password" id="new_password_confirmation" name="new_password_confirmation" required
                            class="w-full p-3 border border-slate-700 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>

                    <button type="submit" onclick="loading(event, this)"
                            class="btn btn-primary w-full md:w-auto py-2">
                        Change Password
                    </button>
                </form>
            </div>

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