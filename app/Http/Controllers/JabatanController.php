<?php

namespace App\Http\Controllers;

use App\Models\Jabatan;
use App\Models\Unit;
use Illuminate\Http\Request;

class JabatanController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $jabatans = Jabatan::with('unit')->get()->groupBy(function($item) {
            return $item->unit ? $item->unit->name : 'Umum / Semua Unit';
        });
        
        return view('jabatans.index', compact('jabatans'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $units = Unit::all();
        return view('jabatans.create', compact('units'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'kode_jabatan' => [
                'required',
                'string',
                'max:255',
                \Illuminate\Validation\Rule::unique('jabatans')->where(function ($query) use ($request) {
                    return $query->where('unit_id', $request->unit_id);
                }),
            ],
            'nama_jabatan' => 'required|string|max:255',
            'kategori' => 'required|in:guru,tambahan,staff',
            'tipe' => 'required|in:struktural,fungsional,tambahan',
            'unit_id' => 'nullable|exists:units,id',
        ]);

        Jabatan::create($request->all());

        return redirect()->route('jabatans.index')
            ->with('success', 'Jabatan created successfully.');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Jabatan $jabatan)
    {
        $units = Unit::all();
        return view('jabatans.edit', compact('jabatan', 'units'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Jabatan $jabatan)
    {
        $request->validate([
            'kode_jabatan' => [
                'required',
                'string',
                'max:255',
                \Illuminate\Validation\Rule::unique('jabatans')->ignore($jabatan->id)->where(function ($query) use ($request) {
                    return $query->where('unit_id', $request->unit_id);
                }),
            ],
            'nama_jabatan' => 'required|string|max:255',
            'kategori' => 'required|in:guru,tambahan,staff',
            'tipe' => 'required|in:struktural,fungsional,tambahan',
            'unit_id' => 'nullable|exists:units,id',
        ]);

        $jabatan->update($request->all());

        return redirect()->route('jabatans.index')
            ->with('success', 'Jabatan updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Jabatan $jabatan)
    {
        $jabatan->delete();

        return redirect()->route('jabatans.index')
            ->with('success', 'Jabatan deleted successfully');
    }
}
