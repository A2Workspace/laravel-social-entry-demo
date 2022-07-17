<?php

namespace Database\Seeders;

use App\Models\Admin;
use Illuminate\Support\Str;
use Illuminate\Database\Seeder;

class AdminsTableSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        foreach (range(0, 10) as $i) {
            Admin::factory()->create([
                'username' => 'admin' . Str::padLeft($i, 3, '0'),
            ]);
        }
    }
}
