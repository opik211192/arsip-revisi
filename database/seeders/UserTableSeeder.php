<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class UserTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //user default
        User::create([
            'name' => 'opik',
            'email' => 'opiktaofik21@gmail.com',
            'username' => 'admin',
            'password' => bcrypt('rahasia123'),
            'struktural_id' => 5,
            'struktural_detail_id' => 19,
            // 'unit_id' => 1,
            //'struktur_id' => 1,
        ]);

        User::create([
            'name' => 'fina',
            'email' => 'fina@gmail.com',
            'username' => 'fina21',
            'password' => bcrypt('rahasia123'),
             'struktural_id' => 1 ,
            'struktural_detail_id' => 2,
            // 'unit_id' => 2,
            //'struktur_id' => 2,

        ]);

        User::create([
            'name' => 'alna',
            'email' => 'alna@gmail.com',
            'username' => 'alna21',
            'password' => bcrypt('rahasia123'),
             'struktural_id' => 3,
            'struktural_detail_id' => 10,
            // 'unit_id' => 3,
            //'struktur_id' => 2,
        ]);

        User::create([
            'name' => 'taofik',
            'email' => 'taofik@gmail.com',
            'username' => 'taofik21',
            'password' => bcrypt('rahasia123'),
             'struktural_id' => 5,
            'struktural_detail_id' => 19,
            // 'unit_id' => 5,
            //'struktur_id' => 3,
        ]);
    }
}
