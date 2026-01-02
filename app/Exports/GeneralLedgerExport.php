<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class GeneralLedgerExport implements FromCollection, WithHeadings, WithMapping, ShouldAutoSize, WithStyles
{
    protected $records;
    protected $period;
    protected $date;
    protected $month;
    protected $year;

    public function __construct($records, $period, $date, $month, $year)
    {
        $this->records = $records;
        $this->period = $period;
        $this->date = $date;
        $this->month = $month;
        $this->year = $year;
    }

    public function collection()
    {
        return $this->records;
    }

    public function headings(): array
    {
        return [
            'Tanggal',
            'Tipe',
            'Kategori',
            'Nama Pihak (Keterangan)',
            'Unit',
            'Metode',
            'Jumlah (Rp)',
            'Pencatat/Admin'
        ];
    }

    public function map($record): array
    {
        return [
            $record->transaction_date->format('d/m/Y'),
            strtoupper($record->type),
            $record->category,
            ($record->payer_name ?: '-') . ' (' . ($record->description ?: '-') . ')',
            $record->unit->name ?? 'Internal/Umum',
            strtoupper($record->payment_method),
            number_format($record->amount, 0, ',', '.'),
            $record->user->name ?? '-'
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => ['font' => ['bold' => true]],
        ];
    }
}
