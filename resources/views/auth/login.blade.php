<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Login</title>
    @vite('resources/css/app.css') <!-- Pastikan Tailwind CSS terhubung -->
    <style>
        /* Login page input overrides to ensure white background and dark text */
        .login-page input[type="email"],
        .login-page input[type="password"],
        .login-page input[type="text"],
        .login-page input[type="search"],
        .login-page textarea,
        .login-page select {
            background-color: #ffffff !important;
            color: #111827 !important; /* gray-900 */
            border: 1px solid #d1d5db !important; /* gray-300 */
        }
        .login-page input::placeholder {
            color: #9ca3af !important; /* gray-400 */
        }
        .login-page input:focus {
            outline: none !important;
            background-color: #ffffff !important;
            border-color: #a855f7 !important; /* purple-600 */
            box-shadow: 0 0 0 3px rgba(168, 85, 247, 0.3) !important;
        }
        /* Avoid unreadable autofill styles */
        .login-page input:-webkit-autofill,
        .login-page input:-webkit-autofill:hover,
        .login-page input:-webkit-autofill:focus {
            -webkit-text-fill-color: #111827 !important;
            transition: background-color 5000s ease-in-out 0s;
            box-shadow: 0 0 0px 1000px #ffffff inset;
        }
    </style>
</head>
<body class="login-page bg-gray-50 flex items-center justify-center min-h-screen">
    <div class="bg-white p-8 rounded-lg shadow-lg w-full max-w-md border border-gray-200">
        <h1 class="text-2xl font-bold text-gray-900 text-center mb-6">Login</h1>
        <form action="{{ route('login') }}" method="POST">
            @csrf
            <!-- Email Field -->
            <div class="mb-4">
                <label for="email" class="block text-gray-700 mb-2">Email:</label>
                <input type="email" name="email" id="email" required value="{{ old('email') }}"
                    class="w-full px-4 py-2 rounded-lg bg-white text-gray-900 border border-gray-300 placeholder-gray-500 hover:bg-white focus:outline-none focus:bg-white focus:ring-2 focus:ring-purple-500 focus:border-purple-500 caret-purple-600 shadow-sm" />
            </div>
            <!-- Password Field -->
            <div class="mb-4">
                <label for="password" class="block text-gray-700 mb-2">Password:</label>
                <input type="password" name="password" id="password" required
                    class="w-full px-4 py-2 rounded-lg bg-white text-gray-900 border border-gray-300 placeholder-gray-500 hover:bg-white focus:outline-none focus:bg-white focus:ring-2 focus:ring-purple-500 focus:border-purple-500 caret-purple-600 shadow-sm" />
            </div>
            <!-- Error Message -->
            @error('error')
                <span class="block text-red-600 text-sm mb-4">{{ $message }}</span>
            @enderror
            <!-- Submit Button -->
            <button type="submit" onclick="loading(event, this)"
                class="w-full bg-purple-600 hover:bg-purple-700 text-white font-bold py-2 px-4 rounded-lg transition">
                Login
            </button>
        </form>
        <!-- Status Message -->
        @if (session('status'))
            <div class="mt-4 border border-green-200 bg-green-100 text-green-800 p-4 rounded-lg">
                <p>Status: {{ session('status') }}</p>
                <p>Message: {{ session('message') }}</p>
            </div>
        @endif
    </div>
</body>
</html>