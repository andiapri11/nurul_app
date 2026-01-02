<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class FinanceBankAccountController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        if (!in_array(auth()->user()->role, ['administrator', 'kepala_keuangan', 'direktur'])) {
            abort(403, 'Akses Ditolak: Hanya Administrator dan Kepala Keuangan yang dapat mengakses data ini.');
        }
        $accounts = \App\Models\BankAccount::latest()->get();
        return view('finance.bank_accounts.index', compact('accounts'));
    }

    public function store(Request $request)
    {
        if (!in_array(auth()->user()->role, ['administrator', 'kepala_keuangan', 'direktur'])) {
            abort(403);
        }
        $request->validate([
            'security_pin' => 'required',
            'bank_name' => 'required|string|max:255',
            'account_number' => 'required|string|max:255',
            'account_holder' => 'required|string|max:255',
            'balance' => 'required|numeric',
            'description' => 'nullable|string',
        ]);

        if (!\Illuminate\Support\Facades\Hash::check($request->security_pin, auth()->user()->security_pin)) {
            return redirect()->back()->withInput()->with('error', 'Gagal: PIN Keamanan Salah.');
        }

        $data = $request->except('security_pin');
        $data['is_active'] = true;
        \App\Models\BankAccount::create($data);

        return redirect()->route('finance.bank-accounts.index')->with('success', 'Akun Bank berhasil ditambahkan.');
    }

    public function update(Request $request, \App\Models\BankAccount $bankAccount)
    {
        if (!in_array(auth()->user()->role, ['administrator', 'kepala_keuangan', 'direktur'])) {
            abort(403);
        }
        $request->validate([
            'security_pin' => 'required',
            'bank_name' => 'required|string|max:255',
            'account_number' => 'required|string|max:255',
            'account_holder' => 'required|string|max:255',
            'balance' => 'required|numeric',
            'description' => 'nullable|string',
        ]);

        if (!\Illuminate\Support\Facades\Hash::check($request->security_pin, auth()->user()->security_pin)) {
            return redirect()->back()->withInput()->with('error', 'Gagal: PIN Keamanan Salah.');
        }

        $data = $request->except('security_pin');
        $data['is_active'] = $request->has('is_active');
        $bankAccount->update($data);

        return redirect()->route('finance.bank-accounts.index')->with('success', 'Akun Bank berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request, \App\Models\BankAccount $bankAccount)
    {
        if (!in_array(auth()->user()->role, ['administrator', 'kepala_keuangan', 'direktur'])) {
            abort(403);
        }
        $request->validate([
            'security_pin' => 'required',
        ]);

        if (!\Illuminate\Support\Facades\Hash::check($request->security_pin, auth()->user()->security_pin)) {
            return redirect()->back()->with('error', 'Gagal: PIN Keamanan Salah.');
        }

        $bankAccount->delete();
        return redirect()->route('finance.bank-accounts.index')->with('success', 'Akun Bank berhasil dihapus.');
    }
}
