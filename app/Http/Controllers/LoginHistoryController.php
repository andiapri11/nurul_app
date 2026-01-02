<?php

namespace App\Http\Controllers;

use App\Models\LoginHistory;
use Illuminate\Http\Request;

class LoginHistoryController extends Controller
{
    public function index(Request $request)
    {
        $query = LoginHistory::with(['user', 'siswa']);

        if ($request->has('search')) {
            $search = $request->get('search');
            $query->where(function($q) use ($search) {
                $q->whereHas('user', function($sq) use ($search) {
                    $sq->where('name', 'like', "%{$search}%")
                      ->orWhere('email', 'like', "%{$search}%");
                })->orWhereHas('siswa', function($sq) use ($search) {
                    $sq->where('name', 'like', "%{$search}%")
                      ->orWhere('email', 'like', "%{$search}%")
                      ->orWhere('username', 'like', "%{$search}%");
                });
            });
        }

        $histories = $query->latest('login_at')->paginate(20);
        return view('admin.login_history.index', compact('histories'));
    }

    public function clear()
    {
        LoginHistory::truncate();
        return redirect()->route('login-history.index')->with('success', 'Riwayat login berhasil dibersihkan.');
    }
}
