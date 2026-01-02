<?php

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
        Schema::create('student_violations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_id')->constrained('students')->onDelete('cascade');
            $table->date('date');
            $table->string('violation_type'); // Ringan, Sedang, Berat
            $table->text('description');
            $table->integer('points')->default(0);
            $table->text('follow_up')->nullable(); // Tindak lanjut
            $table->foreignId('recorded_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
        });

        Schema::create('student_achievements', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_id')->constrained('students')->onDelete('cascade');
            $table->date('date');
            $table->string('achievement_name'); // Nama Lomba/Prestasi
            $table->string('level'); // Sekolah, Kecamatan, etc.
            $table->string('rank')->nullable(); // Juara 1, 2, dll
            $table->text('description')->nullable();
            $table->string('proof')->nullable(); // Foto / Sertifikat path
            $table->foreignId('recorded_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('student_achievements');
        Schema::dropIfExists('student_violations');
    }
};
