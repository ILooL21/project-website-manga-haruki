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

    public function create()
    {
        return view('admin.users.form', [
            'formType' => 'create'
        ]);
    }

    public function show($id)
    {
        $user = User::findOrFail($id);
        return view('admin.users.form', [
            'formType' => 'show',
            'userData' => $user
        ]);
    }

    public function edit($id)
    {
        $user = User::findOrFail($id);
        return view('admin.users.form', [
            'formType' => 'edit',
            'userData' => $user
        ]);
    }
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
        ]);

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        return redirect()->route('admin.users')->with([
            'status' => 'success',
            'message' => 'User created successfully.'
        ]);
    }

    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
            'password' => 'nullable|string|min:8|confirmed',
            'role' => 'required|string|in:User,Admin',
        ]);

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
    }

    public function destroy($id)
    {
        // Logika untuk menghapus pengguna
        $user = User::findOrFail($id);
        $user->delete();
        return redirect()->route('admin.users')->with([
            'status' => 'success',
            'message' => 'User deleted successfully.'
        ]);
    }
}
