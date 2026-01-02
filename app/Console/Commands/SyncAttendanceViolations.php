<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class SyncAttendanceViolations extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:sync-violations';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Sync existing attendance (late/alpha) to violations table';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Starting sync...');
        $count = 0;

        $attendances = \App\Models\StudentAttendance::whereIn('status', ['late', 'alpha'])->get();

        foreach ($attendances as $att) {
            if ($att->status == 'late') {
                 $created = $this->createViolation($att, 'Ringan', 'Terlambat Datang Sekolah', 1);
                 if ($created) $count++;
            } elseif ($att->status == 'alpha') {
                 $created = $this->createViolation($att, 'Sedang', 'Alpha (Tanpa Keterangan)', 2);
                 if ($created) $count++;
            }
        }

        $this->info("Synced $count new violations.");
    }

    private function createViolation($att, $type, $desc, $points)
    {
        $exists = \App\Models\StudentViolation::where('student_id', $att->student_id)
            ->where('date', $att->date)
            ->where('violation_type', $type)
            ->where('description', $desc)
            ->exists();

        if (!$exists) {
            \App\Models\StudentViolation::create([
                'student_id' => $att->student_id,
                'date' => $att->date,
                'violation_type' => $type,
                'description' => $desc,
                'points' => $points,
                'recorded_by' => $att->created_by ?? 1,
            ]);
            return true;
        }
        return false;
    }
}
