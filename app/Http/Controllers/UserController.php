<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function index()
    {
        // Logika untuk menampilkan daftar pengguna yang bukan super admin urutkan dari yang terbaru
        $users = User::where('role', '!=', 'Super Admin')->orderBy('created_at', 'desc')->get();
        return view('admin.users.index', compact('users'));
    }

    public function show($id)
    {
        $user = User::findOrFail($id);
        return view('admin.users.show', [
            'userData' => $user
        ]);
    }

    public function edit($id)
    {
        $user = User::findOrFail($id);
        return view('admin.users.edit', [
            'userData' => $user
        ]);
    }
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8',
        ]);
        try {
            User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
            ]);

            return redirect()->route('admin.users')->with([
                'status' => 'success',
                'message' => 'User created successfully.'
            ]);
        } catch (\Throwable $th) {
            // Log and redirect back with input and server error
            report($th);
            return redirect()->back()->withInput()->with([
                'status' => 'failed',
                'message' => 'Gagal membuat user.',
                'server_error' => $th->getMessage(),
            ]);
        }
    }

    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
            'password' => 'nullable|string|min:8',
            'role' => 'required|string|in:User,Admin',
        ]);

        try {
            $user->name = $request->name;
            $user->email = $request->email;
            $user->role = $request->role;

            if ($request->filled('password')) {
                $user->password = Hash::make($request->password);
            }

            $user->save();

            return redirect()->route('admin.users')->with([
                'status' => 'success',
                'message' => 'User updated successfully.'
            ]);
        } catch (\Throwable $th) {
            report($th);
            return redirect()->back()->withInput()->with([
                'status' => 'failed',
                'message' => 'Gagal mengupdate user.',
                'server_error' => $th->getMessage(),
            ]);
        }
    }

    public function destroy($id)
    {
        // Logika untuk menghapus pengguna
        try {
            $user = User::findOrFail($id);
            $user->delete();
            return redirect()->route('admin.users')->with([
                'status' => 'success',
                'message' => 'User deleted successfully.'
            ]);
        } catch (\Throwable $th) {
            report($th);
            return redirect()->route('admin.users')->with([
                'status' => 'failed',
                'message' => 'Gagal menghapus user.',
                'server_error' => $th->getMessage(),
            ]);
        }
    }
}
