<?php

namespace Database\Seeders;

use App\Models\Struktural;
use Illuminate\Database\Seeder;

class StrukturalSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Struktural::create([
            'name' => 'Balai Besar Wilayah Sungai Citanduy',
        ]);

        Struktural::create([
            'name' => 'Bagian Umum dan Tata Usaha',
        ]);

         Struktural::create([
            'name' => 'Bidang Keterpaduan Pembangunan Infrastruktur Sumber Daya Air',
        ]);

         Struktural::create([
            'name' => 'Bidang Pelaksanaan',
        ]);

        Struktural::create([
            'name' => 'Bidang OP',
        ]);

        Struktural::create([
            'name' => 'Satker Balai Besar Wilayah Sungai Citanduy',
        ]);

        Struktural::create([
            'name' => 'Satker OP SDA Citanduy',
        ]);

        Struktural::create([
            'name' => 'SNVT PJSA Citanduy',
        ]);

        Struktural::create([
            'name' => 'SNVT PJPA Citanduy',
        ]);

        Struktural::create([
            'name' => 'SNVT Pembangunan Bendungan',
        ]);
    }
}
