<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    public function index()
    {
        $users = User::where('id', auth()->id())
            ->orWhere('parent_id', auth()->id())
            ->orWhere(function ($q) {
                $q->where('role', 'orang_tua')->whereNull('parent_id');
            })
            ->orderBy('role', 'asc')
            ->orderBy('name', 'asc')
            ->get();

        return view('users.index', compact('users'));
    }

    public function create()
    {
        return view('users.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'username' => 'required|string|max:255|unique:users,username|regex:/^[a-zA-Z0-9_]+$/',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6|confirmed',
            'role' => 'required|in:orang_tua,anak',
        ]);

        $validated['password'] = Hash::make($validated['password']);
        $validated['parent_id'] = $validated['role'] === 'anak' ? auth()->id() : null;

        User::create($validated);

        return redirect()->route('users.index')->with('success', 'Anggota berhasil ditambahkan.');
    }

    public function edit(string $id)
    {
        $user = $this->getEditableUser($id);
        return view('users.edit', compact('user'));
    }

    public function update(Request $request, string $id)
    {
        $user = $this->getEditableUser($id);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'username' => ['required', 'string', 'max:255', Rule::unique('users')->ignore($user->id), 'regex:/^[a-zA-Z0-9_]+$/'],
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users')->ignore($user->id)],
            'role' => 'required|in:orang_tua,anak',
            'password' => 'nullable|string|min:6|confirmed',
        ]);

        if (!empty($validated['password'])) {
            $validated['password'] = Hash::make($validated['password']);
        } else {
            unset($validated['password']);
        }

        if ($validated['role'] === 'anak') {
            $validated['parent_id'] = auth()->id();
        } else {
            $validated['parent_id'] = null;
        }

        $user->update($validated);

        return redirect()->route('users.index')->with('success', 'Anggota berhasil diperbarui.');
    }

    public function destroy(string $id)
    {
        $user = $this->getEditableUser($id);

        if ($user->id === auth()->id()) {
            return redirect()->route('users.index')->with('error', 'Tidak dapat menghapus akun sendiri.');
        }

        $user->delete();

        return redirect()->route('users.index')->with('success', 'Anggota berhasil dihapus.');
    }

    private function getEditableUser(string $id): User
    {
        $user = User::where('id', $id)
            ->where(function ($q) {
                $q->where('id', auth()->id())->orWhere('parent_id', auth()->id());
            })
            ->firstOrFail();

        return $user;
    }
}
