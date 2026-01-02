<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\AcademicCalendar;
use App\Models\Unit;
use App\Models\AcademicYear;
use Illuminate\Support\Facades\Auth;

class KurikulumCalendarController extends Controller
{
    private function canManageCalendar($user)
    {
        return $user->role === 'administrator' || 
               $user->role === 'direktur' || 
               $user->isKurikulum() || 
               $user->hasJabatan('Kepala Sekolah');
    }

    private function getManagementUnits($user)
    {
        if (method_exists($user, 'getLearningManagementUnits')) {
            return $user->getLearningManagementUnits();
        }
        return $user->getKurikulumUnits();
    }

    /**
     * Dashboard Semester View (Mirrors Admin Index)
     */
    public function index(Request $request)
    {
        $user = Auth::user();
        if (!$this->canManageCalendar($user)) abort(403, 'Akses Ditolak');

        $units = $this->getManagementUnits($user);
        if ($units->isEmpty()) {
             // Fallback for edge cases (e.g. admin with no explicit role assignment but role=admin handles it)
             if ($user->role === 'administrator') $units = Unit::all();
             else if ($user->unit_id) $units = Unit::where('id', $user->unit_id)->get(); 
             else return redirect()->route('dashboard')->with('error', 'Akses ditolak.');
        }

        $unit_id = $request->get('unit_id', $units->first()->id);
        if (!$units->contains('id', $unit_id) && $user->role !== 'administrator' && $user->role !== 'direktur') {
             // If selected unit is not in allowed list, reset to first allowed
             $unit_id = $units->first()->id;
        }

        // --- Semester Logic ---
        $academicYears = AcademicYear::orderByDesc('start_year')->get();
        $activeAY = AcademicYear::where('status', 'active')->first();
        $academic_year_id = $request->get('academic_year_id', $activeAY->id ?? $academicYears->first()->id);
        $academicYear = $academicYears->find($academic_year_id);

        $semester = $request->get('semester', 'ganjil'); // ganjil/genap
        
        // Range Calculation
        $semStartYear = ($semester == 'ganjil') ? $academicYear->start_year : $academicYear->end_year;
        $semStartMonth = ($semester == 'ganjil') ? 7 : 1;
        
        $semStartDate = \Carbon\Carbon::createFromDate($semStartYear, $semStartMonth, 1);
        $semEndDate = $semStartDate->copy()->addMonths(5)->endOfMonth();
        
        // Fetch Semester Events
        $semEvents = AcademicCalendar::where('unit_id', $unit_id)
                        ->whereBetween('date', [$semStartDate, $semEndDate])
                        ->get()
                        ->keyBy(fn($item) => $item->date->format('Y-m-d'));

        // Stats
        $semesterStats = ['effective' => 0, 'holiday' => 0, 'activity' => 0];
        $period = \Carbon\CarbonPeriod::create($semStartDate, $semEndDate);
        foreach ($period as $dt) {
            $dStr = $dt->format('Y-m-d');
            $evt = $semEvents[$dStr] ?? null;
            $isWeekend = ($dt->isWeekend());
            
            if ($evt) {
                if ($evt->is_holiday) $semesterStats['holiday']++;
                else $semesterStats['activity']++;
            } elseif (!$isWeekend) {
                $semesterStats['effective']++;
            }
        }

        return view('curriculum.calendar.index', compact(
            'units', 'unit_id', 'academicYears', 'academicYear', 'academic_year_id', 
            'semester', 'semStartDate', 'semEvents', 'semesterStats'
        ));
    }

    /**
     * Monthly Editor View (Mirrors Admin Manage)
     */
    public function manage(Request $request) 
    {
        $user = Auth::user();
        if (!$this->canManageCalendar($user)) abort(403);
        
        $units = $this->getManagementUnits($user);
        if ($units->isEmpty()) {
             if ($user->role === 'administrator') $units = Unit::all();
             else if ($user->unit_id) $units = Unit::where('id', $user->unit_id)->get(); 
             else abort(403);
        }
        
        $unit_id = $request->get('unit_id', $units->first()->id);
        if (!$units->contains('id', $unit_id) && $user->role !== 'administrator' && $user->role !== 'direktur') {
             $unit_id = $units->first()->id; 
        }
        
        $currentUnit = $units->find($unit_id);
        
        $month = (int) $request->get('month', now()->month);
        $year = (int) $request->get('year', now()->year);
        
        $startDate = \Carbon\Carbon::createFromDate($year, $month, 1);
        $endDate = $startDate->copy()->endOfMonth();
        $dates = [];
        for ($date = $startDate; $date->lte($endDate); $date->addDay()) {
            $dates[] = $date->copy();
        }

        $existingRecords = AcademicCalendar::where('unit_id', $unit_id)
                            ->whereMonth('date', $month)
                            ->whereYear('date', $year)
                            ->get()
                            ->keyBy(fn($item) => $item->date->format('Y-m-d'));

        return view('curriculum.calendar.manage', compact(
            'units', 'currentUnit', 'month', 'year', 'dates', 'existingRecords'
        ));
    }

    public function store(Request $request)
    {
        $user = Auth::user();
        if (!$this->canManageCalendar($user)) abort(403);

        $request->validate([
            'unit_id' => 'required|exists:units,id',
            'days' => 'array',
        ]);
        
        // Authorization check for the specific unit
        $allowedUnits = $this->getManagementUnits($user);
        if (!$allowedUnits->contains('id', $request->unit_id) && $user->role !== 'administrator' && $user->role !== 'direktur') {
             abort(403);
        }

        $days = $request->input('days', []);
        
        if (empty($days)) return redirect()->back();

        foreach ($days as $date => $data) {
            AcademicCalendar::where('unit_id', $request->unit_id)->where('date', $date)->delete();
            
            if (isset($data['type']) && $data['type'] !== 'effective') {
                AcademicCalendar::create([
                    'unit_id' => $request->unit_id,
                    'date' => $date,
                    'is_holiday' => ($data['type'] === 'holiday'),
                    'description' => $data['description'] ?? ($data['type'] === 'holiday' ? 'Libur' : 'Kegiatan'),
                ]);
            }
        }
        
        // determine redirect month/year
        $firstDate = array_key_first($days);
        $month = \Carbon\Carbon::parse($firstDate)->month;
        $year = \Carbon\Carbon::parse($firstDate)->year;

        return redirect()->route('curriculum.calendar.manage', [ 
            'unit_id' => $request->unit_id,
            'month' => $month,
            'year' => $year,
        ])->with('success', 'Kalender berhasil diperbarui.');
    }
}
