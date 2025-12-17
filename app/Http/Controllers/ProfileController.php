<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class ProfileController extends Controller
{
    /**
     * Show the user's profile.
     */
    public function edit()
    {
        $role = Auth::user()->role;
        $layout = match ($role) {
            'admin' => 'admin.layout',
            'kasir' => 'kasir.layout',
            'pemilik' => 'pemilik.layout',
            default => 'pelanggan.layout',
        };

        return view('profile.edit', [
            'user' => Auth::user(),
            'layout' => $layout,
        ]);
    }

    /**
     * Update the user's profile.
     */
    public function update(Request $request)
    {
        $user = Auth::user();

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users')->ignore($user->id), 'regex:/^[a-zA-Z0-9._%+-]+@gmail\.com$/'],
            'phone' => ['nullable', 'string', 'max:20', 'regex:/^[0-9+]*$/'],
            'address' => ['nullable', 'string', 'max:500'],
            'avatar' => ['nullable', 'image', 'max:2048'], // Max 2MB
        ], [
            'email.regex' => 'Email harus menggunakan @gmail.com',
            'phone.regex' => 'Nomor telepon hanya boleh berisi angka',
        ]);

        if ($request->hasFile('avatar')) {
            // Delete old avatar if exists
            if ($user->avatar) {
                Storage::disk('public')->delete($user->avatar);
            }

            $path = $request->file('avatar')->store('avatars', 'public');
            $validated['avatar'] = $path;
            
            \Log::info('Avatar uploaded successfully', ['path' => $path, 'user_id' => $user->id]);
        }

        $user->update($validated);
        
        // Refresh user instance to ensure session has latest data
        $user->refresh();

        return back()->with('success', 'Profil berhasil diperbarui!');
    }

    /**
     * Delete the user's avatar.
     */
    public function deleteAvatar()
    {
        $user = Auth::user();

        if ($user->avatar) {
            Storage::disk('public')->delete($user->avatar);
            $user->update(['avatar' => null]);
            return back()->with('success', 'Foto profil berhasil dihapus!');
        }

        return back()->with('error', 'Tidak ada foto untuk dihapus.');
    }
}
