<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class RegisterController extends Controller
{
    public function show()
    {
        return view('auth.register');
    }

    public function register(Request $request)
    {
        $validated = $request->validate([
            // Nama: hanya huruf dan spasi, min 3 karakter
            'name' => ['required', 'string', 'min:3', 'max:100', 'regex:/^[a-zA-Z\s]+$/'],
            
            // Email: format valid, harus @gmail.com, dan unik
            'email' => ['required', 'string', 'email', 'max:100', 'unique:users,email', 'regex:/^[a-zA-Z0-9._%+-]+@gmail\.com$/i'],
            
            // Password: min 8 karakter, harus ada huruf besar, huruf kecil, angka, dan karakter khusus
            'password' => [
                'required', 
                'string', 
                'min:8',
                'max:50',
                'confirmed',
                'regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[!@#$%^&*(),.?":{}|<>]).+$/'
            ],
            
            // Alamat: min 10 kata, min 30 karakter
            'address' => ['required', 'string', 'min:30', 'max:500', function ($attribute, $value, $fail) {
                $wordCount = str_word_count($value);
                if ($wordCount < 10) {
                    $fail('Alamat harus minimal 10 kata (saat ini: ' . $wordCount . ' kata).');
                }
            }],
            
            // Nomor HP: format +62 diikuti 8-15 digit
            'phone' => ['required', 'string', 'regex:/^\+62[0-9]{8,15}$/'],
        ], [
            'name.required' => 'Nama lengkap wajib diisi.',
            'name.min' => 'Nama minimal 3 karakter.',
            'name.max' => 'Nama maksimal 100 karakter.',
            'name.regex' => 'Nama hanya boleh berisi huruf dan spasi.',
            'email.required' => 'Email wajib diisi.',
            'email.email' => 'Format email tidak valid.',
            'email.regex' => 'Email harus menggunakan domain @gmail.com.',
            'email.unique' => 'Email sudah terdaftar. Silakan gunakan email lain.',
            'password.required' => 'Password wajib diisi.',
            'password.min' => 'Password minimal 8 karakter.',
            'password.max' => 'Password maksimal 50 karakter.',
            'password.confirmed' => 'Konfirmasi password tidak cocok.',
            'password.regex' => 'Password harus mengandung huruf besar, huruf kecil, angka, dan karakter khusus.',
            'address.required' => 'Alamat wajib diisi.',
            'address.min' => 'Alamat minimal 30 karakter.',
            'address.max' => 'Alamat maksimal 500 karakter.',
            'phone.required' => 'Nomor HP wajib diisi.',
            'phone.regex' => 'Format nomor HP harus dimulai dengan +62 dan diikuti 8-15 digit angka. Contoh: +6281234567890',
        ]);

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'address' => $validated['address'],
            'phone' => $validated['phone'],
            'role' => 'pelanggan',
        ]);

        Auth::login($user);

        return redirect()->route('dashboard.pelanggan');
    }
}


