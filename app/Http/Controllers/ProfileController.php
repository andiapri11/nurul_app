<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;

class ProfileController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        
        // Eager load relationships for staff/guru
        $user->load(['jabatanUnits.jabatan', 'jabatanUnits.unit', 'teachingAssignments.schoolClass', 'teachingAssignments.subject']);
        
        return view('profile.index', compact('user'));
    }

    public function updateInfo(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'gender' => 'nullable|in:L,P',
            'birth_place' => 'nullable|string|max:255',
            'birth_date' => 'nullable|date',
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string',
        ]);

        $user = Auth::user();
        $user->update($request->only(['name', 'gender', 'birth_place', 'birth_date', 'phone', 'address']));

        return back()->with('success', 'Informasi profil berhasil diperbarui.');
    }

    public function updatePhoto(Request $request)
    {
        $request->validate([
            'photo' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $user = Auth::user();

        if ($request->hasFile('photo')) {
            // Delete old photo if exists
            if ($user->photo) {
                if (file_exists(public_path('photos/' . $user->photo))) {
                    unlink(public_path('photos/' . $user->photo));
                }
                if (file_exists(public_path('photos/thumb/' . $user->photo))) {
                    unlink(public_path('photos/thumb/' . $user->photo));
                }
            }

            $imageName = time() . '.' . $request->photo->extension();
            
            $manager = new ImageManager(new Driver());
            $image = $manager->read($request->photo);
            $image->cover(354, 472);
            $image->save(public_path('photos/' . $imageName));
            
            $thumbPath = public_path('photos/thumb');
            if (!file_exists($thumbPath)) mkdir($thumbPath, 0755, true);
            $image->save($thumbPath . '/' . $imageName);
            
            $user->update(['photo' => $imageName]);

            return back()->with('success', 'Foto profil berhasil diperbarui.');
        }

        return back()->with('error', 'Gagal mengunggah foto.');
    }

    public function updatePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required',
            'password' => 'required|min:8|confirmed',
        ], [
            'password.confirmed' => 'Konfirmasi kata sandi baru tidak cocok.',
            'password.min' => 'Kata sandi minimal 8 karakter.',
            'current_password.required' => 'Kata sandi saat ini wajib diisi.',
            'password.required' => 'Kata sandi baru wajib diisi.',
        ]);

        $user = Auth::user();

        if (!Hash::check($request->current_password, $user->password)) {
            return back()->with('error', 'Kata sandi saat ini salah.');
        }

        $user->update([
            'password' => Hash::make($request->password),
            'plain_password' => $request->password
        ]);

        return back()->with('success', 'Kata sandi berhasil diperbarui.');
    }

    public function updatePin(Request $request)
    {
        if (!in_array(Auth::user()->role, ['administrator', 'direktur', 'admin_keuangan', 'kepala_keuangan'])) {
            abort(403, 'Akses Ditolak: Anda tidak memiliki wewenang untuk mengatur PIN Keamanan (Financial).');
        }
        $request->validate([
            'password' => 'required',
            'security_pin' => 'required|digits:6|confirmed',
        ], [
            'security_pin.digits' => 'PIN harus berupa 6 digit angka.',
            'security_pin.confirmed' => 'Konfirmasi PIN tidak cocok.',
            'password.required' => 'Kata sandi diperlukan untuk mengubah PIN.',
        ]);

        $user = Auth::user();

        if (!Hash::check($request->password, $user->password)) {
            return back()->with('error', 'Kata sandi salah.');
        }

        $user->update([
            'security_pin' => Hash::make($request->security_pin)
        ]);

        return back()->with('success', 'PIN Keamanan berhasil diperbarui.');
    }
}
