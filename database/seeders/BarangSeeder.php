<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class BarangSeeder extends Seeder
{
    public function run(): void
    {
        $dataBarang = [
            [
                'kode_barang' => '100000243',
                'deskripsi' => 'SWITCH, LIMIT, YBLX - 10/11 SWITCH, 380V AC, 220V DC, 1TH ( 10 AMPERE ), IEC60947-S-1',
            ],
            [
                'kode_barang' => '100000249',
                'deskripsi' => 'BRUSH, CARBON, CB 6100',
            ],
            [
                'kode_barang' => '100000072',
                'deskripsi' => 'FILTER, ELEMENT, LUBE FILTER, OD 950 MM, HEIGHT 170 MM, P558615 (DONALDSON) / LF 3349',
            ],
            [
                'kode_barang' => '100000070',
                'deskripsi' => 'FUEL FILTER, FLEETGUARD, PFC075/FS1280 (PROGUARD), WATER SEPARATOR',
            ],
            [
                'kode_barang' => '100000069',
                'deskripsi' => 'FUEL FILTER, SOLAR, OD 77.2 MM, HEIGHT 120 MM, THREAD M16 X 1.5, P553004 (DONALDSON)',
            ],
            [
                'kode_barang' => '100000066',
                'deskripsi' => 'GREASE, LITHIUM COMPLEX, -20 TO 160, 265-295, 280-300, >275, 165, 0-0, RED, 2',
            ],
            [
                'kode_barang' => '100000064',
                'deskripsi' => 'OIL, LUBRICANT, HEAVY DUTY DIESEL OIL, SAE VISCOSITY GRADE 15W-40, MEDITRAN SX SAE 15W - 40, 209LITER/DRUM',
            ],
            [
                'kode_barang' => '100000063',
                'deskripsi' => 'OIL, LUBRICANT, HEAVY DUTY DIESEL OIL, SAE VISCOSITY GRADE 40, MEDITRAN S SAE 40',
            ],
            [
                'kode_barang' => '100000141',
                'deskripsi' => 'CLUTCH, CANVAS, DIAFRAGMA, DUMP TRUCK',
            ],
            [
                'kode_barang' => '100000140',
                'deskripsi' => 'COUPLING, RUBBER, DIA. OUTSIDE : 99 MM, DIA. INSIDE: 43 MM, THICKNESS: 22 MM',
            ],
            [
                'kode_barang' => '100000134',
                'deskripsi' => 'PLATE, METAL, 4,5 X 4 INCHI X 8 INCHI, BORDES',
            ],
            [
                'kode_barang' => '100000131',
                'deskripsi' => 'BOLT, & NUT, M10 X 20 MM',
            ],
            [
                'kode_barang' => '100000103',
                'deskripsi' => 'HOSE, COUPLING, DUMP TRUCK',
            ],
            [
                'kode_barang' => '100000095',
                'deskripsi' => 'BELT, V-BELT, BELT HIGH 11 MM, LENGTH 1499 MM, B-59',
            ],
            [
                'kode_barang' => '100000094',
                'deskripsi' => 'HOSE, HYDRAULIC, 3/4 INCHI, LENGTH 210CM',
            ],
            [
                'kode_barang' => '100000093',
                'deskripsi' => 'WIRE, ROPE, SELING, 5MM',
            ],
            [
                'kode_barang' => '100000087',
                'deskripsi' => 'BEARING, 051 6306V, ID : 30 MM, OD : 72 MM, THICKNESS : 19 MM',
            ],
            [
                'kode_barang' => '100000086',
                'deskripsi' => 'BEARING, BALL, ANNULAR, DEEP GROVE, 6202 ZZ, ID: 15MM, OD : 35 MM, THICK 11MM, SINGLE ROW, FOR HIGH TEMPERATURE',
            ],
            [
                'kode_barang' => '100000073',
                'deskripsi' => 'FILTER, ELEMENT, 16000070005 / C - 1007, OIL FILTER',
            ],
            [
                'kode_barang' => '100000252',
                'deskripsi' => 'SPRING, BRAKE, SIZE BIG',
            ],
            [
                'kode_barang' => '100000068',
                'deskripsi' => 'FILTER, ELEMENT, CLX-46C 3315843 FS1212 (UNION), FUEL FILTER',
            ],
            [
                'kode_barang' => '100000067',
                'deskripsi' => 'FILTER, ELEMENT, FUEL FILTER, FS1212 (FLEETGUARD), DIA 93,22 MM, HEIGHT 201.17 MM',
            ],
            [
                'kode_barang' => '100000065',
                'deskripsi' => 'OIL, LUBRICANT, HYDRAULIC OIL, ISO VISCOSITY GRADE 70, TURALIK 52 ISO VG 70, 209LITER/DRUM',
            ],
            [
                'kode_barang' => '100000130',
                'deskripsi' => 'BOLT, & NUT, M8 X 20 MM',
            ],
        ];

        foreach ($dataBarang as $barang) {
            DB::table('barang')->updateOrInsert(
                [
                    'kode_barang' => $barang['kode_barang'],
                ],
                [
                    'deskripsi' => $barang['deskripsi'],
                    'updated_at' => now(),
                    'created_at' => now(),
                ]
            );
        }

        $this->command->info('Berhasil memproses ' . count($dataBarang) . ' data barang.');
    }
}
