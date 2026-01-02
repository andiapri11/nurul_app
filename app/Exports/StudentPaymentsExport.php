<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class StudentPaymentsExport implements FromCollection, WithHeadings, WithMapping, ShouldAutoSize, WithStyles
{
    protected $transactions;
    protected $period;
    protected $date;
    protected $month;
    protected $year;

    public function __construct($transactions, $period, $date, $month, $year)
    {
        $this->transactions = $transactions;
        $this->period = $period;
        $this->date = $date;
        $this->month = $month;
        $this->year = $year;
    }

    public function collection()
    {
        return $this->transactions;
    }

    public function headings(): array
    {
        return [
            'No. Invoice',
            'Tanggal',
            'NIS',
            'Nama Siswa',
            'Unit',
            'Metode',
            'Penerima',
            'Rincian Pembayaran',
            'Total (Rp)'
        ];
    }

    public function map($transaction): array
    {
        $details = $transaction->items->map(function($item) {
            return $item->paymentType->name . ($item->month_paid ? ' (' . $item->month_paid . '/' . $item->year_paid . ')' : '');
        })->implode(', ');

        return [
            $transaction->invoice_number,
            $transaction->transaction_date->format('d/m/Y H:i'),
            $transaction->student->nis ?? '-',
            $transaction->student->nama_lengkap ?? '-',
            $transaction->student->unit->name ?? '-',
            strtoupper($transaction->payment_method),
            $transaction->user->name ?? '-',
            $details,
            number_format($transaction->amount, 0, ',', '.')
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => ['font' => ['bold' => true]],
        ];
    }
}
