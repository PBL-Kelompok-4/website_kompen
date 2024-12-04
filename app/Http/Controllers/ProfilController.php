<?php
namespace App\Http\Controllers;

use App\Models\FotoProfilModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class ProfilController extends Controller
{
    public function index()
    {
        $activeMenu = 'profil';
        $breadcrumb = (object) [
            'title' => 'Edit Profil',
            'list' => ['Home', 'Edit Profil']
        ];
        $page = (object) [
            'title' => 'Edit Profil'
        ];
        $user = Auth::user();

        // Get the profile photo associated with the user
        $fotoProfil = FotoProfilModel::where('id_personil', $user->id_personil)->first();

        return view('profil.index', [
            'breadcrumb' => $breadcrumb,
            'page' => $page,
            'user' => $user,
            'fotoProfil' => $fotoProfil,
            'activeMenu' => $activeMenu
        ]);
    }

    public function update(Request $request)
    {
        // Validasi input
        $rules = [
            'nama' => 'required|string|max:255',
            'password' => 'nullable|min:5',
            'foto' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ];
        $request->validate($rules);

        /** @var \App\Models\PersonilAkademikModel $user */
        $user = Auth::user();

        // Update nama
        $user->nama = $request->nama;

        // Update password jika diisi
        if ($request->filled('password')) {
            $user->password = Hash::make($request->password);
        }

        // Simpan perubahan
        $user->save();

        // Update foto profil jika ada
        if ($request->hasFile('foto')) {
            // Hapus foto lama jika ada
            $fotoProfil = FotoProfilModel::where('id_personil', $user->id_personil)->first();
            if ($fotoProfil && $fotoProfil->avatar) {
                Storage::delete('public/avatars/' . $fotoProfil->avatar);
                $fotoProfil->delete();
            }

            // Simpan foto profil baru
            $avatarName = time() . '.' . $request->foto->extension();
            $request->foto->storeAs('public/avatars', $avatarName);

            // Create new FotoProfilModel record
            FotoProfilModel::create([
                'id_personil' => $user->id_personil,
                'avatar' => $avatarName
            ]);
        }

        return redirect('profil/')->with('success', 'Profil berhasil diperbarui!');
    }
}
