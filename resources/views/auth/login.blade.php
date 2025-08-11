<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Login</title>
    @vite('resources/css/app.css') <!-- Pastikan Tailwind CSS terhubung -->
</head>
<body class="bg-gray-900 flex items-center justify-center min-h-screen">
    <div class="bg-gray-800 p-8 rounded-lg shadow-lg w-full max-w-md">
        <h1 class="text-2xl font-bold text-white text-center mb-6">Login</h1>
        <form action="{{ route('login') }}" method="POST">
            @csrf
            <!-- Email Field -->
            <div class="mb-4">
                <label for="email" class="block text-gray-400 mb-2">Email:</label>
                <input type="email" name="email" id="email" required value="{{ old('email') }}"
                    class="w-full px-4 py-2 rounded-lg bg-gray-700 text-white focus:outline-none focus:ring-2 focus:ring-purple-500">
            </div>
            <!-- Password Field -->
            <div class="mb-4">
                <label for="password" class="block text-gray-400 mb-2">Password:</label>
                <input type="password" name="password" id="password" required
                    class="w-full px-4 py-2 rounded-lg bg-gray-700 text-white focus:outline-none focus:ring-2 focus:ring-purple-500">
            </div>
            <!-- Error Message -->
            @error('error')
                <span class="block text-red-500 text-sm mb-4">{{ $message }}</span>
            @enderror
            <!-- Submit Button -->
            <button type="submit"
                class="w-full bg-purple-600 hover:bg-purple-700 text-white font-bold py-2 px-4 rounded-lg transition">
                Login
            </button>
        </form>
        <!-- Status Message -->
        @if (session('status'))
            <div class="mt-4 bg-green-500 text-white p-4 rounded-lg">
                <p>Status: {{ session('status') }}</p>
                <p>Message: {{ session('message') }}</p>
            </div>
        @endif
    </div>
</body>
</html>