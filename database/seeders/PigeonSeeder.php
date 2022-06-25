<?php

namespace Database\Seeders;

use App\Models\Pigeon;
use Illuminate\Database\Seeder;

class PigeonSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        Pigeon::create([
            'name' => 'Antonio',
            'speed' => 70,
            'range' => 600,
            'cost' => 2,
            'downtime' => 2,
        ]);
        Pigeon::create([
            'name' => 'Bonito',
            'speed' => 80,
            'range' => 500,
            'cost' => 2,
            'downtime' => 3,
        ]);
        Pigeon::create([
            'name' => 'Carillo',
            'speed' => 65,
            'range' => 1000,
            'cost' => 2,
            'downtime' => 3,
        ]);
        Pigeon::create([
            'name' => 'Alejandro',
            'speed' => 70,
            'range' => 800,
            'cost' => 2,
            'downtime' => 2,
        ]);
    }
}
