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
        Schema::create('extracurricular_members', function (Blueprint $table) {
            $table->id();
            $table->foreignId('extracurricular_id')->constrained()->onDelete('cascade');
            $table->foreignId('student_id')->constrained()->onDelete('cascade');
            $table->foreignId('academic_year_id')->constrained()->onDelete('cascade');
            $table->string('role')->default('Anggota'); // e.g., Ketua, Sekretaris, Anggota
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('extracurricular_members');
    }
};
