<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class ViolationsExport implements FromView, ShouldAutoSize, WithStyles
{
    protected $violations;
    protected $filterSummary;

    public function __construct($violations, $filterSummary)
    {
        $this->violations = $violations;
        $this->filterSummary = $filterSummary;
    }

    public function view(): View
    {
        return view('student_affairs.violations.excel', [
            'violations' => $this->violations,
            'filterSummary' => $this->filterSummary
        ]);
    }

    public function styles(Worksheet $sheet)
    {
        return [
            // Bold header
            1 => ['font' => ['bold' => true, 'size' => 14]],
            // Filter summary styles could go here if needed
        ];
    }
}
