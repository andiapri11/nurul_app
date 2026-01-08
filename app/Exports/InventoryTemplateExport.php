<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class InventoryTemplateExport implements FromArray, WithHeadings, WithStyles
{
    public function array(): array
    {
        return [
            [
                'CONTOH: Meja Guru', 
                'Furniture', 
                'Ruang Guru 1', 
                'Baik', 
                '500000', 
                'Dana BOS', 
                'Budi Sudarsono', 
                'Ya', 
                '2023-12-01', 
                'UNIT/INV-00001'
            ]
        ];
    }

    public function headings(): array
    {
        return [
            'Nama Barang',
            'Kategori',
            'Ruangan',
            'Kondisi',
            'Harga',
            'Sumber Keterangan',
            'Penanggung Jawab',
            'Bantuan Hibah',
            'Tanggal Beli',
            'Kode Barang'
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => ['font' => ['bold' => true]],
        ];
    }
}
