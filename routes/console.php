<?php

use App\Models\User;
use App\Models\Admin;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Artisan;

/*
|--------------------------------------------------------------------------
| Console Routes
|--------------------------------------------------------------------------
|
| This file is where you may define all of your Closure based console
| commands. Each Closure is bound to a command instance allowing a
| simple approach to interacting with each command's IO methods.
|
*/

Artisan::command('setup', function () {
    if (!File::exists(base_path('.env'))) {
        $this->line('');
        $this->line('Copying .env.example to .env');
        File::copy(base_path('.env.example'), base_path('.env'));
    }

    if (empty(env('APP_KEY'))) {
        $this->line('');
        $this->line('> php artisan key:generate --ansi');
        $this->call('key:generate', ['--ansi' => true]);
    }

    if (empty(env('JWT_SECRET'))) {
        $this->line('');
        $this->line('> php artisan jwt:secret --ansi');
        $this->call('jwt:secret', ['--ansi' => true]);
    }

    if (!empty(DB::select('SHOW TABLES'))) {
        $this->line('');
        $this->warn('Database rows detected.');

        if ($this->confirm('Do you want to drop all tables?')) {
            $this->line('');
            $this->line('> php artisan db:wipe');
            $this->call('db:wipe');
        } else {
            $this->comment('Aborted');
            return;
        }
    }

    $this->line('');
    $this->line('> php artisan migrate');
    $this->call('migrate');

    $this->line('');
    $this->info('Creating user account: user/123456');
    User::factory()->create(['username' => 'user', 'password' => bcrypt('123456')]);
    $this->info('Creating user account: user2/123456');
    User::factory()->create(['username' => 'user2', 'password' => bcrypt('123456')]);
    $this->info('Creating user account: user3/123456');
    User::factory()->create(['username' => 'user3', 'password' => bcrypt('123456')]);
    $this->info('Creating admin account: admin/123456');
    Admin::factory()->create(['username' => 'admin', 'password' => bcrypt('123456')]);
    $this->info('Creating admin account: admin2/123456');
    Admin::factory()->create(['username' => 'admin2', 'password' => bcrypt('123456')]);
    $this->info('Creating admin account: admin3/123456');
    Admin::factory()->create(['username' => 'admin3', 'password' => bcrypt('123456')]);

    $this->line('');
    $this->line('');
    $this->info('Setup finished.');
    $this->info('Please run "php artisan serve" to start demo application.');
})->purpose('Setup demo application');
