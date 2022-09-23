<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class RoleTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Role::create([
            'name' => 'super admin',
            'guard_name' => 'web',
        ]);

        Role::create([
            'name' => 'admin',
            'guard_name' => 'web',
        ]);

        Role::create([
            'name' => 'writer',
            'guard_name' => 'web',
        ]);

    }
}
