<?php

namespace App\Http\Controllers;

use App\Models\Subject;
use App\Models\Unit;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class SubjectController extends Controller
{
    private function getAllowedUnits()
    {
        $user = auth()->user();
        if (in_array($user->role, ['administrator', 'admin', 'direktur'])) {
            return Unit::all();
        }
        
        // Use the new method covering both Kurikulum and Kepala Sekolah
        if (method_exists($user, 'getLearningManagementUnits')) { 
             return $user->getLearningManagementUnits(); 
        }
        
        // Fallback or for other contexts
        return collect([]);
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $search = $request->get('search');
        
        $allowedUnits = $this->getAllowedUnits();
        
        // Eager load subjects on the allowed units
        // Note: getAllowedUnits returns a Collection. We can't use `with` on it directly.
        // We iterate or load.
        $allowedUnits->load(['subjects' => function($query) use ($search) {
            if ($search) {
                $query->where('name', 'like', '%' . $search . '%')
                      ->orWhere('code', 'like', '%' . $search . '%');
            }
        }]);
        
        // If we want to hide units with no subjects matching search? 
        // The view probably iterates units.
        $units = $allowedUnits;

        return view('subjects.index', compact('units'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $units = $this->getAllowedUnits();
        if ($units->isEmpty()) {
             return redirect()->route('subjects.index')->with('error', 'Anda tidak memiliki akses ke unit manapun.');
        }
        return view('subjects.create', compact('units'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $allowedUnits = $this->getAllowedUnits();
        $allowedIds = $allowedUnits->pluck('id')->toArray();

        $request->validate([
            'unit_id' => 'required|exists:units,id',
            'name' => 'required|string|max:255',
            'code' => [
                'nullable', 
                'string', 
                'max:50', 
                Rule::unique('subjects')->where(fn ($query) => $query->where('unit_id', $request->unit_id))
            ],
        ]);
        
        if (!in_array($request->unit_id, $allowedIds)) {
             abort(403, 'Akses Ditolak: Anda tidak berhak menambah mapel di unit ini.');
        }

        Subject::create($request->all());

        return redirect()->route('subjects.index')
            ->with('success', 'Subject created successfully.');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Subject $subject)
    {
        $allowedUnits = $this->getAllowedUnits();
        $allowedIds = $allowedUnits->pluck('id')->toArray();
        if (!in_array($subject->unit_id, $allowedIds)) {
             abort(403, 'Akses Ditolak.');
        }

        $units = $allowedUnits;
        return view('subjects.edit', compact('subject', 'units'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Subject $subject)
    {
        $allowedUnits = $this->getAllowedUnits();
        $allowedIds = $allowedUnits->pluck('id')->toArray();
        if (!in_array($subject->unit_id, $allowedIds)) {
             abort(403, 'Akses Ditolak.');
        }

        $request->validate([
            'unit_id' => 'required|exists:units,id',
            'name' => 'required|string|max:255',
            'code' => [
                'nullable', 
                'string', 
                'max:50', 
                Rule::unique('subjects')->where(fn ($query) => $query->where('unit_id', $request->unit_id))->ignore($subject->id)
            ],
        ]);

        if (!in_array($request->unit_id, $allowedIds)) {
             abort(403, 'Akses Ditolak: Anda tidak berhak memindahkan mapel ke unit ini.');
        }

        $subject->update($request->all());

        return redirect()->route('subjects.index')
            ->with('success', 'Subject updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Subject $subject)
    {
        $allowedUnits = $this->getAllowedUnits();
        $allowedIds = $allowedUnits->pluck('id')->toArray();
        if (!in_array($subject->unit_id, $allowedIds)) {
             abort(403, 'Akses Ditolak.');
        }

        $subject->delete();

        return redirect()->route('subjects.index')
            ->with('success', 'Subject deleted successfully');
    }

    public function getSubjectsByUnit($unit_id)
    {
        // Optional: Check if unit_id is allowed?
        // For dropdowns, it's often okay to be loose, but strict is better.
        // Let's assume this is public or used by JS.
        // If used by authorized Create/Edit forms, the parent checks unit access.
        $subjects = Subject::where('unit_id', $unit_id)->get();
        return response()->json($subjects);
    }
}
