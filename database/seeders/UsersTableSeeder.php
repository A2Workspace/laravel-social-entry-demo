<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Database\Seeder;

class UsersTableSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        foreach (range(0, 10) as $i) {
            User::factory()->create([
                'username' => 'user' . Str::padLeft($i, 3, '0'),
            ]);
        }
    }
}
