<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>
        Profile {{ $user->name }}
    </title>
</head>
<body>
    <h1>Profile of {{ $user->name }}</h1>

    <h2>
        Edit Profile
    </h2>

    <form action="{{ route('profile.update') }}" method="POST">
        @csrf
        @method('PUT')

        <div>
            <label for="name">Name:</label>
            <input type="text" id="name" name="name" value="{{ old('name', $user->name) }}" required>
            @error('name')
                <p>{{ $message }}</p>
            @enderror
        </div>

        <div>
            <label for="email">Email:</label>
            <input type="email" id="email" name="email" value="{{ old('email', $user->email) }}" required>
            @error('email')
                <p>{{ $message }}</p>
            @enderror
        </div>

        <button type="submit" onclick="loading(event, this)">Update Profile</button>
    </form>
    <br>

    {{-- form ganti password --}}
    <h2>
        Change Password
    </h2>
    <form action="{{ route('profile.change_password') }}" method="POST">
        @csrf
        @method('PUT')
        <div>
            <label for="current_password">Current Password:</label>
            <input type="password" id="current_password" name="current_password" required>
        </div>

        <div>
            <label for="new_password">New Password:</label>
            <input type="password" id="new_password" name="new_password" required>
        </div>

        <div>
            <label for="new_password_confirmation">Confirm New Password:</label>
            <input type="password" id="new_password_confirmation" name="new_password_confirmation" required>
        </div>

        <button type="submit" onclick="loading(event, this)">Change Password</button>
    </form>
    <br><br><br>
    @if (session('status'))
        <p>Status: {{ session('status') }}</p>
        <p>Message: {{ session('message') }}</p>
    @endif
</body>
</html>