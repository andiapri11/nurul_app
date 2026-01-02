<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ExtracurricularReport extends Model
{
    protected $fillable = [
        'extracurricular_id',
        'academic_year_id',
        'title',
        'file_path',
        'description'
    ];

    public function extracurricular()
    {
        return $this->belongsTo(Extracurricular::class);
    }

    public function academicYear()
    {
        return $this->belongsTo(AcademicYear::class);
    }
}
