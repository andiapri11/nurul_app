<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Jabatan;
use App\Models\Unit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB;

class FinancialAdminController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Only get users specifically with financial roles
        $financialAdmins = User::whereIn('role', ['admin_keuangan', 'kepala_keuangan'])
            ->with(['jabatanUnits.jabatan', 'jabatanUnits.unit'])
            ->distinct()
            ->latest()
            ->get();

        return view('financial_admins.index', compact('financialAdmins'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $jabatans = Jabatan::where('nama_jabatan', 'LIKE', '%Keuangan%')
                           ->orWhere('nama_jabatan', 'LIKE', '%Bendahara%')
                           ->orWhereIn('kode_jabatan', ['kepala_keuangan', 'admin_keuangan'])
                           ->get();
        
        // If no financial jabatans exist, maybe show all 'staff' type jabatans
        if ($jabatans->isEmpty()) {
            $jabatans = Jabatan::where('kategori', 'staff')->get();
        }

        $units = Unit::all();

        return view('financial_admins.create', compact('jabatans', 'units'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'nip' => 'nullable|string|max:50|unique:users,nip',
            'email' => 'required|string|email|max:255|unique:users',
            'username' => 'required|string|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'security_pin' => 'required|digits:6',
            'role' => 'required|in:admin_keuangan,kepala_keuangan',
            'jabatan_ids' => 'required|array',
            'jabatan_ids.*' => 'required|exists:jabatans,id',
            'unit_ids' => 'nullable|array',
            'unit_ids.*' => 'nullable|exists:units,id',
        ]);

        try {
            DB::beginTransaction();

            $user = User::create([
                'name' => $request->name,
                'nip' => $request->nip,
                'email' => $request->email,
                'username' => $request->username,
                'password' => Hash::make($request->password),
                'security_pin' => Hash::make($request->security_pin),
                'role' => $request->role,
                'status' => 'aktif',
            ]);

            // Assign Jabatan with Unit (Multiple)
            $jabatanIds = $request->input('jabatan_ids', []);
            $unitIds = $request->input('unit_ids', []);

            foreach ($jabatanIds as $index => $jabatanId) {
                if (!empty($jabatanId)) {
                    \App\Models\UserJabatanUnit::create([
                        'user_id' => $user->id,
                        'jabatan_id' => $jabatanId,
                        'unit_id' => $unitIds[$index] ?? null,
                    ]);
                }
            }

            // Legacy pivot attach removed as we migrated to UserJabatanUnit ONLY.

            DB::commit();

            return redirect()->route('financial-admins.index')
                ->with('success', 'Admin Keuangan berhasil ditambahkan.');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Gagal menambahkan data: ' . $e->getMessage())->withInput();
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $admin = User::findOrFail($id);
        
        $jabatans = Jabatan::where('nama_jabatan', 'LIKE', '%Keuangan%')
                           ->orWhere('nama_jabatan', 'LIKE', '%Bendahara%')
                           ->orWhereIn('kode_jabatan', ['kepala_keuangan', 'admin_keuangan'])
                           ->orWhere('kategori', 'staff')
                           ->get();
                           
        $units = Unit::all();
        
        // Get current assignment
        $currentAssignment = \App\Models\UserJabatanUnit::where('user_id', $admin->id)->first();
        $currentJabatanId = $currentAssignment ? $currentAssignment->jabatan_id : ($admin->jabatans->first()->id ?? null);
        $currentUnitId = $currentAssignment ? $currentAssignment->unit_id : null;

        return view('financial_admins.edit', compact('admin', 'jabatans', 'units', 'currentJabatanId', 'currentUnitId'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $admin = User::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255',
            'nip' => ['nullable', 'string', 'max:50', Rule::unique('users')->ignore($admin->id)],
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users')->ignore($admin->id)],
            'username' => ['required', 'string', 'max:255', Rule::unique('users')->ignore($admin->id)],
            'password' => 'nullable|string|min:8|confirmed',
            'security_pin' => 'nullable|digits:6',
            'role' => 'required|in:admin_keuangan,kepala_keuangan',
            'jabatan_ids' => 'required|array',
            'jabatan_ids.*' => 'required|exists:jabatans,id',
            'unit_ids' => 'nullable|array',
            'unit_ids.*' => 'nullable|exists:units,id',
        ]);

        try {
            DB::beginTransaction();

            $admin->name = $request->name;
            $admin->nip = $request->nip;
            $admin->email = $request->email;
            $admin->username = $request->username;
            
            if ($request->filled('password')) {
                $admin->password = Hash::make($request->password);
            }

            if ($request->filled('security_pin')) {
                $admin->security_pin = Hash::make($request->security_pin);
            }

            // Enforce role update if not administrator/director
            if (!in_array($admin->role, ['administrator', 'direktur'])) {
                $admin->role = $request->role;
            }
            
            $admin->save();

            // Sync Jabatan/Unit assignments
            // Strategy: Delete all existing and re-create from input
            \App\Models\UserJabatanUnit::where('user_id', $admin->id)->delete();

            $jabatanIds = $request->input('jabatan_ids', []);
            $unitIds = $request->input('unit_ids', []);

            foreach ($jabatanIds as $index => $jabatanId) {
                if (!empty($jabatanId)) {
                    \App\Models\UserJabatanUnit::create([
                        'user_id' => $admin->id,
                        'jabatan_id' => $jabatanId,
                        'unit_id' => $unitIds[$index] ?? null,
                    ]);
                }
            }

            // Legacy pivot sync removed as we migrated to UserJabatanUnit ONLY.

            DB::commit();

            return redirect()->route('financial-admins.index')
                ->with('success', 'Admin Keuangan berhasil diperbarui.');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Gagal memperbarui data: ' . $e->getMessage())->withInput();
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $admin = User::findOrFail($id);
        
        // Prevent deleting self or super admin (if applicable)
        if ($admin->id == auth()->id()) {
            return back()->with('error', 'Tidak dapat menghapus akun sendiri.');
        }

        $admin->delete();

        return redirect()->route('financial-admins.index')
            ->with('success', 'Admin Keuangan berhasil dihapus.');
    }
}
