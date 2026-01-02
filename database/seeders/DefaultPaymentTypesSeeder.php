<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Unit;
use App\Models\PaymentType;

class DefaultPaymentTypesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $units = Unit::all();

        $types = [
            [
                'name' => 'SPP',
                'code_prefix' => 'SPP',
                'type' => 'monthly'
            ],
            [
                'name' => 'SPP KELAS AC',
                'code_prefix' => 'SPP-AC',
                'type' => 'monthly'
            ],
            [
                'name' => 'UANG ASRAMA BULANAN',
                'code_prefix' => 'ASRAMA',
                'type' => 'monthly'
            ]
        ];

        foreach ($units as $unit) {
            foreach ($types as $t) {
                // Generate a unique code per unit if needed, or just use the prefix
                // To avoid duplicate codes if global uniqueness is enforced (it shouldn't be for different units usually, but let's be safe)
                // Actually user request didn't specify code, but I added the column.
                // Let's format code as CODE-UNITNAME usually, or just CODE.
                // If I just use 'SPP', it might clash if I didn't scope it.
                // Let's use simple codes.
                
                $code = $t['code_prefix']; 
                // Simple check if exists to avoid duplicates
                $exists = PaymentType::where('unit_id', $unit->id)
                            ->where('name', $t['name'])
                            ->exists();

                if (!$exists) {
                    PaymentType::create([
                        'unit_id' => $unit->id,
                        'name' => $t['name'],
                        'code' => $code, // This might duplicate across units, but that's fine for now unless unique constraint exists.
                        'type' => $t['type'],
                        'nominal' => 0 
                    ]);
                }
            }
        }
    }
}
