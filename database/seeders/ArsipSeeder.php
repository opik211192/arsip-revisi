<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Arsip;
use Faker\Factory as Faker;
use Illuminate\Support\Str;
use Illuminate\Support\Carbon;
use Illuminate\Database\Seeder;

class ArsipSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = Faker::create('id_ID');

        // for ($i=0; $i < 100; $i++) { 
        //     Arsip::create([
        //         'jenis_arsip_id' => $faker->numberBetween(1,3),
        //         'lokasi_arsip' => $faker->word(2),
        //         'jenis_id' => $faker->numberBetween(1,3),
        //         'no_berkas' => $faker->numerify('#####'),
        //         'no_box' => $faker->numerify('#####'),
        //         'tahun' => $faker->numberBetween(1998,2022),
        //         //'pencipta_arsip' => $faker->firstName." ".$faker->lastName,
        //         'id_pencipta_arsip' => Users::all()->random()->id,
        //         'uraian_arsip' => $faker->word(5),
        //         'file_arsip' => $faker->words(2, true).".xls",
        //         'user_id' => $faker->numberBetween(1,3),
        //     ]);
        // }

         $jenisArsipIds = \DB::table('jenis_arsips')->pluck('id')->toArray();
        $jenisIds = \DB::table('jenis')->pluck('id')->toArray();
        $penciptaIds = \DB::table('struktural_details')->pluck('id')->toArray();
        $userIds = \DB::table('users')->pluck('id')->toArray();

        if (empty($jenisArsipIds) || empty($jenisIds) || empty($penciptaIds) || empty($userIds)) {
            $this->command->warn('⚠️ Tidak ada data referensi (jenis_arsips, jenis, struktural_details, users). Seeder Arsip tidak dijalankan.');
            return;
        }

        for ($i = 1; $i <= 1000000; $i++) {
            Arsip::create([
                'jenis_arsip_id'   => $faker->randomElement($jenisArsipIds),
                'lokasi_arsip'     => 'Rak ' . $faker->numberBetween(1, 10),
                'jenis_id'         => $faker->randomElement($jenisIds),
                'no_berkas'        => 'BRK-' . str_pad($i, 4, '0', STR_PAD_LEFT),
                'no_box'           => 'BOX-' . $faker->numberBetween(1, 20),
                'tahun'            => $faker->year(),
                'id_pencipta_arsip'=> $faker->randomElement($penciptaIds),
                'uraian_arsip'     => $faker->sentence(6),
                'user_id'          => $faker->randomElement($userIds),
                'created_at'       => Carbon::now()->subDays(rand(0, 365)),
                'updated_at'       => Carbon::now(),
            ]);
        }

        $this->command->info('✅ Seeder Arsip berhasil menambahkan 50 data contoh.');
    
    }
}
