<?php

namespace Database\Seeders;

use App\Models\Desk;
use Illuminate\Database\Seeder;

class DesksSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Desk::factory()->times(5)->create();
    }
}
