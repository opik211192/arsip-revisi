<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;


class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // \App\Models\User::factory(10)->create();
        //$this->call(UnitTableSeeder::class);
        $this->call(RoleTableSeeder::class);
        $this->call(PermissionTableSeeder::class);
        
        $this->call(JenisTableSeeder::class);
        $this->call(JenisArsipTableSeeder::class);
        
        $this->call(StrukturalSeeder::class);
        $this->call(StrukturalDetailSeeder::class);
        $this->call(UserTableSeeder::class);

        $user = User::find(1);
        $user->assignRole('super admin');

        $user = User::find(2);
        $user->assignRole('admin');

        $user = User::find(3);
        $user->assignRole('writer');
        
        
    }
}
