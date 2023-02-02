<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Arsip;
use Faker\Factory as Faker;
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
        // $faker = Faker::create('id_ID');

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
    }
}
