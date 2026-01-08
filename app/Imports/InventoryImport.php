<?php

namespace App\Imports;

use App\Models\Inventory;
use App\Models\InventoryCategory;
use App\Models\Room;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Concerns\SkipsEmptyRows;

class InventoryImport implements ToModel, WithHeadingRow, WithValidation, SkipsEmptyRows
{
    protected $unitId;
    protected $academicYearId;
    protected $categories;
    protected $rooms;

    public function __construct($unitId, $academicYearId)
    {
        $this->unitId = $unitId;
        $this->academicYearId = $academicYearId;
        
        // Cache categories and rooms for faster lookup
        $this->categories = InventoryCategory::where('unit_id', $unitId)
            ->where('academic_year_id', $academicYearId)
            ->pluck('id', 'name')
            ->toArray();
            
        $this->rooms = Room::where('unit_id', $unitId)
            ->where('academic_year_id', $academicYearId)
            ->pluck('id', 'name')
            ->toArray();
    }

    public function model(array $row)
    {
        $categoryName = trim($row['kategori']);
        $roomName = trim($row['ruangan'] ?? '');
        
        $categoryId = $this->categories[$categoryName] ?? null;
        $roomId = !empty($roomName) ? ($this->rooms[$roomName] ?? null) : null;

        // Condition mapping
        $conditionRaw = strtolower(trim($row['kondisi'] ?? 'baik'));
        $condition = 'Good';
        if (str_contains($conditionRaw, 'ringan') || $conditionRaw == 'damaged') {
            $condition = 'Damaged';
        } elseif (str_contains($conditionRaw, 'perbaikan') || $conditionRaw == 'repairing') {
            $condition = 'Repairing';
        } elseif (str_contains($conditionRaw, 'berat') || $conditionRaw == 'broken') {
            $condition = 'Broken';
        }

        // Price cleaning
        $priceRaw = $row['harga'] ?? '';
        $price = preg_replace('/[^0-9]/', '', $priceRaw);
        
        // Format Purchase Date
        $purchaseDate = null;
        if (!empty($row['tanggal_beli'])) {
            try {
                if (is_numeric($row['tanggal_beli'])) {
                    $purchaseDate = \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($row['tanggal_beli']);
                } else {
                    $purchaseDate = date('Y-m-d', strtotime($row['tanggal_beli']));
                }
            } catch (\Exception $e) {
                $purchaseDate = null;
            }
        }

        return new Inventory([
            'inventory_category_id' => $categoryId,
            'room_id' => $roomId,
            'name' => $row['nama_barang'],
            'code' => $row['kode_barang'],
            'condition' => $condition,
            'price' => !empty($price) ? $price : null,
            'source' => $row['sumber_keterangan'] ?? null,
            'person_in_charge' => $row['penanggung_jawab'] ?? null,
            'is_grant' => (strtolower($row['bantuan_hibah'] ?? '') == 'ya' || $row['bantuan_hibah'] == '1'),
            'purchase_date' => $purchaseDate,
        ]);
    }

    public function rules(): array
    {
        return [
            'nama_barang' => 'required',
            'kategori' => 'required',
            'kode_barang' => 'required|unique:inventories,code',
        ];
    }

    public function customValidationMessages()
    {
        return [
            'nama_barang.required' => 'Nama Barang tidak boleh kosong.',
            'kategori.required' => 'Kategori wajib diisi.',
            'kode_barang.required' => 'Kode Barang wajib diisi.',
            'kode_barang.unique' => 'Kode Barang :input sudah terdaftar di sistem. Gunakan kode yang unik.',
        ];
    }
}
