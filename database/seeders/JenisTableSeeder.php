<?php

namespace Database\Seeders;

use App\Models\Jenis;
use Illuminate\Database\Seeder;

class JenisTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Jenis::create([
            'name' => 'Hk',
            'description' => 'Hukum',
        ]);

        Jenis::create([
            'name' => 'HL',
            'description' => 'Hubungan Luar Negri',

        ]);

        Jenis::create([
            'name' => 'HM',
            'description' => 'Hubungan Masyarakat',

        ]);

        Jenis::create([
            'name' => 'KP',
            'description' => 'Kepegawaian',

        ]);

        Jenis::create([
            'name' => 'KU',
            'description' => 'Keuangan',

        ]);

        Jenis::create([
            'name' => 'OR',
            'description' => 'Organisasi dan Tata Lakasana',

        ]);

        Jenis::create([
            'name' => 'IP',
            'description' => 'lorem',

        ]);

        Jenis::create([
            'name' => 'PA',
            'description' => 'Pengelolaan Data',

        ]);

        Jenis::create([
            'name' => 'PB',
            'description' => 'Pengadaan Barang/Jasa',

        ]);

        Jenis::create([
            'name' => 'PM',
            'description' => 'Penanaman Modal',

        ]);

        Jenis::create([
            'name' => 'PR',
            'description' => 'Perencanaan',

        ]);

        Jenis::create([
            'name' => 'PS',
            'description' => 'Pengelolaan Aset Barang Milik Negara (BMN)',

        ]);

        Jenis::create([
            'name' => 'PW',
            'description' => 'Pengawasan',

        ]);

        Jenis::create([
            'name' => 'UM',
            'description' => 'Umum',

        ]);

    }
}
