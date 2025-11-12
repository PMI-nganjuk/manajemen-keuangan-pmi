<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    // Konstruktor untuk menerapkan middleware dan kontrol akses berbasis peran (Role-Based Access Control).
    public function __construct()
    {
        $this->middleware('auth');

        $this->middleware(function ($request, $next) {
            if (auth()->user()->role !== User::ROLE_ADMIN) {
                return redirect()->route(auth()->user()->role . '.dashboard');
            }
            return $next($request);
        });
    }

    // Menampilkan daftar (listing) semua pengguna (users).

     /*
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $users = User::all();

        return view('users.index', compact('users'));
    }

    //Menampilkan form untuk membuat pengguna baru.

     /*
     * @return \Illuminate\View\View
     */
    public function create()
    {
        return view('users.create');
    }

    //Menyimpan data pengguna baru yang dibuat ke database.

     /*
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        $request->validate([
            'nama'      => 'required|string|max:255',
            'email'     => 'required|email|unique:users,email',
            'password'  => 'required|min:6',
            'role'      => 'required|in:' . implode(',', User::getRoles()),
            'kategori'  => ['nullable', Rule::in(User::getKategori())],
            'nomer_wa'  => 'nullable|string|max:20',
            'alamat'    => 'nullable|string',
        ]);

        User::create([
            'nama'     => $request->nama,
            'email'    => $request->email,
            'password' => Hash::make($request->password),
            'role'     => $request->role,
            'kategori' => $request->kategori,
            'nomer_wa' => $request->nomer_wa,
            'alamat'   => $request->alamat,
        ]);

        return redirect()->route('users.index')->with('success', 'User berhasil ditambahkan.');
    }

     // Menampilkan form untuk mengedit pengguna yang ditentukan.
     // Menggunakan Route Model Binding untuk resolusi otomatis User.

     /*
     * @param  \App\Models\User  $user
     * @return \Illuminate\View\View
     */
    public function edit(User $user)
    {
        return view('users.edit', compact('user'));
    }

    // Memperbarui (update) data pengguna yang ditentukan di database.

    /*
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, User $user)
    {
        $rules = [
            'nama'      => 'required|string|max:255',
            'email'     => ['required', 'email', Rule::unique('users', 'email')->ignore($user->id)],
            'role'      => 'required|in:' . implode(',', User::getRoles()),
            'kategori'  => ['nullable', Rule::in(User::getKategori())],
            'nomer_wa'  => 'nullable|string|max:20',
            'alamat'    => 'nullable|string',
        ];

        if ($request->filled('password')) {
            $rules['password'] = 'min:6';
        }

        $request->validate($rules);

        $user->update([
            'nama'     => $request->nama,
            'email'    => $request->email,
            'role'     => $request->role,
            'kategori' => $request->kategori,
            'nomer_wa' => $request->nomer_wa,
            'alamat'   => $request->alamat,
        ]);

        if ($request->filled('password')) {
            $user->update([
                'password' => Hash::make($request->password)
            ]);
        }

        return redirect()->route('users.index')->with('success', 'User berhasil diperbarui.');
    }

    // Menghapus pengguna yang ditentukan dari database.

     /*
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(User $user)
    {
        $user->delete();

        return redirect()->route('users.index')->with('success', 'User berhasil dihapus.');
    }
}
