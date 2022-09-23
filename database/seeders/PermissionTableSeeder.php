<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;

class PermissionTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Permission::create([
            'name' => 'create arsip',
            'guard_name' => 'web',
            ]);

        Permission::create([
            'name' => 'show users',
            'guard_name' => 'web',
        ]);

        Permission::create([
            'name' => 'delete arsip',
            'guard_name' => 'web',
        ]);
    }
}
