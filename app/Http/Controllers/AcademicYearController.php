<?php

namespace App\Http\Controllers;

use App\Models\AcademicYear;
use App\Models\Semester;
use Illuminate\Http\Request;

class AcademicYearController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        if (!auth()->user()->isDirektur()) {
            abort(403, 'Akses ditolak.');
        }
        $academicYears = AcademicYear::orderBy('start_year', 'desc')->get();
        $semesters = Semester::all();
        return view('academic_years.index', compact('academicYears', 'semesters'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        if (!auth()->user()->isDirektur()) {
            abort(403, 'Hanya Administrator yang dapat mengakses halaman ini.');
        }
        return view('academic_years.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        if (!auth()->user()->isDirektur()) {
            abort(403, 'Akses ditolak.');
        }
        $request->validate([
            'start_year' => 'required|integer|min:2000|max:2099',
            'end_year' => 'required|integer|min:2000|max:2099|gt:start_year',
            'status' => 'required|in:active,inactive',
        ]);

        // Check if exists
        $exists = AcademicYear::where('start_year', $request->start_year)
            ->where('end_year', $request->end_year)
            ->exists();
            
        if ($exists) {
            return back()->withErrors(['start_year' => 'Tahun Ajaran ini sudah ada.'])->withInput();
        }

        if ($request->status == 'active') {
            AcademicYear::where('status', 'active')->update(['status' => 'inactive']);
        }

        AcademicYear::create($request->all());

        return redirect()->route('academic-years.index')
            ->with('success', 'Academic Year created successfully.');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(AcademicYear $academicYear)
    {
        if (!auth()->user()->isDirektur()) {
            abort(403, 'Akses ditolak.');
        }
        return view('academic_years.edit', compact('academicYear'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, AcademicYear $academicYear)
    {
        if (!auth()->user()->isDirektur()) {
            abort(403, 'Akses ditolak.');
        }
        $request->validate([
           'start_year' => 'required|integer|min:2000|max:2099',
           'end_year' => 'required|integer|min:2000|max:2099|gt:start_year',
           'status' => 'required|in:active,inactive',
        ]);

        // Check if exists excluding current
        $exists = AcademicYear::where('start_year', $request->start_year)
            ->where('end_year', $request->end_year)
            ->where('id', '!=', $academicYear->id)
            ->exists();
            
        if ($exists) {
            return back()->withErrors(['start_year' => 'Tahun Ajaran ini sudah ada.'])->withInput();
        }

        if ($request->status == 'active') {
             AcademicYear::where('id', '!=', $academicYear->id)
                ->where('status', 'active')
                ->update(['status' => 'inactive']);
        }

        $academicYear->update($request->all());

        return redirect()->route('academic-years.index')
            ->with('success', 'Academic Year updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(AcademicYear $academicYear)
    {
        if (!auth()->user()->isDirektur()) {
            abort(403, 'Akses ditolak.');
        }
        $academicYear->delete();

        return redirect()->route('academic-years.index')
            ->with('success', 'Academic Year deleted successfully');
    }

    public function activateYear($id)
    {
        if (!auth()->user()->isDirektur()) {
            abort(403, 'Akses ditolak.');
        }
        // Deactivate all
        AcademicYear::query()->update(['status' => 'inactive']);
        
        // Activate selected
        AcademicYear::findOrFail($id)->update(['status' => 'active']);

        return redirect()->back()->with('success', 'Tahun pelajaran berhasil diaktifkan.');
    }

    public function activateSemester($id)
    {
        if (!auth()->user()->isDirektur()) {
            abort(403, 'Akses ditolak.');
        }
        // Deactivate all
        Semester::query()->update(['status' => 'inactive']);
        
        // Activate selected
        Semester::findOrFail($id)->update(['status' => 'active']);

        return redirect()->back()->with('success', 'Semester berhasil diaktifkan.');
    }
}
