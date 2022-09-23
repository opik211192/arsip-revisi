<?php

namespace Database\Seeders;

use App\Models\JenisArsip;
use Illuminate\Database\Seeder;

class JenisArsipTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        JenisArsip::create([
            'name' => 'Aktif',
        ]);

        JenisArsip::create([
            'name' => 'Inaktif',
        ]);

        JenisArsip::create([
            'name' => 'Vital',
        ]);

        JenisArsip::create([
            'name' => 'Statis',
        ]);

        JenisArsip::create([
            'name' => 'Terjaga',
        ]);
    }
}
