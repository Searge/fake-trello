<?php

namespace Database\Seeders;

use App\Models\Tesk;
use Illuminate\Database\Seeder;

class TesksSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Tesk::factory()->times(200)->create();
    }
}
