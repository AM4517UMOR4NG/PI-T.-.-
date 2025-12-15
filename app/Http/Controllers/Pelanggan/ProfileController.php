<?php

namespace App\Http\Controllers\Pelanggan;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Hash;

class ProfileController extends Controller
{
    public function show()
    {
        $user = auth()->user();
        return view('pelanggan.profile.show', compact('user'));
    }

    public function edit()
    {
        $user = auth()->user();
        return view('pelanggan.profile.edit', compact('user'));
    }

    public function update(Request $request)
    {
        $user = auth()->user();
        
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email,' . $user->id],
            'phone' => ['required', 'string', 'max:30', 'regex:/^(\+62|62|0)[0-9]{8,15}$/'],
            'address' => ['required', 'string', 'max:500', 'min:5'],
            'current_password' => ['nullable', 'string'],
            'password' => ['nullable', 'string', 'min:8', 'confirmed'],
            'redirect_back' => ['nullable', 'string'],
        ], [
            'phone.required' => 'Nomor HP wajib diisi untuk melakukan pemesanan.',
            'phone.regex' => 'Format nomor HP tidak valid. Contoh: 081234567890 atau +6281234567890',
            'address.required' => 'Alamat wajib diisi untuk melakukan pemesanan.',
            'address.min' => 'Alamat minimal 5 karakter.',
        ]);

        // Update basic info
        $user->update([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'phone' => $validated['phone'],
            'address' => $validated['address'],
        ]);

        // Update password if provided
        if (!empty($validated['password'])) {
            if (!$validated['current_password'] || !Hash::check($validated['current_password'], $user->password)) {
                return back()->withErrors(['current_password' => 'Password saat ini tidak sesuai.']);
            }
            
            $user->update([
                'password' => Hash::make($validated['password']),
            ]);
        }

        // Check if there's a redirect URL from the form
        if (!empty($validated['redirect_back'])) {
            return redirect($validated['redirect_back'])->with('status', '✅ Profil berhasil diperbarui! Silakan lanjutkan pemesanan Anda.');
        }

        // Check if there's a redirect URL in session
        $redirectUrl = session('redirect_after_update');
        if ($redirectUrl) {
            session()->forget('redirect_after_update');
            return redirect($redirectUrl)->with('status', '✅ Profil berhasil diperbarui! Silakan lanjutkan pemesanan Anda.');
        }
        
        return redirect()->route('pelanggan.profile.show')->with('status', '✅ Profil berhasil diperbarui! Sekarang Anda bisa melakukan pemesanan rental.');
    }
}
