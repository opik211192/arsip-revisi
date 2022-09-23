<?php

namespace Database\Seeders;

use App\Models\Unit;
use Illuminate\Database\Seeder;

class UnitTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Unit::create([
            'name' => 'Satker Balai',
        ]);

        Unit::create([
            'name' => 'Satker PJPA',
        ]);

        Unit::create([
            'name' => 'Satker PJSA',
        ]);

        Unit::create([
            'name' => 'Satker OP',
        ]);    
        
        Unit::create([
            'name' => 'Satker Bendungan',
        ]);

         Unit::create([
            'name' => 'PPK Tata Laksana',
        ]);

        Unit::create([
            'name' => 'PPK Perencanaan & Program',
        ]);

         Unit::create([
            'name' => 'PPK PSDA',
        ]);

        Unit::create([
            'name' => 'PPK OP 1',
        ]);

        Unit::create([
            'name' => 'PPK OP 2',
        ]);    
        
        Unit::create([
            'name' => 'PPK OP 3',
        ]);

         Unit::create([
            'name' => 'PPK Air Baku 1',
        ]);

        Unit::create([
            'name' => 'PPK Air Baku',
        ]);
        
        Unit::create([
            'name' => 'PPK Tanah',
        ]);

        Unit::create([
            'name' => 'PPK Irigasi',
        ]);    
        
        Unit::create([
            'name' => 'PPK Sungai Pantai 1',
        ]);

         Unit::create([
            'name' => 'PPK Sungai Pantai 2',
        ]);
        
        Unit::create([
            'name' => 'PPk Bendungan',
        ]);
    }
}
