<?php

namespace App\Http\Controllers;

use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class SettingController extends Controller
{
    public function index()
    {
        $settings = Setting::pluck('value', 'key');
        return view('settings.index', compact('settings'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'app_name' => 'nullable|string|max:255',
            'school_name' => 'nullable|string|max:255',
            'app_address' => 'nullable|string|max:500',
            'app_logo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'app_favicon' => 'nullable|image|mimes:ico,png,jpg|max:512',
            'black_book_points' => 'nullable|integer|min:1',
        ]);

        if ($request->has('app_name')) {
            Setting::updateOrCreate(['key' => 'app_name'], ['value' => $request->app_name]);
        }

        if ($request->has('school_name')) {
            Setting::updateOrCreate(['key' => 'school_name'], ['value' => $request->school_name]);
        }

        if ($request->has('app_address')) {
            Setting::updateOrCreate(['key' => 'app_address'], ['value' => $request->app_address]);
        }
                
        if ($request->has('black_book_points')) {
            Setting::updateOrCreate(['key' => 'black_book_points'], ['value' => $request->black_book_points]);
        }

        if ($request->hasFile('app_logo')) {
            $oldLogo = Setting::where('key', 'app_logo')->first();
            if ($oldLogo && $oldLogo->value) {
                Storage::disk('public')->delete($oldLogo->value);
            }
            $path = $request->file('app_logo')->store('settings', 'public');
            Setting::updateOrCreate(['key' => 'app_logo'], ['value' => $path]);
        }

        if ($request->hasFile('app_favicon')) {
            $oldFavicon = Setting::where('key', 'app_favicon')->first();
            if ($oldFavicon && $oldFavicon->value) {
                Storage::disk('public')->delete($oldFavicon->value);
            }
            $path = $request->file('app_favicon')->store('settings', 'public');
            Setting::updateOrCreate(['key' => 'app_favicon'], ['value' => $path]);
        }

        return redirect()->back()->with('success', 'Pengaturan aplikasi berhasil diperbarui.');
    }
}
