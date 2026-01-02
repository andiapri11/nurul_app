<?php
/**
 * Migration to add reporting fields to procurement_requests table.
 * These fields enable Sarpras (Wakil Sarana) to upload report proof (nota and photo) 
 * for approved procurements, which then requires finance approval.
 */

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('procurement_requests', function (Blueprint $table) {
            $table->string('report_nota')->nullable()->after('photo');
            $table->string('report_photo')->nullable()->after('report_nota');
            $table->string('report_status')->default('Pending')->after('report_photo'); // Pending, Reported, Approved, Rejected
            $table->timestamp('report_at')->nullable()->after('report_status');
            $table->text('report_note')->nullable()->after('report_at');
            $table->timestamp('finance_approved_at')->nullable()->after('report_note');
            $table->text('finance_note')->nullable()->after('finance_approved_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('procurement_requests', function (Blueprint $table) {
            $table->dropColumn([
                'report_nota', 'report_photo', 'report_status', 
                'report_at', 'report_note', 'finance_approved_at', 'finance_note'
            ]);
        });
    }
};
