<?php

namespace App\Http\Controllers;

use App\Models\Schedule;
use App\Models\Unit;
use App\Models\SchoolClass;
use App\Models\TeachingAssignment;
use App\Models\TimeSlot;
use Illuminate\Http\Request;

class ScheduleController extends Controller
{
    private function canManageSchedule() {
        $user = auth()->user();
        return $user->role === 'administrator' || $user->role === 'admin' || $user->role === 'direktur' || $user->isKurikulum() || $user->hasJabatan('Kepala Sekolah');
    }

    private function getManagementUnits() {
        $user = auth()->user();
        if (in_array($user->role, ['administrator', 'admin', 'direktur'])) {
            return Unit::all();
        }
        
        // Use the proper helper for Kurikulum AND Kepala Sekolah
        if (method_exists($user, 'getLearningManagementUnits')) {
            return $user->getLearningManagementUnits();
        }
        
        return $user->getKurikulumUnits(); 
    }

    private function getViewUnits() {
         $user = auth()->user();
         if (in_array($user->role, ['administrator', 'admin', 'direktur'])) {
            return Unit::all();
        }
        // For View, return units where user has ANY assignment (Jabatan or Teaching)
        // logic similar to GuruKaryawanController or just JabatanUnits
        return Unit::whereIn('id', $user->jabatanUnits()->pluck('unit_id')->unique())->get();
    }

    /**
     * Show the settings page for schedule slots.
     */
    public function settings(Request $request)
    {
        if (!$this->canManageSchedule()) {
             abort(403, 'Akses Ditolak.');
        }

        $units = $this->getManagementUnits();
        $selectedUnitId = $request->unit_id;
        
        if (!$selectedUnitId && $units->count() > 0) {
            $selectedUnitId = $units->first()->id;
        }

        $timeSlots = [];
        if ($selectedUnitId) {
             // Validate access
             if (!$units->contains('id', $selectedUnitId)) {
                 abort(403);
             }
            $timeSlots = TimeSlot::where('unit_id', $selectedUnitId)
                ->orderBy('start_time')
                ->get();
        }

        return view('schedules.settings', compact('units', 'selectedUnitId', 'timeSlots'));
    }

    public function massUpdate(Request $request)
    {
        if (!$this->canManageSchedule()) {
            abort(403, 'Akses Ditolak.');
        }

        if (!$request->class_id) {
            return redirect()->route('schedules.index')->with('error', 'Pilih Kelas terlebih dahulu.');
        }

        $class = SchoolClass::with(['unit', 'academicYear'])->findOrFail($request->class_id);
        
        // Authorization check
        $allowedUnits = $this->getManagementUnits();
        if (!$allowedUnits->contains('id', $class->unit_id)) {
             return redirect()->route('schedules.index')->with('error', 'Akses Ditolak.');
        }

        $days = ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat'];
        
        $timeSlots = TimeSlot::where('unit_id', $class->unit_id)
            ->orderBy('start_time')
            ->get();

        $assignments = TeachingAssignment::where('class_id', $class->id)
            ->with(['subject', 'user'])
            ->get();

        $existingSchedules = Schedule::where('class_id', $class->id)
            ->orderBy('day')
            ->orderBy('start_time')
            ->get();

        $gridRows = [];
        
        if ($existingSchedules->count() > 0) {
            // EDIT MODE: Group existing schedules into rows
            $byDay = [];
            foreach ($days as $day) {
                // We use values() to reset indices for easier row-based access
                $byDay[$day] = $existingSchedules->where('day', $day)->values();
            }

            // Find how many rows we need to show all data
            $rowCount = max(array_values(array_map('count', $byDay)));

            for ($i = 0; $i < $rowCount; $i++) {
                $row = [];
                foreach ($days as $day) {
                    if (isset($byDay[$day][$i])) {
                        $s = $byDay[$day][$i];
                        $assignmentId = '';
                        if ($s->is_break) {
                            $assignmentId = 'break';
                        } else {
                            $match = $assignments->where('subject_id', $s->subject_id)
                                               ->where('user_id', $s->user_id)
                                               ->first();
                            $assignmentId = $match ? $match->id : '';
                        }

                        $row[$day] = [
                            'start' => \Carbon\Carbon::parse($s->start_time)->format('H:i'),
                            'end' => \Carbon\Carbon::parse($s->end_time)->format('H:i'),
                            'assignment_id' => $assignmentId,
                            'break_name' => $s->break_name,
                            'is_break' => $s->is_break
                        ];
                    } else {
                        // Empty slot for this day in this row position
                        $row[$day] = [
                            'start' => '', 'end' => '', 'assignment_id' => '', 'break_name' => '', 'is_break' => false
                        ];
                    }
                }
                $gridRows[] = $row;
            }
        } else {
            // INPUT MODE (NEW): Start with an empty grid so user adds rows manually
            // We can optionally add ONE empty row to start with, or none. 
            // The user requested "kosongkan biar menambah baris sendiri", let's give 1 starting row.
            $row = [];
            foreach ($days as $day) {
                $row[$day] = ['start' => '', 'end' => '', 'assignment_id' => '', 'break_name' => '', 'is_break' => false];
            }
            $gridRows[] = $row;
        }

        $breakSlots = $timeSlots->where('is_break', true);

        return view('schedules.mass_update', compact('class', 'days', 'timeSlots', 'breakSlots', 'assignments', 'gridRows'));
    }

    public function massStore(Request $request)
    {
        if (!$this->canManageSchedule()) {
            abort(403, 'Akses Ditolak.');
        }

        $classId = $request->class_id;
        $class = SchoolClass::findOrFail($classId);

        // schedules[index][days][Senin][start_time]
        // schedules[index][days][Senin][end_time]
        // schedules[index][days][Senin][assignment_id]
        // schedules[index][days][Senin][break_name]
        $rows = $request->input('schedules', []);

        \DB::beginTransaction();
        try {
            // Overwrite all for this class to ensure consistency
            Schedule::where('class_id', $classId)->delete();

            foreach ($rows as $row) {
                $daysData = $row['days'] ?? [];

                foreach ($daysData as $day => $data) {
                    $assignmentId = $data['assignment_id'] ?? null;
                    $startTime = $data['start_time'] ?? null;
                    $endTime = $data['end_time'] ?? null;

                    if (empty($assignmentId) || !$startTime || !$endTime) continue;

                    $insertData = [
                        'unit_id' => $class->unit_id,
                        'class_id' => $classId,
                        'day' => $day,
                        'start_time' => $startTime,
                        'end_time' => $endTime,
                    ];

                    if ($assignmentId === 'break') {
                        $insertData['is_break'] = true;
                        $insertData['break_name'] = $data['break_name'] ?? 'ISTIRAHAT';
                        $insertData['subject_id'] = null;
                        $insertData['user_id'] = null;
                    } else {
                        $assignment = TeachingAssignment::find($assignmentId);
                        if ($assignment) {
                            $insertData['is_break'] = false;
                            $insertData['break_name'] = null;
                            $insertData['subject_id'] = $assignment->subject_id;
                            $insertData['user_id'] = $assignment->user_id;
                        } else {
                            continue;
                        }
                    }

                    Schedule::create($insertData);
                }
            }

            \DB::commit();
            return redirect()->route('schedules.index', ['unit_id' => $class->unit_id, 'class_id' => $class->id])
                ->with('success', 'Jadwal berhasil diperbarui secara massal.');
        } catch (\Exception $e) {
            \DB::rollBack();
            return back()->with('error', 'Gagal menyimpan jadwal: ' . $e->getMessage())->withInput();
        }
    }

    public function storeTimeSlot(Request $request)
    {
        if (!$this->canManageSchedule()) abort(403);
        
        $request->validate([
            'unit_id' => 'required|exists:units,id',
            'name' => 'required|string',
            'start_time' => 'required',
            'end_time' => 'required|after:start_time',
            'is_break' => 'boolean'
        ]);

        // Validate Unit Access
        $units = $this->getManagementUnits();
        if (!$units->contains('id', $request->unit_id)) abort(403);

        TimeSlot::create([
            'unit_id' => $request->unit_id,
            'name' => $request->name,
            'start_time' => $request->start_time,
            'end_time' => $request->end_time,
            'is_break' => $request->has('is_break'),
        ]);

        return back()->with('success', 'Slot waktu berhasil ditambahkan.');
    }

    public function updateTimeSlot(Request $request, TimeSlot $timeSlot)
    {
        if (!$this->canManageSchedule()) abort(403);
        
        $units = $this->getManagementUnits();
        if (!$units->contains('id', $timeSlot->unit_id)) abort(403);

        $request->validate([
            'name' => 'required|string',
            'start_time' => 'required',
            'end_time' => 'required|after:start_time',
            'is_break' => 'boolean'
        ]);

        $oldStartTime = $timeSlot->start_time;
        $oldEndTime = $timeSlot->end_time;

        $timeSlot->update([
            'name' => $request->name,
            'start_time' => $request->start_time,
            'end_time' => $request->end_time,
            'is_break' => $request->has('is_break'),
        ]);

        Schedule::where('unit_id', $timeSlot->unit_id)
            ->where('start_time', $oldStartTime)
            ->where('end_time', $oldEndTime)
            ->update([
                'start_time' => $request->start_time,
                'end_time' => $request->end_time
            ]);

        return back()->with('success', 'Slot waktu berhasil diperbarui.');
    }

    public function destroyTimeSlot(TimeSlot $timeSlot)
    {
        if (!$this->canManageSchedule()) abort(403);
        $units = $this->getManagementUnits();
        if (!$units->contains('id', $timeSlot->unit_id)) abort(403);
        
        $timeSlot->delete();
        return back()->with('success', 'Slot waktu dihapus.');
    }

    public function index(Request $request)
    {
        $user = auth()->user();

        // Get Viewable Units
        $units = $this->getViewUnits();

        $selectedUnitId = $request->unit_id;
        
        // VALIDASI AKSES UNIT
        if ($selectedUnitId && !$units->contains('id', $selectedUnitId)) {
             $selectedUnitId = $units->first()->id ?? null;
        }

        if (!$selectedUnitId && $units->count() > 0) {
            $selectedUnitId = $units->first()->id;
        }

        // ACADEMIC YEAR FILTER
        $academicYears = \App\Models\AcademicYear::orderBy('start_year', 'desc')->get();
        $activeYear = $academicYears->where('status', 'active')->first();
        
        $selectedAcademicYearId = $request->academic_year_id;
        if (!$selectedAcademicYearId && $activeYear) {
             $selectedAcademicYearId = $activeYear->id;
        }

        $selectedClassId = $request->class_id;

        $classes = [];
        $schedules = [];

        if ($selectedUnitId) {
            $classQuery = SchoolClass::where('unit_id', $selectedUnitId);
            if ($selectedAcademicYearId) {
                $classQuery->where('academic_year_id', $selectedAcademicYearId);
            }
            $classes = $classQuery->get();
        }

        $currentClass = null;
        if ($selectedClassId) {
            // Ensure class belongs to selected unit (security)
            $currentClass = SchoolClass::with('academicYear')->find($selectedClassId);
            if (!$currentClass || $currentClass->unit_id != $selectedUnitId) {
                $currentClass = null;
                $schedules = collect([]);
            } else {
                $rawSchedules = Schedule::where('class_id', $selectedClassId)
                    ->with(['subject', 'teacher'])
                    ->orderByRaw("FIELD(day, 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat')")
                    ->orderBy('start_time')
                    ->get();

                $schedules = $rawSchedules->groupBy('day');
            }
        }

        return view('schedules.index', compact('units', 'classes', 'schedules', 'selectedUnitId', 'selectedClassId', 'currentClass', 'academicYears', 'selectedAcademicYearId'));
    }

    public function create(Request $request)
    {
        if (!$this->canManageSchedule()) {
            abort(403, 'Anda tidak memiliki akses untuk menambah jadwal.');
        }

        if (!$request->class_id) {
            return redirect()->route('schedules.index')->with('error', 'Pilih Kelas terlebih dahulu untuk menambah jadwal.');
        }

        $class = SchoolClass::findOrFail($request->class_id);
        
        if ($class->academicYear && $class->academicYear->status !== 'active') {
             return redirect()->route('schedules.index', ['unit_id' => $class->unit_id, 'class_id' => $class->id])
                 ->with('error', 'Tidak dapat menambah jadwal: Kelas ini dari Tahun Ajaran Non-Aktif (Arsip).');
        }
        
        // VALIDASI UNIT OTORITAS
        $allowedUnits = $this->getManagementUnits();
        if (!$allowedUnits->contains('id', $class->unit_id)) {
             return redirect()->route('schedules.index')->with('error', 'Anda tidak memiliki akses Management di Unit kelas ini.');
        }
        
        $assignments = TeachingAssignment::where('class_id', $class->id)
            ->with(['subject', 'user'])
            ->get();

        $timeSlots = TimeSlot::where('unit_id', $class->unit_id)
            ->orderBy('start_time')
            ->get();

        return view('schedules.create', compact('class', 'assignments', 'timeSlots'));
    }

    public function store(Request $request)
    {
        if (!$this->canManageSchedule()) {
            abort(403, 'Anda tidak memiliki akses.');
        }

        $request->validate([
            'class_id' => 'required|exists:classes,id',
            'assignment_id' => 'required',
            'day' => 'required',
            'start_time' => 'required',
            'end_time' => 'required|after:start_time',
        ]);

        $class = SchoolClass::findOrFail($request->class_id);

        $allowedUnits = $this->getManagementUnits();
        if (!$allowedUnits->contains('id', $class->unit_id)) {
             abort(403, 'Anda tidak berhak mengatur jadwal di unit ini.');
        }

        $isBreak = $request->assignment_id === 'break';
        $subjectId = null;
        $teacherId = null;

        if (!$isBreak) {
            $parts = explode('-', $request->assignment_id);
            if (count($parts) < 2) {
                 return back()->withInput()->with('error', 'Format Mata Pelajaran tidak valid.');
            }
            $subjectId = $parts[0];
            $teacherId = $parts[1];
            
            $clash = Schedule::where('user_id', $teacherId)
                ->where('day', $request->day)
                ->where(function ($query) use ($request) {
                    $query->whereBetween('start_time', [$request->start_time, $request->end_time])
                          ->orWhereBetween('end_time', [$request->start_time, $request->end_time])
                          ->orWhere(function ($q) use ($request) {
                              $q->where('start_time', '<=', $request->start_time)
                                ->where('end_time', '>=', $request->end_time);
                          });
                })
                ->with('schoolClass')
                ->first();

            if ($clash) {
                return back()->withInput()->with('error', "BENTROK JADWAL! Guru tersebut sedang mengajar di Kelas {$clash->schoolClass->name} pada jam tersebut.");
            }
        }
        
        Schedule::create([
            'unit_id' => $class->unit_id,
            'class_id' => $class->id,
            'subject_id' => $subjectId,
            'user_id' => $teacherId,
            'day' => $request->day,
            'start_time' => $request->start_time,
            'end_time' => $request->end_time,
            'is_break' => $isBreak,
            'break_name' => $isBreak ? ($request->break_name ?? 'ISTIRAHAT') : null,
        ]);

        return redirect()->route('schedules.index', ['unit_id' => $class->unit_id, 'class_id' => $class->id])
            ->with('success', 'Jadwal berhasil ditambahkan!');
    }

    public function edit(Schedule $schedule)
    {
        if (!$this->canManageSchedule()) abort(403);

        $allowedUnits = $this->getManagementUnits();
        if (!$allowedUnits->contains('id', $schedule->unit_id)) abort(403);

        $class = $schedule->schoolClass;
        
        $assignments = TeachingAssignment::where('class_id', $class->id)
            ->with(['subject', 'user'])
            ->get();

        $timeSlots = TimeSlot::where('unit_id', $class->unit_id)
            ->orderBy('start_time')
            ->get();

        return view('schedules.edit', compact('schedule', 'class', 'assignments', 'timeSlots'));
    }

    public function update(Request $request, Schedule $schedule)
    {
        if (!$this->canManageSchedule()) abort(403);

        $allowedUnits = $this->getManagementUnits();
        if (!$allowedUnits->contains('id', $schedule->unit_id)) abort(403);

        $request->validate([
            'assignment_id' => 'required',
            'day' => 'required',
            'start_time' => 'required',
            'end_time' => 'required|after:start_time',
        ]);

        $isBreak = $request->assignment_id === 'break';
        $subjectId = null;
        $teacherId = null;

        if (!$isBreak) {
            $parts = explode('-', $request->assignment_id);
            if (count($parts) < 2) {
                 return back()->withInput()->with('error', 'Format Mata Pelajaran tidak valid.');
            }
            $subjectId = $parts[0];
            $teacherId = $parts[1];
            
            // Check Clash (Excluding current schedule)
            $clash = Schedule::where('user_id', $teacherId)
                ->where('day', $request->day)
                ->where('id', '!=', $schedule->id) // Exclude current
                ->where(function ($query) use ($request) {
                    $query->whereBetween('start_time', [$request->start_time, $request->end_time])
                          ->orWhereBetween('end_time', [$request->start_time, $request->end_time])
                          ->orWhere(function ($q) use ($request) {
                              $q->where('start_time', '<=', $request->start_time)
                                ->where('end_time', '>=', $request->end_time);
                          });
                })
                ->with('schoolClass')
                ->first();

            if ($clash) {
                return back()->withInput()->with('error', "BENTROK JADWAL! Guru tersebut sedang mengajar di Kelas {$clash->schoolClass->name} pada jam tersebut.");
            }
        }
        
        $schedule->update([
            'subject_id' => $subjectId,
            'user_id' => $teacherId,
            'day' => $request->day,
            'start_time' => $request->start_time,
            'end_time' => $request->end_time,
            'is_break' => $isBreak,
            'break_name' => $isBreak ? ($request->break_name ?? 'ISTIRAHAT') : null,
        ]);

        return redirect()->route('schedules.index', ['unit_id' => $schedule->unit_id, 'class_id' => $schedule->class_id])
            ->with('success', 'Jadwal berhasil diperbarui!');
    }

    public function destroy(Schedule $schedule)
    {
        if (!$this->canManageSchedule()) {
            abort(403, 'Anda tidak memiliki akses.');
        }

        $allowedUnits = $this->getManagementUnits();
        if (!$allowedUnits->contains('id', $schedule->unit_id)) {
             abort(403, 'Anda tidak berhak mengubah jadwal di unit ini.');
        }

        $unitId = $schedule->unit_id;
        $classId = $schedule->class_id;
        $schedule->delete();

        return redirect()->route('schedules.index', ['unit_id' => $unitId, 'class_id' => $classId])
            ->with('success', 'Jadwal dihapus.');
    }

    public function show($id)
    {
        return redirect()->route('schedules.index');
    }

    public function print(Request $request)
    {
        $classId = $request->class_id;
        if (!$classId) {
            return back()->with('error', 'Pilih kelas terlebih dahulu.');
        }

        $class = SchoolClass::with(['unit', 'teacher', 'academicYear'])->findOrFail($classId);
        
        $schedules = Schedule::where('class_id', $classId)
            ->with(['subject', 'teacher'])
            ->orderBy('start_time')
            ->get()
            ->groupBy('day');

        $days = ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat'];

        return view('schedules.print', compact('class', 'schedules', 'days'));
    }
}
