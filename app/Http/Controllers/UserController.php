<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    // Menampilkan daftar (listing) semua pengguna (users).

     /*
     * @return \Illuminate\View\View
     */
    public function index()
    {
        //
    }

    //Menampilkan form untuk membuat pengguna baru.

     /*
     * @return \Illuminate\View\View
     */
    public function create()
    {
        //
    }

    //Menyimpan data pengguna baru yang dibuat ke database.

     /*
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        //
    }

     // Menampilkan form untuk mengedit pengguna yang ditentukan.
     // Menggunakan Route Model Binding untuk resolusi otomatis User.

     /*
     * @param  \App\Models\User  $user
     * @return \Illuminate\View\View
     */
    public function edit(User $user)
    {
        //
    }

    // Memperbarui (update) data pengguna yang ditentukan di database.

    /*
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, User $user)
    {
        //
    }

    // Menghapus pengguna yang ditentukan dari database.

     /*
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(User $user)
    {
        //
    }
}
