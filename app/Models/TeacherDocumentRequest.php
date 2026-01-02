<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TeacherDocumentRequest extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'academic_year_id',
        'semester',
        'due_date',
        'is_active',
        'created_by',
        'target_units',
        'target_subjects',
        'target_grades',
        'target_users',
    ];

    protected $casts = [
        'due_date' => 'date',
        'is_active' => 'boolean',
        'target_units' => 'array',
        'target_subjects' => 'array',
        'target_grades' => 'array',
        'target_users' => 'array',
    ];

    public function academicYear()
    {
        return $this->belongsTo(AcademicYear::class);
    }

    public function submissions()
    {
        return $this->hasMany(TeacherDocumentSubmission::class, 'request_id');
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Determine if the given user is a target for this request.
     *
     * @param User $user
     * @return bool
     */
    public function isTargetFor(User $user): bool
    {
        // 1. Check explicit User Targeting
        // If target_users is populated, the user MUST be in this list to be eligible/notified.
        // Assuming target_users is an "Include List".
        // If target_users is empty, we fall back to Criteria Targeting.
        if (!empty($this->target_users)) {
            // target_users stores IDs as strings or ints
            return in_array($user->id, $this->target_users);
        }

        // 2. Criteria Targeting (Unit, Subject, Grade)
        // If target_users is empty, we check if the user matches ALL specified criteria.
        // If a criteria is empty, it is considered "Any" (Global for that criteria).

        $matchesUnit = true;
        if (!empty($this->target_units)) {
            // Get all units the user is associated with
            // 1. Home Unit
            $userUnitIds = collect([$user->unit_id]);
            
            // 2. Jabatan Units
            $jabatanUnits = \Illuminate\Support\Facades\DB::table('user_jabatan_units')
                            ->where('user_id', $user->id)
                            ->pluck('unit_id');
            $userUnitIds = $userUnitIds->concat($jabatanUnits);

            // 3. Teaching Assignment Units
            $teachingUnits = \App\Models\TeachingAssignment::where('user_id', $user->id)
                            ->with('schoolClass')
                            ->get()
                            ->pluck('schoolClass.unit_id');
            $userUnitIds = $userUnitIds->concat($teachingUnits);
            
            // 4. Wali Kelas Unit
            $waliKelas = $user->waliKelasOf;
            if ($waliKelas) {
                $userUnitIds->push($waliKelas->unit_id);
            }

            $userUnitIds = $userUnitIds->unique()->filter();

            // Check intersection
            $matchesUnit = $userUnitIds->intersect($this->target_units)->isNotEmpty();
        }

        if (!$matchesUnit) return false;

        $matchesSubject = true;
        if (!empty($this->target_subjects)) {
            $userSubjectIds = \App\Models\TeachingAssignment::where('user_id', $user->id)
                            ->pluck('subject_id')
                            ->unique();
            $matchesSubject = $userSubjectIds->intersect($this->target_subjects)->isNotEmpty();
        }

        if (!$matchesSubject) return false;

        $matchesGrade = true;
        if (!empty($this->target_grades)) {
            $userGrades = \App\Models\TeachingAssignment::where('user_id', $user->id)
                            ->with('schoolClass')
                            ->get()
                            ->pluck('schoolClass.grade_code')
                            ->unique();
            $matchesGrade = $userGrades->intersect($this->target_grades)->isNotEmpty();
        }

        if (!$matchesGrade) return false;

        return true;
    }
}
