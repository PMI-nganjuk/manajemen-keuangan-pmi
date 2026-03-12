<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    /**
     * Show daftar user
     */
    public function index(): View
    {
        $users = User::query()->latest()->get();

        return view('users.index', [
            'users' => $users
        ]);
    }

    /**
     * Show form create user
     */
    public function create(): View
    {
        return view('users.create');
    }

    /**
     * Save user baru
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'nama'      => 'required|string|max:255',
            'email'     => 'required|email|unique:users,email',
            'password'  => 'required|min:6',
            'role'      => ['required', Rule::in(User::getRoles())],
            'kategori'  => ['nullable', Rule::in(User::getKategori())],
            'nomer_wa'  => 'nullable|string|max:20',
            'alamat'    => 'nullable|string',
        ]);

        $validated['password'] = Hash::make($validated['password']);

        User::query()->create($validated);

        return redirect()
            ->route('users.index')
            ->with('success', 'User berhasil ditambahkan.');
    }

    /**
     * Form edit user
     */
    public function edit(User $user): View
    {
        return view('users.edit', [
            'user' => $user
        ]);
    }

    /**
     * Update user
     */
    public function update(Request $request, User $user): RedirectResponse
    {
        $validated = $request->validate([
            'nama'      => 'required|string|max:255',
            'email'     => ['required', 'email', Rule::unique('users', 'email')->ignore($user->id)],
            'role'      => ['required', Rule::in(User::getRoles())],
            'kategori'  => ['nullable', Rule::in(User::getKategori())],
            'nomer_wa'  => 'nullable|string|max:20',
            'alamat'    => 'nullable|string',
            'password'  => 'nullable|min:6',
        ]);

        if (!empty($validated['password'])) {
            $validated['password'] = Hash::make($validated['password']);
        } else {
            unset($validated['password']);
        }

        $user->update($validated);

        return redirect()
            ->route('users.index')
            ->with('success', 'User berhasil diperbarui.');
    }

    /**
     * Hapus user
     */
    public function destroy(User $user): RedirectResponse
    {
        $user->delete();

        return redirect()
            ->route('users.index')
            ->with('success', 'User berhasil dihapus.');
    }

    /**
     * Test controller
     */
    public function test()
    {
        return response()->json([
            'status' => 'success',
            'message' => 'Controller berjalan dengan baik'
        ]);
    }
}
