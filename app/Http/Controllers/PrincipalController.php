<?php

namespace App\Http\Controllers;

use App\Models\Unit;
use App\Models\User;
use App\Models\Schedule;
use App\Models\SchoolClass;
use App\Models\ClassCheckin;
use App\Models\AcademicYear;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class PrincipalController extends Controller
{
    use \App\Traits\MonitoringTrait;

    /**
     * Get units assigned to this principal.
     */
    private function getPrincipalUnits()
    {
        return Auth::user()->getManajemenUnits();
    }

    public function index(Request $request = null)
    {
        $request = $request ?: request();
        $units = $this->getPrincipalUnits();
        $unitIds = $units->pluck('id');
        
        // Smarter default: Prefer the user's assigned 'unit_id' if available in authorized units
        $homeUnitId = Auth::user()->unit_id;
        if ($homeUnitId && $unitIds->contains($homeUnitId)) {
            $defaultUnit = $homeUnitId;
        } else {
            // Default to 'all' if multiple units exist, otherwise the single unit ID
            $defaultUnit = $unitIds->count() > 1 ? 'all' : $unitIds->first();
        }
        
        $selectedUnitId = $request->get('unit_id', $defaultUnit);
        
        // Determine scope: Array of IDs to filter by
        $scopeUnitIds = ($selectedUnitId == 'all') ? $unitIds->toArray() : [$selectedUnitId];

        $activeYear = AcademicYear::where('status', 'active')->first();

        // 1. Statistics
        $stats = [
            'total_teachers' => User::whereHas('jabatanUnits', function($q) use ($scopeUnitIds) {
                $q->whereIn('unit_id', $scopeUnitIds);
            })->count(),
            'total_classes' => SchoolClass::whereIn('unit_id', $scopeUnitIds)->count(),
            'total_schedules' => Schedule::whereHas('schoolClass', function($q) use ($scopeUnitIds, $activeYear) {
                $q->whereIn('unit_id', $scopeUnitIds);
                if ($activeYear) {
                    $q->where('academic_year_id', $activeYear->id);
                }
            })->count(),
        ];

        // 2. Today's Checkin Status
        $checkinsToday = ClassCheckin::whereHas('schedule.schoolClass', function($q) use ($scopeUnitIds) {
            $q->whereIn('unit_id', $scopeUnitIds);
        })->whereDate('checkin_time', now())->get();

        $stats['today_present'] = $checkinsToday->where('status', 'ontime')->count();
        $stats['today_late'] = $checkinsToday->where('status', 'late')->count();
        $stats['today_checkins'] = $checkinsToday->count();

        // 3. Current Live Classes (Real-time Monitor)
        $days = [
            'Sunday' => 'Minggu', 'Monday' => 'Senin', 'Tuesday' => 'Selasa', 
            'Wednesday' => 'Rabu', 'Thursday' => 'Kamis', 'Friday' => 'Jumat', 'Saturday' => 'Sabtu'
        ];
        $currentDay = $days[now()->format('l')];
        $currentTime = now()->format('H:i:s');

        // Fetch calendar entries for today to check for holidays/activities
        $todayStr = now()->toDateString();
        $calendars = \App\Models\AcademicCalendar::whereDate('date', $todayStr)->get();
        $globalCals = $calendars->whereNull('unit_id');

        $activeSchedules = Schedule::whereHas('schoolClass', function($q) use ($scopeUnitIds, $activeYear) {
                $q->whereIn('unit_id', $scopeUnitIds);
                if ($activeYear) {
                    $q->where('academic_year_id', $activeYear->id);
                }
            })
            ->where('day', $currentDay)
            ->where('start_time', '<=', $currentTime)
            ->where('end_time', '>=', $currentTime)
            ->with(['schoolClass', 'subject', 'teacher'])
            ->get();
            
        // Filter out schedules if the class/unit is on holiday
        $activeSchedules = $activeSchedules->filter(function($schedule) use ($calendars, $globalCals) {
            $unitId = $schedule->schoolClass->unit_id;
            $classId = $schedule->class_id;
            
            $unitCals = $calendars->where('unit_id', $unitId)->merge($globalCals);
            
            // Priority Check for this specific class
            $cal = $unitCals->first(fn($c) => $c->is_holiday && is_array($c->affected_classes) && in_array($classId, $c->affected_classes));
            if (!$cal) $cal = $unitCals->first(fn($c) => !$c->is_holiday && is_array($c->affected_classes) && in_array($classId, $c->affected_classes));
            if (!$cal) $cal = $unitCals->first(fn($c) => $c->is_holiday && is_null($c->affected_classes));
            // Note: If it's just a school activity (non-holiday), we still show it in live monitor 
            // because teachers might still check in for activities.
            
            if ($cal && $cal->is_holiday) {
                return false; // Skip if holiday
            }
            
            return true;
        });
        
        // Append status
        foreach($activeSchedules as $schedule) {
            $checkin = $checkinsToday->where('schedule_id', $schedule->id)->first();
            $schedule->live_status = $checkin ? 'Hadir' : 'Menunggu';
            $schedule->checkin_time = $checkin ? $checkin->checkin_time : null;
        }

        // 4. Recent Activity
        $recentCheckins = ClassCheckin::with(['user', 'schedule.schoolClass', 'schedule.subject'])
            ->whereHas('schedule.schoolClass', function($q) use ($scopeUnitIds) {
                $q->whereIn('unit_id', $scopeUnitIds);
            })
            ->latest('checkin_time')
            ->take(5)
            ->get();

        // 5. Pending Document Approvals
        $pendingDocumentsCount = \App\Models\TeacherDocumentSubmission::where('status', 'validated')
            ->whereHas('user.jabatanUnits', function($q) use ($scopeUnitIds) {
                $q->whereIn('unit_id', $scopeUnitIds);
            })->count();

        // 6. Monitoring Data
        $monitoringData = $this->getMonitoringData($selectedUnitId);

        return view('principal.index', compact('units', 'selectedUnitId', 'stats', 'recentCheckins', 'activeYear', 'activeSchedules', 'pendingDocumentsCount', 'monitoringData'));
    }

    public function teacherAttendance(Request $request)
    {
        $units = $this->getPrincipalUnits();
        $unitIds = $units->pluck('id');
        $selectedUnitId = $request->get('unit_id', $unitIds->first());

        $activeYear = AcademicYear::where('status', 'active')->first();

        // Fetch teachers with their stats specifically for this unit
        $teachers = User::whereHas('jabatanUnits', function($q) use ($selectedUnitId) {
            $q->where('unit_id', $selectedUnitId);
        })->with(['jabatanUnits' => function($q) use ($selectedUnitId) {
            $q->where('unit_id', $selectedUnitId);
        }])->get();

        // Calculate stats for each teacher
        foreach ($teachers as $teacher) {
            // 1. Weekly Schedules in this Unit
            $teacher->weekly_schedules = Schedule::where('user_id', $teacher->id)
                ->whereHas('schoolClass', function($q) use ($selectedUnitId) {
                    $q->where('unit_id', $selectedUnitId);
                })
                ->when($activeYear, function($q) use ($activeYear) {
                    $q->whereHas('schoolClass', function($sq) use ($activeYear) {
                        $sq->where('academic_year_id', $activeYear->id);
                    });
                })
                ->count();

            // 2. Monthly Checkins in this Unit
            $teacher->monthly_checkins = ClassCheckin::where('user_id', $teacher->id)
                ->whereHas('schedule.schoolClass', function($q) use ($selectedUnitId) {
                    $q->where('unit_id', $selectedUnitId);
                })
                ->whereMonth('checkin_time', now()->month)
                ->whereYear('checkin_time', now()->year)
                ->count();
                
             // 3. Today's Checkin Count
             $teacher->today_checkins = ClassCheckin::where('user_id', $teacher->id)
                ->whereHas('schedule.schoolClass', function($q) use ($selectedUnitId) {
                    $q->where('unit_id', $selectedUnitId);
                })
                ->whereDate('checkin_time', now())
                ->count();
        }

        return view('principal.teachers', compact('units', 'selectedUnitId', 'teachers'));
    }

    public function classStats(Request $request)
    {
        $units = $this->getPrincipalUnits();
        $unitIds = $units->pluck('id');
        $selectedUnitId = $request->get('unit_id', $unitIds->first());

        // Academic Years
        $academicYears = AcademicYear::orderBy('start_year', 'desc')->get();
        $activeYear = $academicYears->where('status', 'active')->first();
        $selectedYearId = $request->get('academic_year_id', $activeYear ? $activeYear->id : null);

        $classes = SchoolClass::where('unit_id', $selectedUnitId)
            ->when($selectedYearId, function($q) use ($selectedYearId) {
                $q->where('academic_year_id', $selectedYearId);
            })
            ->withCount('students')
            ->with(['students' => function($q) {
                $q->select('students.id', 'students.nama_lengkap', 'students.nis', 'students.status')
                  ->where('students.status', 'aktif') // Show only active students
                  ->orderBy('students.nama_lengkap');
            }])
            ->get();

        return view('principal.classes', compact('units', 'selectedUnitId', 'classes', 'activeYear', 'academicYears', 'selectedYearId'));
    }

    /**
     * Document Approval Dashboard for Principal
     */
    public function documents(Request $request)
    {
        $units = $this->getPrincipalUnits();
        $unitIds = $units->pluck('id');
        
        // 1. Fetch Submissions that are 'validated' (waiting for approval)
        $submissions = \App\Models\TeacherDocumentSubmission::with(['request', 'user', 'validator'])
            ->where('status', 'validated')
            ->whereHas('user.jabatanUnits', function($q) use ($unitIds) {
                $q->whereIn('unit_id', $unitIds);
            })
            ->latest('validated_at')
            ->get();

        // 1b. Fetch History (Approved/Rejected) for potential Cancellation/Review
        $historySubmissions = \App\Models\TeacherDocumentSubmission::with(['request', 'user', 'validator'])
            ->whereIn('status', ['approved', 'rejected'])
            ->whereHas('user.jabatanUnits', function($q) use ($unitIds) {
                $q->whereIn('unit_id', $unitIds);
            })
            ->latest('approved_at') // Sort by approval time
            ->limit(50) // Limit to recent history to avoid overloading
            ->get();
            
        // 2. Fetch Requests targeting these units (for Progress Monitoring)
        // Since target_units is JSON Array of IDs ["1", "2"]
        // We need to find requests where ANY of $unitIds is in target_units OR target_units is empty (Global)
        
        // Filter By Academic Year
        $academicYears = \App\Models\AcademicYear::orderBy('start_year', 'desc')->get();
        $activeYear = $academicYears->where('status', 'active')->first();
        
        $selectedYearId = $request->get('academic_year_id', $activeYear ? $activeYear->id : null);

        // 2. Fetch Requests
        $requests = \App\Models\TeacherDocumentRequest::with(['academicYear'])
            ->where('is_active', true)
            ->when($selectedYearId, function($q) use ($selectedYearId) {
                $q->where('academic_year_id', $selectedYearId);
            })
            ->where(function($q) use ($unitIds) {
                // If target_units is null means Global (usually)
                $q->whereNull('target_units')
                  ->orWhereJsonLength('target_units', 0);
                  
                // OR json contains any of the unit IDs
                foreach ($unitIds as $uid) {
                    $q->orWhereJsonContains('target_units', (string)$uid);
                    $q->orWhereJsonContains('target_units', (int)$uid); // Safety for type
                }
            })
            ->latest()
            ->get();
            
        // Calculate basic progress stats for each request relative to THIS Principal's Units
        foreach ($requests as $req) {
            // Count eligible teachers in these specific units
            // This is heavy, maybe simple count for now or reuse Curriculum logic? 
            // Let's just count Submissions from users IN THESE UNITS for this request
            $req->submission_count = $req->submissions()
                ->whereHas('user.jabatanUnits', function($q) use ($unitIds) {
                    $q->whereIn('unit_id', $unitIds);
                })
                ->where('status', '!=', 'rejected') // Exclude rejected
                ->count();
                
            $req->validated_count = $req->submissions()
                ->whereHas('user.jabatanUnits', function($q) use ($unitIds) {
                    $q->whereIn('unit_id', $unitIds);
                })
                ->whereIn('status', ['validated', 'approved'])
                ->count();
                
            $req->approved_count = $req->submissions()
                ->whereHas('user.jabatanUnits', function($q) use ($unitIds) {
                    $q->whereIn('unit_id', $unitIds);
                })
                ->where('status', 'approved')
                ->count();
        }

        return view('principal.documents', compact('units', 'submissions', 'historySubmissions', 'requests', 'academicYears', 'selectedYearId'));
    }

    public function createDocumentRequest()
    {
        $units = $this->getPrincipalUnits();
        $unitIds = $units->pluck('id');
        
        $academicYears = AcademicYear::orderBy('status', 'asc')
                                     ->orderBy('start_year', 'desc')
                                     ->get();
        // Load teachers in principal's units for selection
        $teachers = User::whereIn('role', ['guru', 'karyawan'])
                        ->whereHas('jabatanUnits', function($q) use ($unitIds) {
                            $q->whereIn('unit_id', $unitIds);
                        })
                        ->orderBy('name')
                        ->get();
                        
        return view('principal.document_create', compact('units', 'academicYears', 'teachers'));
    }

    public function storeDocumentRequest(Request $request)
    {
        $units = $this->getPrincipalUnits();
        $unitIds = $units->pluck('id')->toArray();

        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'academic_year_id' => 'required|exists:academic_years,id',
            'semester' => 'required|in:ganjil,genap',
            'due_date' => 'required|date',
            'target_units' => 'required|array',       // Principal MUST select at least one unit (or all authorized)
            'target_units.*' => 'in:' . implode(',', $unitIds),
            'target_users' => 'nullable|array',       // Optional: Specific teachers
            'target_users.*' => 'exists:users,id',
        ]);

        \App\Models\TeacherDocumentRequest::create([
            'title' => $request->title,
            'description' => $request->description,
            'academic_year_id' => $request->academic_year_id,
            'semester' => $request->semester,
            'due_date' => $request->due_date,
            'is_active' => true,
            'created_by' => Auth::id(),
            'target_units' => $request->target_units, // Stored as JSON by cast
            'target_subjects' => [], // Principal requests usually general or strictly user-targeted
            'target_grades' => [],
            'target_users' => $request->target_users ?? [], // Specific teachers
        ]);

        return redirect()->route('principal.documents')->with('success', 'Permintaan dokumen berhasil dibuat.');
    }

    public function showDocumentRequest($id)
    {
        $request = \App\Models\TeacherDocumentRequest::with(['academicYear', 'creator'])->findOrFail($id);
        
        // Authorization check? Assuming list is already filtered, but ID direct access needs check.
        // Principal sees global requests (from admin/kurikulum) or specific ones.
        // For now, allow viewing if they have access to the target unit?
        // Let's implement loose check or stick to controller logic validation if needed.
        
        // Get submissions stats specific to this request
        $units = $this->getPrincipalUnits();
        $unitIds = $units->pluck('id');
        
        // Filter submissions by Principal's units
        $submissions = $request->submissions()
            ->with(['user.jabatanUnits', 'validator', 'approver'])
            ->whereHas('user.jabatanUnits', function($q) use ($unitIds) {
                $q->whereIn('unit_id', $unitIds);
            })
            ->latest()
            ->get();
            
        return view('principal.document_show', compact('request', 'submissions'));
    }

    public function editDocumentRequest($id)
    {
        $request = \App\Models\TeacherDocumentRequest::findOrFail($id);
        
        // Ensure only creator or special role can edit? 
        // Or if Principal created it.
        if ($request->created_by != Auth::id()) {
             // For now, restrict editing to creator.
             return redirect()->route('principal.documents')->with('error', 'Anda hanya dapat mengedit permintaan yang Anda buat sendiri.');
        }

        $units = $this->getPrincipalUnits();
        $unitIds = $units->pluck('id');
        
        $academicYears = AcademicYear::orderBy('status', 'asc')
                                     ->orderBy('start_year', 'desc')
                                     ->get();
        
        $teachers = User::whereIn('role', ['guru', 'karyawan'])
                        ->whereHas('jabatanUnits', function($q) use ($unitIds) {
                            $q->whereIn('unit_id', $unitIds);
                        })
                        ->orderBy('name')
                        ->get();
                        
        return view('principal.document_edit', compact('request', 'units', 'academicYears', 'teachers'));
    }

    public function updateDocumentRequest(Request $request, $id)
    {
        $docRequest = \App\Models\TeacherDocumentRequest::findOrFail($id);
        
        if ($docRequest->created_by != Auth::id()) {
             abort(403, 'Unauthorized');
        }
        
        $units = $this->getPrincipalUnits();
        $unitIds = $units->pluck('id')->toArray();

        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'academic_year_id' => 'required|exists:academic_years,id',
            'semester' => 'required|in:ganjil,genap',
            'due_date' => 'required|date',
            'target_units' => 'required|array',
            'target_units.*' => 'in:' . implode(',', $unitIds),
            'target_users' => 'nullable|array',
            'target_users.*' => 'exists:users,id',
            'is_active' => 'boolean'
        ]);

        $docRequest->update([
            'title' => $request->title,
            'description' => $request->description,
            'academic_year_id' => $request->academic_year_id,
            'semester' => $request->semester,
            'due_date' => $request->due_date,
            'is_active' => $request->has('is_active') ? true : false,
            'target_units' => $request->target_units,
            'target_users' => $request->target_users ?? [],
        ]);

        return redirect()->route('principal.documents')->with('success', 'Permintaan dokumen berhasil diperbarui.');
    }

    public function destroyDocumentRequest($id)
    {
        $docRequest = \App\Models\TeacherDocumentRequest::findOrFail($id);
        
        if ($docRequest->created_by != Auth::id()) {
             return redirect()->back()->with('error', 'Anda tidak memiliki hak untuk menghapus permintaan ini.');
        }

        // Force Delete: Delete all associated submissions and files
        $submissions = $docRequest->submissions;
        foreach ($submissions as $sub) {
            if ($sub->file_path && \Illuminate\Support\Facades\Storage::disk('public')->exists($sub->file_path)) {
                \Illuminate\Support\Facades\Storage::disk('public')->delete($sub->file_path);
            }
            $sub->delete();
        }

        $docRequest->delete();
        
        return redirect()->route('principal.documents')->with('success', 'Permintaan dokumen dan semua data pengumpulan terkait berhasil dihapus.');
    }

    public function documentReview(Request $request, $id)
    {
         $submission = \App\Models\TeacherDocumentSubmission::with(['request', 'user'])->findOrFail($id);
         
         if ($request->isMethod('post')) {
             $request->validate([
                 'status' => 'required|in:approved,rejected,validated', // Added 'validated' for cancellation
                 'feedback' => 'nullable|string'
             ]);
             
             $updateData = [
                 'status' => $request->status,
                 'feedback' => $request->feedback,
             ];
             
             // If Cancelling (Back to Validated), clear approval info
             if ($request->status == 'validated') {
                 $updateData['approved_by'] = null;
                 $updateData['approved_at'] = null;
                 $msg = 'Approval dibatalkan, dokumen kembali ke status Validated.';
             } else {
                 $updateData['approved_by'] = Auth::id();
                 $updateData['approved_at'] = now();
                 $msg = 'Dokumen berhasil diproses.';
             }

             $submission->update($updateData);
             
             return redirect()->route('principal.documents')->with('success', $msg);
         }
         
         return view('principal.document_review', compact('submission'));
    }
}
