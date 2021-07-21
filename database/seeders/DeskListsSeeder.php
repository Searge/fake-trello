<?php

namespace Database\Seeders;

use App\Models\DeskList;
use Illuminate\Database\Seeder;

class DeskListsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DeskList::factory()->times(20)->create();
    }
}
