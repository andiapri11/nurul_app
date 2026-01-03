<?php

use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Schema;

Route::get('/', [\App\Http\Controllers\AuthController::class, 'home']);



/*
Route::get('/fix-skl-column', function() {
    try {
        if (!Schema::hasColumn('student_graduation_results', 'skl_file')) {
            Schema::table('student_graduation_results', function (\Illuminate\Database\Schema\Blueprint $table) {
                $table->string('skl_file')->nullable()->after('message');
            });
            return "Column skl_file added successfully!";
        }
        return "Column already exists.";
    } catch (\Exception $e) {
        return "Error: " . $e->getMessage();
    }
});
*/

Route::get('/login', [AuthController::class, 'login'])->name('login');
Route::post('/login', [AuthController::class, 'authenticate']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

/*
Route::get('/debug-supervision', function() {
    $user = \Illuminate\Support\Facades\Auth::user();
    if(!$user) return 'Not logged in';
    
    $activeYear = \App\Models\AcademicYear::active()->first();
    $all = \App\Models\Supervision::all();
    
    $managedUnits = $user->getManajemenUnits()->pluck('id');
    
    return [
        'user_id' => $user->id,
        'user_role' => $user->role,
        'user_unit_id' => $user->unit_id,
        'managed_unit_ids' => $managedUnits,
        'active_year' => $activeYear,
        'supervisions_count' => $all->count(),
        'supervisions' => $all->map(function($s) {
            return [
                'id' => $s->id,
                'unit_id' => $s->unit_id,
                'supervisor_id' => $s->supervisor_id,
                'academic_year_id' => $s->academic_year_id
            ];
        })
    ];
});
*/




// Shared Routes (Staff & Students)
Route::middleware(['auth:web,student'])->group(function () {
    Route::get('schedules/print', [\App\Http\Controllers\ScheduleController::class, 'print'])->name('schedules.print');
    Route::get('/finance/payments/receipt/{transaction}', [\App\Http\Controllers\FinancePaymentController::class, 'printReceipt'])->name('finance.payments.receipt');
});

Route::middleware(['auth'])->group(function () {
    // Mading Online (Protected)
    Route::get('/mading', [\App\Http\Controllers\MadingController::class, 'index'])->name('mading.index');
    
    Route::get('/dashboard', [AuthController::class, 'dashboard'])->name('dashboard');
    
    // User Profile Routes
    Route::get('/profile', [\App\Http\Controllers\ProfileController::class, 'index'])->name('profile.index');
    Route::post('/profile/info', [\App\Http\Controllers\ProfileController::class, 'updateInfo'])->name('profile.update-info');
    Route::post('/profile/photo', [\App\Http\Controllers\ProfileController::class, 'updatePhoto'])->name('profile.update-photo');
    Route::post('/profile/password', [\App\Http\Controllers\ProfileController::class, 'updatePassword'])->name('profile.update-password');
    Route::post('/profile/pin', [\App\Http\Controllers\ProfileController::class, 'updatePin'])->name('profile.update-pin');
    // Schedules (Shared Access, authorization handled in controller)
    // Schedule CRUD routes
    // Schedule Settings MUST come before resource to avoid conflict with {schedule}
    Route::get('schedules/settings', [\App\Http\Controllers\ScheduleController::class, 'settings'])->name('schedules.settings');
    Route::post('schedules/settings', [\App\Http\Controllers\ScheduleController::class, 'storeTimeSlot'])->name('schedules.storeTimeSlot');
    Route::put('schedules/settings/{timeSlot}', [\App\Http\Controllers\ScheduleController::class, 'updateTimeSlot'])->name('schedules.updateTimeSlot');
    Route::delete('schedules/settings/{timeSlot}', [\App\Http\Controllers\ScheduleController::class, 'destroyTimeSlot'])->name('schedules.destroyTimeSlot');
    
    // Schedule CRUD routes
    Route::get('schedules/mass-update', [\App\Http\Controllers\ScheduleController::class, 'massUpdate'])->name('schedules.mass-update');
    Route::post('schedules/mass-update', [\App\Http\Controllers\ScheduleController::class, 'massStore'])->name('schedules.mass-store');
    
    // Schedule CRUD routes
    Route::resource('schedules', \App\Http\Controllers\ScheduleController::class);
    
    // Student Management (Authorized)
    Route::get('/students/alumni', [\App\Http\Controllers\StudentController::class, 'alumni'])->name('students.alumni');
    Route::get('/students/withdrawn', [\App\Http\Controllers\StudentController::class, 'withdrawn'])->name('students.withdrawn');
    Route::post('/students/{id}/change-status', [\App\Http\Controllers\StudentController::class, 'changeStatus'])->name('students.change-status');
    
    Route::post('/students/bulk-action', [\App\Http\Controllers\StudentController::class, 'bulkAction'])->name('students.bulk-action');
    Route::post('/students/import', [\App\Http\Controllers\StudentController::class, 'import'])->name('students.import');
    Route::get('/students/download-template', [\App\Http\Controllers\StudentController::class, 'downloadTemplate'])->name('students.download-template');
    Route::get('/students/export', [\App\Http\Controllers\StudentController::class, 'export'])->name('students.export'); // New Export Route
    Route::resource('students', \App\Http\Controllers\StudentController::class);

    // Class Checkins
    Route::get('class-checkins/export-pdf', [\App\Http\Controllers\ClassCheckinController::class, 'exportPdf'])->name('class-checkins.export-pdf');
    Route::resource('class-checkins', \App\Http\Controllers\ClassCheckinController::class);

    // Classes & Subjects Selection (Accessible by Authorized Users)
    Route::get('classes/mass-edit', [\App\Http\Controllers\SchoolClassController::class, 'massEdit'])->name('classes.mass-edit');
    Route::post('classes/mass-update', [\App\Http\Controllers\SchoolClassController::class, 'massUpdate'])->name('classes.mass-update');
    Route::resource('classes', \App\Http\Controllers\SchoolClassController::class);
    Route::resource('subjects', \App\Http\Controllers\SubjectController::class); // Subject Management (Authorized via Controller)
    // Actually `subjects` resource is in Admin group (Edit/Create).
    // I only need the AJAX getters here.
    Route::get('/get-classes/{unit_id}', [\App\Http\Controllers\SchoolClassController::class, 'getClassesByUnit']);
    Route::get('/get-subjects/{unit_id}', [\App\Http\Controllers\SubjectController::class, 'getSubjectsByUnit']);


    
    // Guru Dashboard Routes
    Route::get('/dashboard/kelas-saya', [\App\Http\Controllers\GuruDashboardController::class, 'myClass'])->name('dashboard.my-class');
    
    // Teacher Documents (Administrasi Guru)
    Route::group(['prefix' => 'teacher-docs', 'as' => 'teacher-docs.'], function() {
        Route::get('/', [\App\Http\Controllers\CurriculumController::class, 'teacherIndex'])->name('index');
        Route::post('/{id}/upload', [\App\Http\Controllers\CurriculumController::class, 'upload'])->name('upload');
        
        // Teacher Supervision Routes
        Route::resource('supervisions', \App\Http\Controllers\SupervisionController::class)->only(['index', 'edit', 'update']);
    });

    // Wali Kelas Routes (Homeroom Teacher)
    Route::group(['prefix' => 'wali-kelas', 'as' => 'wali-kelas.'], function() {
        Route::get('/', [\App\Http\Controllers\WaliKelasController::class, 'index'])->name('index');
        Route::get('/attendance', [\App\Http\Controllers\WaliKelasController::class, 'attendance'])->name('attendance');
        Route::post('/attendance', [\App\Http\Controllers\WaliKelasController::class, 'storeAttendance'])->name('store-attendance');
        Route::get('/report', [\App\Http\Controllers\WaliKelasController::class, 'report'])->name('report');
        Route::get('/report/export', [\App\Http\Controllers\WaliKelasController::class, 'exportReport'])->name('export-report');
        Route::delete('/attendance/destroy', [\App\Http\Controllers\WaliKelasController::class, 'destroyAttendance'])->name('destroy-attendance');
        
        // Student Management for Wali Kelas
        Route::get('/students', [\App\Http\Controllers\WaliKelasStudentController::class, 'index'])->name('students.index');
        Route::get('/students/{id}/edit', [\App\Http\Controllers\WaliKelasStudentController::class, 'edit'])->name('students.edit');
        Route::put('/students/{id}', [\App\Http\Controllers\WaliKelasStudentController::class, 'update'])->name('students.update');
        Route::get('/students/{student}/show', [\App\Http\Controllers\WaliKelasController::class, 'showStudent'])->name('students.show');
        
        // Violations
        Route::get('/violations', [\App\Http\Controllers\WaliKelasController::class, 'violations'])->name('violations');

        // Extracurriculars
        Route::get('/extracurriculars', [\App\Http\Controllers\WaliKelasController::class, 'extracurriculars'])->name('extracurriculars');

        // Announcements
        Route::resource('announcements', \App\Http\Controllers\WaliKelasAnnouncementController::class)->only(['index', 'store', 'destroy']);
    });

    // Principal (Kepala Sekolah) Routes
    Route::group(['prefix' => 'principal', 'as' => 'principal.', 'middleware' => ['principal']], function() {
        Route::get('/dashboard', [\App\Http\Controllers\PrincipalController::class, 'index'])->name('index');
        Route::get('/teacher-attendance', [\App\Http\Controllers\PrincipalController::class, 'teacherAttendance'])->name('teacher-attendance');
        Route::get('/class-stats', [\App\Http\Controllers\PrincipalController::class, 'classStats'])->name('class-stats');
        Route::get('/documents', [\App\Http\Controllers\PrincipalController::class, 'documents'])->name('documents');
        Route::get('/documents/create', [\App\Http\Controllers\PrincipalController::class, 'createDocumentRequest'])->name('documents.create');
        Route::post('/documents', [\App\Http\Controllers\PrincipalController::class, 'storeDocumentRequest'])->name('documents.store');
        
        // Document Request Management
        Route::get('/documents/request/{id}', [\App\Http\Controllers\PrincipalController::class, 'showDocumentRequest'])->name('documents.show-request');
        Route::get('/documents/request/{id}/edit', [\App\Http\Controllers\PrincipalController::class, 'editDocumentRequest'])->name('documents.edit-request');
        Route::put('/documents/request/{id}', [\App\Http\Controllers\PrincipalController::class, 'updateDocumentRequest'])->name('documents.update-request');
        Route::delete('/documents/request/{id}', [\App\Http\Controllers\PrincipalController::class, 'destroyDocumentRequest'])->name('documents.destroy-request');

        Route::match(['get', 'post'], '/documents/{id}', [\App\Http\Controllers\PrincipalController::class, 'documentReview'])->name('documents.review');
        
        // Supervision Routes
        Route::get('supervisions/teacher-info/{teacher}', [\App\Http\Controllers\SupervisionController::class, 'getTeacherInfo'])->name('supervisions.teacher-info');
        Route::resource('supervisions', \App\Http\Controllers\SupervisionController::class);
    });

    // Sarana Prasarana (Sarpras) Routes
    Route::group(['prefix' => 'sarpras', 'as' => 'sarpras.', 'middleware' => ['auth', 'sarpras']], function() {
        Route::get('/dashboard', [\App\Http\Controllers\SarprasController::class, 'index'])->name('index');
        
        // Categories
        Route::get('/categories', [\App\Http\Controllers\SarprasController::class, 'categories'])->name('categories.index');
        Route::post('/categories', [\App\Http\Controllers\SarprasController::class, 'storeCategory'])->name('categories.store');
        Route::delete('/categories/{category}', [\App\Http\Controllers\SarprasController::class, 'destroyCategory'])->name('categories.destroy');

        // Room Types
        Route::post('/room-types', [\App\Http\Controllers\SarprasController::class, 'storeRoomType'])->name('room-types.store');
        Route::delete('/room-types/{roomType}', [\App\Http\Controllers\SarprasController::class, 'destroyRoomType'])->name('room-types.destroy');

        // Units
        Route::post('/units/store-ajax', [\App\Http\Controllers\SarprasController::class, 'storeUnit'])->name('units.store_ajax');
        Route::delete('/units/{unit}', [\App\Http\Controllers\SarprasController::class, 'destroyUnit'])->name('units.destroy');

        // Academic Years (Sarpras Context)
        Route::post('/academic-years', [\App\Http\Controllers\SarprasController::class, 'storeAcademicYear'])->name('academic-years.store');
        Route::delete('/academic-years/{academicYear}', [\App\Http\Controllers\SarprasController::class, 'destroyAcademicYear'])->name('academic-years.destroy');

        // Rooms
        Route::get('/rooms', [\App\Http\Controllers\SarprasController::class, 'rooms'])->name('rooms.index');
        Route::post('/rooms', [\App\Http\Controllers\SarprasController::class, 'storeRoom'])->name('rooms.store');
        Route::put('/rooms/{room}', [\App\Http\Controllers\SarprasController::class, 'updateRoom'])->name('rooms.update');
        Route::delete('/rooms/{room}', [\App\Http\Controllers\SarprasController::class, 'destroyRoom'])->name('rooms.destroy');
        
        // Inventory
        Route::get('/inventory', [\App\Http\Controllers\SarprasController::class, 'inventory'])->name('inventory.index');
        Route::get('/inventory/print', [\App\Http\Controllers\SarprasController::class, 'printInventory'])->name('inventory.print');
        Route::get('/inventory/disposed', [\App\Http\Controllers\SarprasController::class, 'disposedInventory'])->name('inventory.disposed'); // Archive list
        Route::get('/inventory/{id}/disposal-proof', [\App\Http\Controllers\SarprasController::class, 'printDisposalProof'])->name('inventory.disposal-proof'); // Print proof
        Route::post('/inventory/{id}/upload-disposal-photo', [\App\Http\Controllers\SarprasController::class, 'uploadDisposalPhoto'])->name('inventory.upload-disposal-photo'); // Upload photo proof
        Route::post('/inventory/{id}/dispose', [\App\Http\Controllers\SarprasController::class, 'disposeInventory'])->name('inventory.dispose'); // Action to dispose
        Route::post('/inventory/{id}/restore', [\App\Http\Controllers\SarprasController::class, 'restoreInventory'])->name('inventory.restore'); // Restore
        Route::delete('/inventory/{id}/force-delete', [\App\Http\Controllers\SarprasController::class, 'forceDeleteInventory'])->name('inventory.force-delete'); // Permanent delete

        Route::get('/inventory/{inventory}/history', [\App\Http\Controllers\SarprasController::class, 'inventoryHistory'])->name('inventory.history');
        Route::get('/inventory/print-barcodes', [\App\Http\Controllers\SarprasController::class, 'printBarcodes'])->name('inventory.print-barcodes');
        Route::post('/inventory', [\App\Http\Controllers\SarprasController::class, 'storeInventory'])->name('inventory.store');
        Route::put('/inventory/{inventory}', [\App\Http\Controllers\SarprasController::class, 'updateInventory'])->name('inventory.update');
        Route::delete('/inventory/{inventory}', [\App\Http\Controllers\SarprasController::class, 'destroyInventory'])->name('inventory.destroy');

        // Consumables (Barang Habis Pakai)
        Route::get('/consumables', [\App\Http\Controllers\SarprasController::class, 'consumables'])->name('consumables.index');
        Route::get('/consumables/history', [\App\Http\Controllers\SarprasController::class, 'consumableHistory'])->name('consumables.history');
        Route::post('/consumables', [\App\Http\Controllers\SarprasController::class, 'storeConsumable'])->name('consumables.store');
        Route::put('/consumables/{consumable}', [\App\Http\Controllers\SarprasController::class, 'updateConsumable'])->name('consumables.update');
        Route::post('/consumables/{consumable}/transact', [\App\Http\Controllers\SarprasController::class, 'transactConsumable'])->name('consumables.transact');
        Route::delete('/consumables/{consumable}', [\App\Http\Controllers\SarprasController::class, 'destroyConsumable'])->name('consumables.destroy');
        
        // Damage Reports
        Route::get('/reports', [\App\Http\Controllers\SarprasController::class, 'damageReports'])->name('reports.index');
        Route::get('/reports/print', [\App\Http\Controllers\SarprasController::class, 'printReports'])->name('reports.print');
        Route::put('/reports/{report}', [\App\Http\Controllers\SarprasController::class, 'updateReportStatus'])->name('reports.update-status');
        Route::delete('/reports/{report}', [\App\Http\Controllers\SarprasController::class, 'destroyDamageReport'])->name('reports.destroy');
        Route::post('/reports/{report}/follow-up', [\App\Http\Controllers\SarprasController::class, 'submitFollowUp'])->name('reports.submit-follow-up');
        
        // Unified Approval Pages
        Route::get('/principal/approvals', [\App\Http\Controllers\SarprasController::class, 'principalApprovals'])->name('principal.approvals');
        Route::get('/director/approvals', [\App\Http\Controllers\SarprasController::class, 'directorApprovals'])->name('director.approvals');

        // Procurement Requests (Pengajuan Barang)
        Route::get('/procurements', [\App\Http\Controllers\SarprasController::class, 'procurements'])->name('procurements.index');
        Route::post('/procurements', [\App\Http\Controllers\SarprasController::class, 'storeProcurement'])->name('procurements.store');
        Route::post('/procurements/{procurement}/report', [\App\Http\Controllers\SarprasController::class, 'submitProcurementReport'])->name('procurements.report');
        Route::put('/procurements/{procurement}', [\App\Http\Controllers\SarprasController::class, 'updateProcurement'])->name('procurements.update');
        Route::delete('/procurements/{procurement}', [\App\Http\Controllers\SarprasController::class, 'destroyProcurement'])->name('procurements.destroy');

        // Principal Validation (Pengajuan)
        Route::get('/principal/procurements', [\App\Http\Controllers\SarprasController::class, 'principalProcurements'])->name('procurements.principal-index');
        Route::post('/procurements/{procurement}/validate-principal', [\App\Http\Controllers\SarprasController::class, 'validatePrincipalProcurement'])->name('procurements.validate-principal');
        Route::post('/procurements/{procurement}/cancel-principal', [\App\Http\Controllers\SarprasController::class, 'cancelPrincipalProcurement'])->name('procurements.cancel-principal');
        
        // Director Approval (Pengajuan)
        Route::get('/director/procurements', [\App\Http\Controllers\SarprasController::class, 'directorProcurements'])->name('procurements.director-index');
        Route::post('/procurements/{procurement}/approve-director', [\App\Http\Controllers\SarprasController::class, 'approveDirectorProcurement'])->name('procurements.approve-director');
        Route::post('/procurements/{procurement}/reset-director', [\App\Http\Controllers\SarprasController::class, 'resetDirectorProcurement'])->name('procurements.reset-director');
        Route::get('/procurements/print/{requestCode}', [\App\Http\Controllers\SarprasController::class, 'printProcurement'])->name('procurements.print');
        
        // Headmaster Validation (Kepala Sekolah)
        Route::get('/principal/reports', [\App\Http\Controllers\SarprasController::class, 'principalReports'])->name('reports.principal-index');
        Route::post('/reports/{report}/validate-principal', [\App\Http\Controllers\SarprasController::class, 'approvePrincipal'])->name('reports.approve-principal');
        
        // Director Approval (Pimpinan Lembaga)
        Route::get('/director/reports', [\App\Http\Controllers\SarprasController::class, 'directorReports'])->name('reports.director-index');
        Route::post('/reports/{report}/approve-director', [\App\Http\Controllers\SarprasController::class, 'approveDirector'])->name('reports.approve-director');
        Route::post('/reports/{report}/reset-director', [\App\Http\Controllers\SarprasController::class, 'resetDirectorReport'])->name('reports.reset-director');

        // QR/Barcode Scanning
        Route::get('/scan', [\App\Http\Controllers\SarprasController::class, 'scan'])->name('scan');
        Route::get('/inventory/find-by-code', [\App\Http\Controllers\SarprasController::class, 'findByCode'])->name('inventory.find-by-code');
        Route::post('/inventory/report-damage-by-code', [\App\Http\Controllers\SarprasController::class, 'reportDamageByCode'])->name('inventory.report-damage-by-code');
    });

    // Curriculum (Wakasek Kurikulum) Routes
    Route::group(['prefix' => 'school-management/curriculum', 'as' => 'curriculum.'], function() {
        Route::get('/', [\App\Http\Controllers\CurriculumController::class, 'index'])->name('index');
        Route::get('/jurnal-kelas', [\App\Http\Controllers\CurriculumController::class, 'jurnalKelas'])->name('jurnal-kelas');
        Route::get('/jurnal-kelas/print', [\App\Http\Controllers\CurriculumController::class, 'jurnalKelasPrint'])->name('jurnal-kelas.print');
        Route::get('/create', [\App\Http\Controllers\CurriculumController::class, 'create'])->name('create');
        Route::post('/', [\App\Http\Controllers\CurriculumController::class, 'store'])->name('store');
        Route::get('/get-teachers', [\App\Http\Controllers\CurriculumController::class, 'getTeachers'])->name('get-teachers');
        Route::get('/get-grades', [\App\Http\Controllers\CurriculumController::class, 'getGrades'])->name('get-grades');
        Route::get('/get-subjects', [\App\Http\Controllers\CurriculumController::class, 'getSubjects'])->name('get-subjects');
        Route::get('/calendar', [\App\Http\Controllers\KurikulumCalendarController::class, 'index'])->name('calendar.index');
        Route::get('/calendar/manage', [\App\Http\Controllers\KurikulumCalendarController::class, 'manage'])->name('calendar.manage');
        Route::post('/calendar', [\App\Http\Controllers\KurikulumCalendarController::class, 'store'])->name('calendar.store');
        Route::get('/{id}', [\App\Http\Controllers\CurriculumController::class, 'show'])->name('show');
        Route::get('/{id}/edit', [\App\Http\Controllers\CurriculumController::class, 'edit'])->name('edit');
        Route::put('/{id}', [\App\Http\Controllers\CurriculumController::class, 'update'])->name('update');
        Route::delete('/{id}', [\App\Http\Controllers\CurriculumController::class, 'destroy'])->name('destroy');
        Route::post('/submission', [\App\Http\Controllers\CurriculumController::class, 'storeSubmission'])->name('submission.store');
        Route::post('/submission/{id}/update-status', [\App\Http\Controllers\CurriculumController::class, 'updateStatus'])->name('submission.update-status');
    });

    // Graduation (Pengumuman Kelulusan)
    Route::group(['prefix' => 'graduation', 'as' => 'graduation.'], function() {
        Route::get('/', [\App\Http\Controllers\GraduationAnnouncementController::class, 'index'])->name('index');
        Route::post('/store', [\App\Http\Controllers\GraduationAnnouncementController::class, 'store'])->name('store');
        Route::get('/{id}', [\App\Http\Controllers\GraduationAnnouncementController::class, 'show'])->name('show');
        Route::delete('/{id}', [\App\Http\Controllers\GraduationAnnouncementController::class, 'destroy'])->name('destroy');
        Route::post('/settings', [\App\Http\Controllers\GraduationAnnouncementController::class, 'updateSettings'])->name('settings.update');
        Route::post('/store-single', [\App\Http\Controllers\GraduationAnnouncementController::class, 'storeResult'])->name('store-single');
        Route::delete('/result/{id}', [\App\Http\Controllers\GraduationAnnouncementController::class, 'deleteResult'])->name('result.delete');
        Route::get('/get-students/{class_id}', [\App\Http\Controllers\GraduationAnnouncementController::class, 'getStudentsByClass'])->name('get-students');
    });
    // Academic Calendar (Authorized via Controller)
    Route::get('academic-calendars/manage', [\App\Http\Controllers\AcademicCalendarController::class, 'manage'])->name('academic-calendars.manage');
    Route::post('academic-calendars/manage', [\App\Http\Controllers\AcademicCalendarController::class, 'updateMonth'])->name('academic-calendars.update-month');
    Route::resource('academic-calendars', \App\Http\Controllers\AcademicCalendarController::class);

    // Direktur (Executive) Routes
    Route::group(['prefix' => 'director', 'as' => 'director.', 'middleware' => function ($request, $next) {
        if (!in_array(Auth::user()->role, ['administrator', 'direktur'])) {
            abort(403, 'Akses Ditolak: Khusus Direktur/Admin.');
        }
        return $next($request);
    }], function() {
        Route::get('/dashboard', [\App\Http\Controllers\DirectorController::class, 'index'])->name('index');
        Route::get('/employees', [\App\Http\Controllers\DirectorController::class, 'employees'])->name('employees');
    });
    // Student Affairs (Wakil Kesiswaan)
    Route::middleware(['auth', 'kesiswaan'])->prefix('student-affairs')->name('student-affairs.')->group(function () {
        // Violations
        Route::get('violations', [\App\Http\Controllers\StudentAffairsController::class, 'indexViolations'])->name('violations.index');
        Route::get('violations/export-pdf', [\App\Http\Controllers\StudentAffairsController::class, 'exportPdfViolation'])->name('violations.export-pdf');
        Route::get('violations/create', [\App\Http\Controllers\StudentAffairsController::class, 'createViolation'])->name('violations.create');
        Route::post('violations', [\App\Http\Controllers\StudentAffairsController::class, 'storeViolation'])->name('violations.store');
        Route::get('violations/{violation}/edit', [\App\Http\Controllers\StudentAffairsController::class, 'editViolation'])->name('violations.edit');
        Route::put('violations/{violation}', [\App\Http\Controllers\StudentAffairsController::class, 'updateViolation'])->name('violations.update');
        Route::patch('violations/update-follow-up', [\App\Http\Controllers\StudentAffairsController::class, 'updateFollowUp'])->name('violations.update-follow-up');
        Route::delete('violations/{violation}', [\App\Http\Controllers\StudentAffairsController::class, 'destroyViolation'])->name('violations.destroy');

        // Achievements
        Route::get('achievements', [\App\Http\Controllers\StudentAffairsController::class, 'indexAchievements'])->name('achievements.index');
        Route::get('achievements/export-pdf', [\App\Http\Controllers\StudentAffairsController::class, 'exportPdfAchievement'])->name('achievements.export-pdf');
        Route::get('achievements/create', [\App\Http\Controllers\StudentAffairsController::class, 'createAchievement'])->name('achievements.create');
        Route::post('achievements', [\App\Http\Controllers\StudentAffairsController::class, 'storeAchievement'])->name('achievements.store');
        Route::get('achievements/{achievement}/edit', [\App\Http\Controllers\StudentAffairsController::class, 'editAchievement'])->name('achievements.edit');
        Route::put('achievements/{achievement}', [\App\Http\Controllers\StudentAffairsController::class, 'updateAchievement'])->name('achievements.update');
        Route::delete('achievements/{achievement}', [\App\Http\Controllers\StudentAffairsController::class, 'destroyAchievement'])->name('achievements.destroy');
        
        // Black Book (Poin > Unit Threshold)
        Route::get('black-book', [\App\Http\Controllers\StudentAffairsController::class, 'blackBook'])->name('black-book');
        Route::post('black-book/update-threshold', [\App\Http\Controllers\StudentAffairsController::class, 'updateBlackBookThreshold'])->name('black-book.update-threshold');

        // Extracurriculars
        Route::get('extracurriculars', [\App\Http\Controllers\StudentAffairsController::class, 'indexExtracurriculars'])->name('extracurriculars.index');
        Route::post('extracurriculars', [\App\Http\Controllers\StudentAffairsController::class, 'storeExtracurricular'])->name('extracurriculars.store');
        Route::put('extracurriculars/{extracurricular}', [\App\Http\Controllers\StudentAffairsController::class, 'updateExtracurricular'])->name('extracurriculars.update');
        Route::delete('extracurriculars/{extracurricular}', [\App\Http\Controllers\StudentAffairsController::class, 'destroyExtracurricular'])->name('extracurriculars.destroy');
        Route::get('extracurriculars/{extracurricular}/members', [\App\Http\Controllers\StudentAffairsController::class, 'manageExtracurricularMembers'])->name('extracurriculars.members');
        Route::post('extracurriculars/{extracurricular}/members', [\App\Http\Controllers\StudentAffairsController::class, 'addExtracurricularMember'])->name('extracurriculars.add-member');
        Route::delete('extracurriculars/members/{member}', [\App\Http\Controllers\StudentAffairsController::class, 'removeExtracurricularMember'])->name('extracurriculars.remove-member');

        // Achievements & Reports
        Route::get('extracurriculars/{extracurricular}/achievements', [\App\Http\Controllers\StudentAffairsController::class, 'extracurricularAchievements'])->name('extracurriculars.achievements');
        Route::put('extracurriculars/{extracurricular}/achievements', [\App\Http\Controllers\StudentAffairsController::class, 'updateExtracurricularAchievements'])->name('extracurriculars.update-achievements');
        Route::post('extracurriculars/{extracurricular}/reports', [\App\Http\Controllers\StudentAffairsController::class, 'storeExtracurricularReport'])->name('extracurriculars.store-report');
        Route::delete('extracurriculars/reports/{report}', [\App\Http\Controllers\StudentAffairsController::class, 'deleteExtracurricularReport'])->name('extracurriculars.delete-report');
    });
});

Route::middleware(['auth', 'admin'])->group(function () {
    // Admin routes remain here
    Route::get('/user-mading', [\App\Http\Controllers\UserController::class, 'index'])->name('users.index');
    Route::post('/user-mading', [\App\Http\Controllers\UserController::class, 'store'])->name('users.store');
    Route::resource('jabatans', \App\Http\Controllers\JabatanController::class);
    Route::resource('administrators', \App\Http\Controllers\AdministratorController::class);
    Route::get('/login-history', [\App\Http\Controllers\LoginHistoryController::class, 'index'])->name('login-history.index');
    Route::delete('/login-history/clear', [\App\Http\Controllers\LoginHistoryController::class, 'clear'])->name('login-history.clear');
    Route::get('/user-guru', [\App\Http\Controllers\GuruKaryawanController::class, 'userIndex'])->name('gurukaryawans.user-index');
    
    // Guru Karyawan Import
    Route::post('/gurukaryawans/import', [\App\Http\Controllers\GuruKaryawanController::class, 'import'])->name('gurukaryawans.import');
    Route::get('/gurukaryawans/download-template', [\App\Http\Controllers\GuruKaryawanController::class, 'downloadTemplate'])->name('gurukaryawans.download-template');
    Route::post('/gurukaryawans/copy-data', [\App\Http\Controllers\GuruKaryawanController::class, 'copyData'])->name('gurukaryawans.copy-data');
    Route::resource('gurukaryawans', \App\Http\Controllers\GuruKaryawanController::class);
    
    // Manajemen Mading
    Route::resource('mading-admin', \App\Http\Controllers\MadingAdminController::class);
    
    // User Manajemen - Pimpinan (Direktur)
    Route::resource('leadership-users', \App\Http\Controllers\LeadershipUsersController::class);

    Route::post('/gurukaryawans/{id}/toggle-status', [\App\Http\Controllers\GuruKaryawanController::class, 'toggleStatus'])->name('gurukaryawans.toggle-status');

    
    // Admin Students Import/Export
    Route::get('/admin-students/alumni', [\App\Http\Controllers\AdminStudentController::class, 'alumni'])->name('admin-students.alumni');
    Route::get('/admin-students/withdrawn', [\App\Http\Controllers\AdminStudentController::class, 'withdrawn'])->name('admin-students.withdrawn');
    Route::post('/admin-students/{id}/change-status', [\App\Http\Controllers\AdminStudentController::class, 'changeStatus'])->name('admin-students.change-status');
    
    Route::post('/admin-students/import', [\App\Http\Controllers\AdminStudentController::class, 'import'])->name('admin-students.import');
    Route::get('/admin-students/download-template', [\App\Http\Controllers\AdminStudentController::class, 'downloadTemplate'])->name('admin-students.download-template');
    
    Route::resource('admin-students', \App\Http\Controllers\AdminStudentController::class);
    Route::post('/admin-students/{id}/toggle-status', [\App\Http\Controllers\AdminStudentController::class, 'toggleUserStatus'])->name('admin-students.toggle-status');
    Route::post('/admin-students/activate-all', [\App\Http\Controllers\AdminStudentController::class, 'activateAll'])->name('admin-students.activate-all');
    Route::post('/admin-students/deactivate-all', [\App\Http\Controllers\AdminStudentController::class, 'deactivateAll'])->name('admin-students.deactivate-all');
    Route::post('/admin-students/bulk-action', [\App\Http\Controllers\AdminStudentController::class, 'bulkAction'])->name('admin-students.bulk-action');
    Route::post('/admin-students/{id}/reset-password', [\App\Http\Controllers\AdminStudentController::class, 'resetPassword'])->name('admin-students.reset-password');
    Route::delete('/admin-students/{id}/delete-photo', [\App\Http\Controllers\AdminStudentController::class, 'deletePhoto'])->name('admin-students.delete-photo');
    // Route::post('/students/bulk-action', [\App\Http\Controllers\StudentController::class, 'bulkAction'])->name('students.bulk-action');
    
    // Student Import/Export
    // Route::post('/students/import', [\App\Http\Controllers\StudentController::class, 'import'])->name('students.import');
    // Route::get('/students/download-template', [\App\Http\Controllers\StudentController::class, 'downloadTemplate'])->name('students.download-template');
    
    // Route::resource('students', \App\Http\Controllers\StudentController::class); // Moved to Auth Group
    Route::resource('units', \App\Http\Controllers\UnitController::class);
    Route::resource('academic-years', \App\Http\Controllers\AcademicYearController::class);
    Route::post('academic-years/{id}/activate', [\App\Http\Controllers\AcademicYearController::class, 'activateYear'])->name('academic-years.activate');
    Route::post('semesters/{id}/activate', [\App\Http\Controllers\AcademicYearController::class, 'activateSemester'])->name('semesters.activate');
    // Route::resource('classes', \App\Http\Controllers\SchoolClassController::class); // Moved to Auth Group
    // Route::resource('subjects', \App\Http\Controllers\SubjectController::class); // Moved to Auth Group
    
    // Backup Routes

    // Backup Routes
    Route::get('/backups', [\App\Http\Controllers\BackupController::class, 'index'])->name('backups.index');
    Route::post('/backups/create', [\App\Http\Controllers\BackupController::class, 'create'])->name('backups.create');
    Route::get('/backups/{filename}/download', [\App\Http\Controllers\BackupController::class, 'download'])->name('backups.download');
    Route::delete('/backups/{filename}/delete', [\App\Http\Controllers\BackupController::class, 'delete'])->name('backups.delete');
    Route::resource('financial-admins', \App\Http\Controllers\FinancialAdminController::class);
    
    // Setting Aplikasi
    Route::get('/settings', [\App\Http\Controllers\SettingController::class, 'index'])->name('settings.index');
    Route::post('/settings', [\App\Http\Controllers\SettingController::class, 'update'])->name('settings.update');
    Route::get('/audit-report', [\App\Http\Controllers\SystemAuditController::class, 'index'])->name('audit.report');
});

// Finance Routes (Accessible by Admin, Director, or Finance Admin)
Route::middleware(['auth', 'finance'])->group(function () {
    Route::group(['prefix' => 'finance', 'as' => 'finance.'], function() {
        // 1. Dashboard
        Route::get('/dashboard', [\App\Http\Controllers\FinanceDashboardController::class, 'index'])->name('dashboard');
        
        // 2. Pembayaran Siswa
        Route::get('/payments', [\App\Http\Controllers\FinancePaymentController::class, 'index'])->name('payments.index');
        Route::get('/payments/{student}', [\App\Http\Controllers\FinancePaymentController::class, 'show'])->name('payments.show');
        Route::post('/payments/{student}/pay', [\App\Http\Controllers\FinancePaymentController::class, 'store'])->name('payments.store');
        Route::get('/transactions', [\App\Http\Controllers\FinancePaymentController::class, 'transactionsHistory'])->name('transactions.index');
        Route::delete('/payments/transactions/{transaction}', [\App\Http\Controllers\FinancePaymentController::class, 'destroyTransaction'])->name('payments.transactions.destroy');

        // 3. Status & Atur Pembayaran Siswa
        Route::get('/student-bills', [\App\Http\Controllers\FinancePaymentController::class, 'billStatus'])->name('bills.index');
        Route::get('/student-bills/export/excel', [\App\Http\Controllers\FinancePaymentController::class, 'exportBillsExcel'])->name('bills.export.excel');
        Route::get('/student-bills/export/pdf', [\App\Http\Controllers\FinancePaymentController::class, 'exportBillsPdf'])->name('bills.export.pdf');
        Route::delete('/student-bills/bulk-destroy', [\App\Http\Controllers\FinancePaymentController::class, 'bulkDestroyBills'])->name('bills.bulk-destroy');
        Route::delete('/student-bills/{bill}', [\App\Http\Controllers\FinancePaymentController::class, 'destroyBill'])->name('bills.destroy');
        Route::get('/student-fees', [\App\Http\Controllers\FinancePaymentController::class, 'manageFees'])->name('student-fees.index');
        Route::post('/student-fees/store', [\App\Http\Controllers\FinancePaymentController::class, 'storeClassFees'])->name('payments.settings.store_class_fees'); 
        
        // 4. Atur Jenis Pembayaran (Types)
        Route::get('/payment-types', [\App\Http\Controllers\FinancePaymentController::class, 'manageTypes'])->name('payment-types.index');
        Route::post('/payment-types/store', [\App\Http\Controllers\FinancePaymentController::class, 'storeType'])->name('payments.settings.store');
        Route::get('/payment-types/{type}/edit', [\App\Http\Controllers\FinancePaymentController::class, 'editType'])->name('payment-types.edit');
        Route::put('/payment-types/{type}', [\App\Http\Controllers\FinancePaymentController::class, 'updateType'])->name('payment-types.update');
        Route::delete('/payment-types/{type}', [\App\Http\Controllers\FinancePaymentController::class, 'destroyType'])->name('payment-types.destroy');

        // 5. Pemasukan
        Route::get('/income', [\App\Http\Controllers\FinanceIncomeController::class, 'index'])->name('income.index');
        Route::post('/income', [\App\Http\Controllers\FinanceIncomeController::class, 'store'])->name('income.store');
        Route::delete('/income/{income}', [\App\Http\Controllers\FinanceIncomeController::class, 'destroy'])->name('income.destroy');
        Route::post('/income/categories', [\App\Http\Controllers\FinanceIncomeController::class, 'storeCategory'])->name('income.categories.store');
        Route::delete('/income/categories/{category}', [\App\Http\Controllers\FinanceIncomeController::class, 'destroyCategory'])->name('income.categories.destroy');
        Route::get('/income/{income}/print', [\App\Http\Controllers\FinanceIncomeController::class, 'print'])->name('income.print');

        // 6. Pengeluaran
        Route::get('/expense', [\App\Http\Controllers\FinanceExpenseController::class, 'index'])->name('expense.index');
        Route::post('/expense', [\App\Http\Controllers\FinanceExpenseController::class, 'store'])->name('expense.store');
        Route::delete('/expense/{expense}', [\App\Http\Controllers\FinanceExpenseController::class, 'destroy'])->name('expense.destroy');
        Route::post('/expense/categories', [\App\Http\Controllers\FinanceExpenseController::class, 'storeCategory'])->name('expense.categories.store');
        Route::delete('/expense/categories/{category}', [\App\Http\Controllers\FinanceExpenseController::class, 'destroyCategory'])->name('expense.categories.destroy');
        Route::get('/expense/{expense}/print', [\App\Http\Controllers\FinanceExpenseController::class, 'print'])->name('expense.print');
        Route::post('/expense/from-procurement', [\App\Http\Controllers\FinanceExpenseController::class, 'storeProcurementExpense'])->name('expense.store-procurement');
        Route::post('/realization/verify', [\App\Http\Controllers\FinanceExpenseController::class, 'verifyProcurementReport'])->name('realization.verify');
        Route::post('/realization/cancel', [\App\Http\Controllers\FinanceExpenseController::class, 'cancelVerification'])->name('realization.cancel');
        Route::post('/expense/{id}/upload-proof', [\App\Http\Controllers\FinanceExpenseController::class, 'uploadGeneralProof'])->name('expense.upload-proof');

        // Realization Reports
        Route::get('/realization', [\App\Http\Controllers\FinanceExpenseController::class, 'realization'])->name('realization.index');
        Route::post('/realization/verify', [\App\Http\Controllers\FinanceExpenseController::class, 'verifyProcurementReport'])->name('realization.verify');

        // 8. Akun Bank
        Route::resource('bank-accounts', \App\Http\Controllers\FinanceBankAccountController::class);

        // 7. Laporan
        Route::get('/reports', [\App\Http\Controllers\FinanceReportController::class, 'index'])->name('reports.index');
        Route::get('/reports/arrears', [\App\Http\Controllers\FinanceReportController::class, 'arrears'])->name('reports.arrears');
        Route::get('/reports/student-payments', [\App\Http\Controllers\FinanceReportController::class, 'studentPayments'])->name('reports.student-payments');
        Route::get('/reports/general-ledger', [\App\Http\Controllers\FinanceReportController::class, 'generalLedger'])->name('reports.general-ledger');

        // 8. Verifikasi Pembayaran
        Route::get('/verifications', [\App\Http\Controllers\PaymentVerificationController::class, 'index'])->name('verifications.index');
        Route::get('/verifications/{paymentRequest}', [\App\Http\Controllers\PaymentVerificationController::class, 'show'])->name('verifications.show');
        Route::post('/verifications/{paymentRequest}/verify', [\App\Http\Controllers\PaymentVerificationController::class, 'verify'])->name('verifications.verify');
        Route::post('/verifications/{paymentRequest}/reject', [\App\Http\Controllers\PaymentVerificationController::class, 'reject'])->name('verifications.reject');
        Route::delete('/verifications/{paymentRequest}', [\App\Http\Controllers\PaymentVerificationController::class, 'destroy'])->name('verifications.destroy');
    });
});

// Route accessible by both Staff and Students
Route::middleware(['auth:web,student'])->get('/graduation/download-skl/{id}', [\App\Http\Controllers\GraduationAnnouncementController::class, 'downloadSkl'])->name('graduation.download-skl');


Route::middleware(['auth:student', 'siswa'])->prefix('siswa')->name('siswa.')->group(function () {
    Route::get('/dashboard', [\App\Http\Controllers\Siswa\SiswaDashboardController::class, 'index'])->name('dashboard');
    Route::get('/profil', [\App\Http\Controllers\Siswa\SiswaProfilController::class, 'index'])->name('profil');
    Route::post('/profil/update-password', [\App\Http\Controllers\Siswa\SiswaProfilController::class, 'updatePassword'])->name('profil.update-password');
    // Placeholders for other requested menus
    Route::get('/jadwal', [\App\Http\Controllers\Siswa\SiswaJadwalController::class, 'index'])->name('jadwal');
    Route::get('/nilai', [\App\Http\Controllers\Siswa\SiswaPointController::class, 'index'])->name('nilai');
    Route::get('/absensi', [\App\Http\Controllers\Siswa\SiswaAbsensiController::class, 'index'])->name('absensi');
    Route::get('/pengumuman', [\App\Http\Controllers\Siswa\SiswaPengumumanController::class, 'index'])->name('pengumuman');
    Route::get('/riwayat-pembayaran', [\App\Http\Controllers\Siswa\SiswaPaymentController::class, 'history'])->name('payments.history');
    Route::get('/tunggakan', [\App\Http\Controllers\Siswa\SiswaPaymentController::class, 'arrears'])->name('payments.arrears');
    
    // Payment Requests (Online Payment)
    Route::get('/pembayaran/baru', [\App\Http\Controllers\Siswa\SiswaPaymentRequestController::class, 'create'])->name('payments.requests.create');
    Route::post('/pembayaran/store', [\App\Http\Controllers\Siswa\SiswaPaymentRequestController::class, 'store'])->name('payments.requests.store');
    Route::get('/pembayaran/status', [\App\Http\Controllers\Siswa\SiswaPaymentRequestController::class, 'index'])->name('payments.requests.index');
    Route::get('/pembayaran/status/{id}', [\App\Http\Controllers\Siswa\SiswaPaymentRequestController::class, 'show'])->name('payments.requests.show');
    Route::get('/pembayaran/status/{id}/print', [\App\Http\Controllers\Siswa\SiswaPaymentRequestController::class, 'print'])->name('payments.requests.print');
    Route::post('/pembayaran/status/{id}/update-proof', [\App\Http\Controllers\Siswa\SiswaPaymentRequestController::class, 'updateProof'])->name('payments.requests.update-proof');
});

/*
// Temporary route to run migrations since terminal is inaccessible
Route::get('/fix-db', function() {
    $output = "<h1>Database Migration Tool</h1>";
    try {
        \Illuminate\Support\Facades\Artisan::call('config:clear');
        \Illuminate\Support\Facades\Artisan::call('cache:clear');
        \Illuminate\Support\Facades\Artisan::call('migrate', ['--force' => true]);
        $output .= "<h2 style='color:green'>Migration Success!</h2>";
        $output .= "<pre>" . \Illuminate\Support\Facades\Artisan::output() . "</pre>";
        
        $output .= "<h3>Table Status:</h3>";
        $output .= "Payments: " . (\Illuminate\Support\Facades\Schema::hasTable('payments') ? 'EXISTS' : 'MISSING') . "<br>";
        $output .= "Receipts: " . (\Illuminate\Support\Facades\Schema::hasTable('receipts') ? 'EXISTS' : 'MISSING') . "<br>";
        
        $migrations = \Illuminate\Support\Facades\DB::table('migrations')->orderBy('id', 'desc')->limit(5)->get();
        $output .= "<h3>Recent Migrations:</h3><ul>";
        foreach($migrations as $m) {
            $output .= "<li>{$m->migration} (Batch: {$m->batch})</li>";
        }
        $output .= "</ul>";
    } catch (\Exception $e) {
        $output .= "<h2 style='color:red'>Migration Failed</h2>";
        $output .= "<p>Error: " . $e->getMessage() . "</p>";
    }
    return $output;
});



Route::get('/fix-orphans', function() {
    $students = \App\Models\Student::whereNull('user_siswa_id')->get();
    $count = 0;
    foreach($students as $student) {
        // Create UserSiswa
        $username = $student->nis ?? strtolower(str_replace(' ', '', $student->nama_lengkap)) . rand(100,999);
        
        $userSiswa = \App\Models\UserSiswa::create([
            'name' => $student->nama_lengkap,
            'username' => $username,
            'email' => $username . '@student.school.id', // Placeholder email
            'password' => \Illuminate\Support\Facades\Hash::make($student->nis ?? '12345678'),
            'plain_password' => $student->nis ?? '12345678',
        ]);
        
        $student->user_siswa_id = $userSiswa->id;
        $student->save();
        $count++;
    }
    return "Fixed $count orphan students. They now have UserSiswa accounts.";
});
*/

/*
Route::get('/fix-bill-sync', function() {
    $output = "<h1>Bill Synchronization Tool</h1>";
    
    DB::beginTransaction();
    try {
        // 1. Reset all non-free bills to 0 paid
        $resetCount = \App\Models\StudentBill::where('is_free', 0)->update([
            'paid_amount' => 0,
            'status' => 'unpaid'
        ]);
        $output .= "<p>Reset $resetCount bills to unpaid state.</p>";

        // 2. Get all valid transactions (not void)
        $transactions = \App\Models\Transaction::where('is_void', 0)->with('items')->get();
        $processedItems = 0;

        foreach($transactions as $trx) {
            foreach($trx->items as $item) {
                // Try finding bill by ID first
                $bill = null;
                if (!empty($item->student_bill_id)) {
                    $bill = \App\Models\StudentBill::find($item->student_bill_id);
                }

                // If no ID or not found, try matching logic
                if (!$bill) {
                    $query = \App\Models\StudentBill::where('student_id', $trx->student_id)
                        ->where('payment_type_id', $item->payment_type_id);
                    
                    if ($item->month_paid) {
                        $query->where('month', $item->month_paid);
                    }
                    
                    // Match year logic if possible, otherwise rely on available bill
                    if ($item->year_paid) {
                        $query->whereHas('academicYear', function($q) use ($item) {
                            $q->where('start_year', $item->year_paid);
                        });
                    }

                    $bill = $query->first();
                }

                if ($bill) {
                    $bill->paid_amount += $item->amount;
                    
                    // Update status
                    if ($bill->paid_amount >= $bill->amount) {
                         // Cap at amount? Maybe safer to avoid weird >100%
                         // $bill->paid_amount = $bill->amount; 
                         $bill->status = 'paid';
                    } elseif ($bill->paid_amount > 0) {
                        $bill->status = 'partial';
                    } else {
                        $bill->status = 'unpaid';
                    }
                    
                    $bill->save();
                    
                    // Backfill student_bill_id if missing
                    if (empty($item->student_bill_id)) {
                        $item->student_bill_id = $bill->id;
                        $item->save();
                    }
                    
                    $processedItems++;
                }
            }
        }
        
        // 3. Handle Free Bills (Ensure they are marked paid)
        \App\Models\StudentBill::where('is_free', 1)->update(['status' => 'paid', 'paid_amount' => 0]); 
        // Note: Free bills usually have amount=X but paid=0 and status=paid? Or amount=0?
        // Logic in storeClassFees: amount=0, status=paid. 
        // Logic in bulk views: amount=0.
        // Let's ensure amount is 0 if is_free
        DB::statement("UPDATE student_bills SET amount = 0, paid_amount = 0, status = 'paid' WHERE is_free = 1");

        DB::commit();
        $output .= "<p style='color:green'>Successfully processed $processedItems transaction items.</p>";
        $output .= "<p>Bills are now synchronized with transaction history.</p>";
        
    } catch (\Exception $e) {
        DB::rollBack();
        $output .= "<h3 style='color:red'>Error: " . $e->getMessage() . "</h3>";
    }
    
    return $output;
});
*/

/*
Route::get('/debug-db', function() {
    $units = \App\Models\Unit::with('subjects')->get();
    $html = "<h1>Database Debug Check</h1>";
    $html .= "<table border='1' cellpadding='5' style='border-collapse:collapse;'>";
    $html .= "<tr><th>Unit ID</th><th>Unit Name</th><th>Subjects Count</th><th>Subject Names</th></tr>";
    
    foreach($units as $unit) {
        $subjectNames = $unit->subjects->map(function($s) {
            return $s->name . " (ID:{$s->id})";
        })->implode(', ');
        
        $html .= "<tr>";
        $html .= "<td>{$unit->id}</td>";
        $html .= "<td>{$unit->name}</td>";
        $html .= "<td>{$unit->subjects->count()}</td>";
        $html .= "<td>" . ($subjectNames ?: '<span style="color:red;">NO SUBJECTS</span>') . "</td>";
        $html .= "</tr>";
    }
    $html .= "</table>";
    return $html;
});

Route::get('/debug-users', function() {
    $keuangan = \App\Models\User::whereHas('jabatans', function($q) {
        $q->where('nama_jabatan', 'LIKE', '%Keuangan%')
          ->orWhere('nama_jabatan', 'LIKE', '%Bendahara%');
    })->orWhere('username', 'LIKE', 'admin_keuangan%')->get();

    $allUsers = \App\Models\User::with('jabatans', 'jabatanUnits.jabatan')->get();

    return response()->json([
        'count_filtered' => $keuangan->count(),
        'filtered_names' => $keuangan->pluck('name'),
        'all_users_count' => $allUsers->count(),
        'all_users_debug' => $allUsers->map(function($u) {
            return [
                'id' => $u->id,
                'name' => $u->name,
                'role' => $u->role,
                'username' => $u->username,
                'email' => $u->email,
                'jabatans_count' => $u->jabatans->count(),
                'jabatans_names' => $u->jabatans->pluck('nama_jabatan'),
                'jabatan_units_count' => $u->jabatanUnits->count(),
                'jabatan_units_names' => $u->jabatanUnits->map(fn($ju) => $ju->jabatan ? $ju->jabatan->nama_jabatan : 'N/A'),
            ];
        })
    ]);
});

Route::get('/seed-dummy-data', function() {
    DB::beginTransaction();
    try {
        $units = ['SD NURUL ILMI', 'SMP NURUL ILMI', 'SMA NURUL ILMI'];
        $createdUnits = [];
        
        foreach ($units as $unitName) {
            $unit = \App\Models\Unit::firstOrCreate(['name' => $unitName]);
            $createdUnits[$unitName] = $unit;
        }

        // Subjects per unit
        $subjects = [
            'SD NURUL ILMI' => ['Matematika', 'Bahasa Indonesia', 'IPA', 'IPS', 'PPKn', 'PAI', 'PJOK', 'Seni Budaya'],
            'SMP NURUL ILMI' => ['Matematika', 'Bahasa Indonesia', 'Bahasa Inggris', 'IPA', 'IPS', 'PPKn', 'PAI', 'PJOK', 'Seni Budaya', 'TIK'],
            'SMA NURUL ILMI' => ['Matematika Wajib', 'Matematika Peminatan', 'Bahasa Indonesia', 'Bahasa Inggris', 'Fisika', 'Kimia', 'Biologi', 'Ekonomi', 'Sejarah', 'Geografi', 'Sosiologi', 'PAI', 'PJOK']
        ];

        $subjectCount = 0;
        foreach ($subjects as $unitName => $subs) {
            $unit = $createdUnits[$unitName];
            foreach ($subs as $subName) {
                \App\Models\Subject::firstOrCreate(
                    ['unit_id' => $unit->id, 'name' => $subName],
                    ['code' => strtoupper(substr($unitName, 0, 3)) . '-' . strtoupper(substr($subName, 0, 3)) . rand(10, 99)]
                );
                $subjectCount++;
            }
        }

        // Classes per unit
        $classes = [
            'SD NURUL ILMI' => ['1A', '1B', '2A', '2B', '3A', '4A', '5A', '6A'],
            'SMP NURUL ILMI' => ['7A', '7B', '8A', '8B', '9A', '9B'],
            'SMA NURUL ILMI' => ['10 IPA 1', '10 IPA 2', '10 IPS 1', '11 IPA 1', '11 IPS 1', '12 IPA 1', '12 IPS 1']
        ];

        $classCount = 0;
        foreach ($classes as $unitName => $clsList) {
            $unit = $createdUnits[$unitName];
            foreach ($clsList as $clsName) {
                // Determine grade code heuristic
                preg_match('/\d+/', $clsName, $matches);
                $grade = $matches[0] ?? '1';

                \App\Models\SchoolClass::firstOrCreate(
                    ['unit_id' => $unit->id, 'name' => $clsName],
                    ['grade_code' => $grade, 'code' => str_replace(' ', '', $clsName)]
                );
                $classCount++;
            }
        }

        // Seed Financial Admin
        $jabatanKeuangan = \App\Models\Jabatan::firstOrCreate(
            ['nama_jabatan' => 'Kepala Keuangan'],
            ['kategori' => 'staff']
        );

        $userKeuangan = \App\Models\User::firstOrCreate(
            ['email' => 'keuangan@nurulilmi.id'],
            [
                'name' => 'Kepala Keuangan',
                'username' => 'admin_keuangan',
                'password' => \Illuminate\Support\Facades\Hash::make('password'),
                'role' => 'staff',
                'status' => 'aktif',
            ]
        );

        $unitPusat = \App\Models\Unit::first();
        if ($unitPusat) {
            \App\Models\UserJabatanUnit::firstOrCreate(
                [
                    'user_id' => $userKeuangan->id,
                    'jabatan_id' => $jabatanKeuangan->id,
                    'unit_id' => $unitPusat->id
                ]
            );
            
            // Also attach to old pivot
             if (!$userKeuangan->jabatans()->where('jabatan_id', $jabatanKeuangan->id)->exists()) {
                $userKeuangan->jabatans()->attach($jabatanKeuangan->id);
            }
        }

        DB::commit();
        return "<h1>Seeding Complete!</h1><p>Generated/Verified:<br>Units: " . count($createdUnits) . "<br>Subjects: $subjectCount<br>Classes: $classCount<br>Financial Admin: OK</p><a href='/financial-admins'>Go to Financial Admins</a>";

    } catch (\Exception $e) {
        DB::rollBack();
        return "Seeding Failed: " . $e->getMessage();
    }
})->middleware('auth', 'role:administrator');

/*
Route::get('/setup-mading', function() {
    try {
        // Ensure role exists in User model (handled in code, but we can't change code from here easily if it wasn't done)
        // Creating the user
        $u = \App\Models\User::firstOrCreate(
            ['username' => 'mading'],
            [
                'name' => 'Display Mading',
                'email' => 'mading@nurulilmi.id',
                'password' => \Illuminate\Support\Facades\Hash::make('password'),
                'role' => 'mading', 
                'status' => 'aktif'
            ]
        );
        
        // Update role if it was different
        if ($u->role !== 'mading') {
            $u->role = 'mading';
            $u->save();
        }

        return "<h1>Mading Setup Successful</h1><p>User 'mading' is ready.</p><p>Go to <a href='/login'>Login</a> and use username: <b>mading</b>, password: <b>password</b></p>";
    } catch (\Exception $e) {
        return "Setup Failed: " . $e->getMessage();
    }
});
Route::get('/debug-fix-orphan-schedules', function() {
    return \App\Models\SchoolClass::all()->map(function($c){
        return "ID: {$c->id}, Name: {$c->name}, AY: {$c->academic_year_id}";
    });
});
*/

/*
Route::get('/seed-checkins', function() {
    // 1. Get some schedules
    $schedules = \App\Models\Schedule::with('schoolClass')->take(50)->get();
    
    if ($schedules->isEmpty()) {
        return "No schedules found. Please create schedules first.";
    }
    
    $count = 0;
    
    // 2. Loop and create checkins for the last 7 days
    for ($i = 0; $i < 7; $i++) {
        $date = now()->subDays($i);
        $dayNameEn = $date->format('l');
        
        // Map English day to Indonesian
        $dayMap = [
            'Monday' => 'Senin',
            'Tuesday' => 'Selasa',
            'Wednesday' => 'Rabu',
            'Thursday' => 'Kamis',
            'Friday' => 'Jumat',
            'Saturday' => 'Sabtu',
            'Sunday' => 'Minggu',
        ];
        $dayNameId = $dayMap[$dayNameEn];
        
        foreach ($schedules as $schedule) {
            // Only create if schedule day matches (simple simulation)
            if ($schedule->day === $dayNameId) {
                
                // Randomize status
                $rand = rand(1, 10);
                $status = 'ontime';
                $notes = 'Materi lancar';
                if ($rand > 8) { $status = 'late'; $notes = 'Maaf terlambat macet'; }
                
                // Check if exists
                $exists = \App\Models\ClassCheckin::where('schedule_id', $schedule->id)
                    ->whereDate('checkin_time', $date->format('Y-m-d'))
                    ->exists();
                    
                if (!$exists) {
                    \App\Models\ClassCheckin::create([
                        'schedule_id' => $schedule->id,
                        'user_id' => $schedule->user_id ?? 1, // Fallback if no user assigned to schedule
                        'checkin_time' => $date->setTime(rand(7, 14), rand(0, 59)),
                        'status' => $status,
                        'notes' => $notes,
                        'latitude' => -6.200000 + (rand(-100, 100) / 10000),
                        'longitude' => 106.816666 + (rand(-100, 100) / 10000),
                    ]);
                    $count++;
                }
            }
        }
    }
    
    return "Seeding Complete. Created $count check-in records for the past week.";
});
*/

/*
Route::get('/fix-db', function() {
    try {
        // 1. Run Migrations (for the new table)
        Artisan::call('migrate', ['--force' => true]);
        $output = Artisan::output();
        
        // 2. Force Nullable columns on transactions (Raw SQL backup)
        // We use raw SQL because change() might fail if doctrine/dbal is missing or migration already run
        DB::statement("ALTER TABLE transactions MODIFY payment_type_id BIGINT UNSIGNED NULL");
        DB::statement("ALTER TABLE transactions MODIFY month_paid INT NULL");
        DB::statement("ALTER TABLE transactions MODIFY year_paid VARCHAR(255) NULL");
        DB::statement("ALTER TABLE transactions MODIFY transaction_date DATETIME NULL");

        // Add VOID columns if not exists
        if (!Schema::hasColumn('transactions', 'is_void')) {
            DB::statement("ALTER TABLE transactions ADD COLUMN is_void TINYINT(1) DEFAULT 0");
        }
        if (!Schema::hasColumn('transactions', 'void_reason')) {
            DB::statement("ALTER TABLE transactions ADD COLUMN void_reason TEXT NULL");
        }

        // Add security_pin column to users if not exists
        if (!Schema::hasColumn('users', 'security_pin')) {
            DB::statement("ALTER TABLE users ADD COLUMN security_pin VARCHAR(255) NULL");
        }

        // Add is_free column if not exists
        if (!Schema::hasColumn('student_bills', 'is_free')) {
            DB::statement("ALTER TABLE student_bills ADD COLUMN is_free TINYINT(1) DEFAULT 0");
        }
        if (!Schema::hasColumn('student_payment_settings', 'is_free')) {
            DB::statement("ALTER TABLE student_payment_settings ADD COLUMN is_free TINYINT(1) DEFAULT 0");
        }

        // 3. Fix Student Violations missing Academic Year
        $activeYear = \App\Models\AcademicYear::where('status', 'active')->first();
        if ($activeYear) {
            DB::table('student_violations')
                ->whereNull('academic_year_id')
                ->update(['academic_year_id' => $activeYear->id]);
        }
        
        return "Migration & Schema Fix Success!<br><br>Log:<br>" . nl2br($output) . "<br>Columns updated. is_free column added to bills and settings.";
    } catch (\Exception $e) {
        return "Fix failed: " . $e->getMessage();
    }
});
*/

Route::delete('/finance/transactions/{transaction}/force-delete', [\App\Http\Controllers\FinancePaymentController::class, 'forceDeleteTransaction'])
    ->name('finance.payments.transactions.force-delete')->middleware('auth');

Route::post('/finance/transactions/{transaction}/unvoid', [App\Http\Controllers\FinancePaymentController::class, 'unvoidTransaction'])
    ->name('finance.payments.transactions.unvoid')->middleware('auth');

Route::get('/finance/transactions/export/excel', [App\Http\Controllers\FinancePaymentController::class, 'exportExcel'])
    ->name('finance.transactions.export.excel')->middleware('auth');

Route::get('/finance/transactions/export/pdf', [App\Http\Controllers\FinancePaymentController::class, 'exportPdf'])
    ->name('finance.transactions.export.pdf')->middleware('auth');
/*
Route::get('/clean-bills', function() {
    try {
        $count = \App\Models\StudentBill::count();
        \App\Models\StudentBill::query()->delete();
        return "Deleted $count student bills successfully.";
    } catch (\Exception $e) {
        return "Failed to delete bills: " . $e->getMessage();
    }
});
Route::get('/delete-target-invoice', function() {
    $invoice = 'INV-20251229104317-993';
    try {
        $trx = \App\Models\Transaction::where('invoice_number', $invoice)->first();
        if ($trx) {
            $trx->delete();
            return "Transaksi $invoice berhasil dihapus permanen.";
        }
        return "Transaksi $invoice tidak ditemukan.";
    } catch (\Exception $e) {
        return "Gagal menghapus: " . $e->getMessage();
    }
});
*/
