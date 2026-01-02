<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class KesiswaanDashboardController extends Controller
{
    public function index()
    {
        $allowedUnits = \Auth::user()->getKesiswaanUnits();
        $allowedUnitIds = $allowedUnits->pluck('id')->toArray();

        $pendingViolationsCount = \App\Models\StudentViolation::whereHas('student.schoolClass', function ($q) use ($allowedUnitIds) {
            $q->whereIn('unit_id', $allowedUnitIds);
        })->where('follow_up_status', 'pending')->count();

        $processingViolationsCount = \App\Models\StudentViolation::whereHas('student.schoolClass', function ($q) use ($allowedUnitIds) {
            $q->whereIn('unit_id', $allowedUnitIds);
        })->where('follow_up_status', 'process')->count();
        
        $recentViolations = \App\Models\StudentViolation::with(['student.schoolClass', 'recorder'])
            ->whereHas('student.schoolClass', function ($q) use ($allowedUnitIds) {
                $q->whereIn('unit_id', $allowedUnitIds);
            })
            ->latest('date')
            ->take(5)
            ->get();

        return view('dashboard.kesiswaan', compact('pendingViolationsCount', 'processingViolationsCount', 'recentViolations'));
    }
}
