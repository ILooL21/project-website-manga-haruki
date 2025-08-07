<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function index()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        // Validate and authenticate user
        $credentials = $request->only('email', 'password');

        if (Auth::attempt($credentials)) {
            return redirect()->route('index')->with([
                'status' => 'success',
                'message' => 'Login successful!'
            ]);
        }

        // Authentication failed
        return redirect()->back()->withErrors(['error' => 'Invalid credentials'])->withInput();
    }

    public function logout(Request $request)
    {
        // Handle logout logic
        Auth::logout();

        // Invalidate the session
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        // Redirect to login with success message
        return redirect()->route('login')->with([
            'status' => 'success',
            'message' => 'Logout successful!'
        ]);
    }

    public function showRegistrationForm()
    {
        return view('auth.register');
    }

    public function register(Request $request)
    {
        // Validate registration data
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
        ]);

        // Create the user
        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        return redirect()->route('login')->with([
            'status' => 'success',
            'message' => 'Registration successful! You can now log in.'
        ]);
    }

    // public function profile()
    // {
    //     $user = Auth::user();
    //     return view('profile', compact('user'));
    // }

    // public function updateProfile(Request $request)
    // {
    //     $user = Auth::user();

    //     // Validate profile data
    //     $request->validate([
    //         'name' => 'required|string|max:255',
    //         'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
    //     ]);

    //     // Update user profile
    //     User::where('id', $user->id)->update([
    //         'name' => $request->name,
    //         'email' => $request->email,
    //     ]);

    //     return redirect()->route('profile')->with([
    //         'status' => 'success',
    //         'message' => 'Profile updated successfully!'
    //     ]);
    // }

    // public function changePassword(Request $request)
    // {
    //     $user = Auth::user();

    //     // Validate password change data
    //     $request->validate([
    //         'current_password' => 'required|string',
    //         'new_password' => 'required|string|min:8|confirmed',
    //     ]);

    //     // Check if the current password is correct
    //     if (!Hash::check($request->current_password, $user->password)) {
    //         return redirect()->back()->withErrors(['current_password' => 'Current password is incorrect.']);
    //     }

    //     // Update the password
    //     User::where('id', $user->id)->update([
    //         'password' => Hash::make($request->new_password),
    //     ]);

    //     return redirect()->route('profile')->with([
    //         'status' => 'success',
    //         'message' => 'Password changed successfully!'
    //     ]);
    // }
}
