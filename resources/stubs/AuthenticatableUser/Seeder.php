<?php

namespace Database\Seeders;

use App\Models\Dummy;
use Illuminate\Support\Str;
use Illuminate\Database\Seeder;

class DummiesTableSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        foreach (range(0, 10) as $i) {
            Dummy::factory()->create([
                'username' => 'dummy' . Str::padLeft($i, 3, '0'),
            ]);
        }
    }
}
