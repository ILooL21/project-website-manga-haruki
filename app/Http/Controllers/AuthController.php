<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Laravel\Socialite\Facades\Socialite;

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
            return redirect()->route('admin.dashboard')->with([
                'status' => 'success',
                'message' => 'Login successful!'
            ]);
        }

        // Authentication failed
        return redirect()->back()->withErrors(['error' => 'Invalid credentials'])->withInput();
    }

    public function loginGoogle()
    {
        return Socialite::driver('google')
            ->with(['prompt' => 'select_account'])
            ->redirect();
    }

    public function handleGoogleCallback()
    {
        try {
            $googleUser = Socialite::driver('google')->stateless()->user();

            // cek apakah user authentikasi
            if(Auth::check()){
                $user = User::where('google_id', $googleUser->id)->first();
                if ($user && $user->id !== Auth::user()->id) {
                    return redirect()->route('landing-page.index')->with([
                        'status' => 'error',
                        'message' => 'Email google sudah terdaftar, silakan login menggunakan akun tersebut atau gunakan metode lain.'
                    ]);
                }else{
                    // update email dan google_id
                    User::where('id', Auth::user()->id)->update([
                        'email' => $googleUser->email,
                        'google_id' => $googleUser->id,
                    ]);

                    return redirect()->route('profile')->with([
                        'status' => 'success',
                        'message' => 'Email berhasil diubah!'
                    ]);
                }
            }else{
                $user = User::where('email', $googleUser->email)->first();
    
                if ($user) {
                    // jika google_id null, update google_id
                    if (is_null($user->google_id)) {
                        $user->update([
                            'google_id' => $googleUser->id,
                        ]);
                    }
    
                    // User exists, log them in
                    Auth::login($user);
                } else {
                    // User does not exist, create a new user
                    $newUser = User::create([
                        'name' => $googleUser->name,
                        'email' => $googleUser->email,
                        'google_id' => $googleUser->id,
                        'password' => Hash::make(uniqid()),
                    ]);
    
                    // Log the new user in
                    Auth::login($newUser);
                }
    
                return redirect()->route('landing-page.index')->with([
                    'status' => 'success',
                    'message' => 'Login successful!'
                ]);
            }
        } catch (\Exception $e) {
            return redirect()->route('landing-page.index')->with([
                'status' => 'error',
                'message' => 'Login failed, please try again.'
            ]);
        }
    }

    public function logout(Request $request)
    {
        // cari user yang sedang login
        $user = Auth::user();

        // Handle logout logic
        Auth::logout();

        // Invalidate the session
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        // Redirect to login with success message
        return redirect()->route($user->role === 'User' ? 'landing-page.index' : 'login')->with([
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

    public function profile()
    {
        $user = Auth::user();
        return view('auth.profile', compact('user'));
    }

    public function updateProfile(Request $request)
    {
        $user = Auth::user();

        // Validate profile data
        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        // Update user profile
        User::where('id', $user->id)->update([
            'name' => $request->name,
        ]);

        return redirect()->route('profile')->with([
            'status' => 'success',
            'message' => 'Profile updated successfully!'
        ]);
    }

    public function changePassword(Request $request)
    {
        $user = Auth::user();

        // Validate password change data
        $request->validate([
            'new_password' => 'required|string|min:8|confirmed',
        ]);

        // Update the password
        User::where('id', $user->id)->update([
            'password' => Hash::make($request->new_password),
        ]);

        return redirect()->route('profile')->with([
            'status' => 'success',
            'message' => 'Password changed successfully!'
        ]);
    }
}
