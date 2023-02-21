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
            'username' => 'superadmin',
            'password' => bcrypt('rahasia123'),
            'struktural_id' => 6,
            'struktural_detail_id' => 19,
            // 'unit_id' => 1,
            //'struktur_id' => 1,
        ]);

        User::create([
            'name' => 'ADMIN',
            'email' => 'admin@gmail.com',
            'username' => 'admin',
            'password' => bcrypt('rahasia123'),
             'struktural_id' => 2 ,
            'struktural_detail_id' => 2,
            // 'unit_id' => 2,
            //'struktur_id' => 2,

        ]);

        User::create([
            'name' => 'User ke 1',
            'email' => 'user1@gmail.com',
            'username' => 'user1',
            'password' => bcrypt('rahasia123'),
             'struktural_id' => 7,
            'struktural_detail_id' => 24,
            // 'unit_id' => 3,
            //'struktur_id' => 2,
        ]);

        User::create([
            'name' => 'User ke 2',
            'email' => 'user2@gmail.com',
            'username' => 'user2',
            'password' => bcrypt('rahasia123'),
             'struktural_id' => 7,
            'struktural_detail_id' => 25,
            // 'unit_id' => 5,
            //'struktur_id' => 3,
        ]);

         User::create([
            'name' => 'User ke 3',
            'email' => 'user3@gmail.com',
            'username' => 'user3',
            'password' => bcrypt('rahasia123'),
             'struktural_id' => 7,
            'struktural_detail_id' => 26,
            // 'unit_id' => 5,
            //'struktur_id' => 3,
        ]);
    }
}
