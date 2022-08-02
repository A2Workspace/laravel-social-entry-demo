<?php

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
        File::copy(base_path('.env.example'), base_path('.env'));

        $this->info('Copying .env.example to .env');
        $this->call('key:generate', ['--ansi' => true]);
        $this->call('jwt:secret', ['--ansi' => true]);
    }

    if (!empty(DB::select('SHOW TABLES'))) {
        if ($this->confirm('Database is not empty. Do you want to drop all tables?')) {
            $this->call('db:wipe');
        } else {
            $this->comment('Aborted');
            return;
        }
    }

    $this->call('migrate', ['--seed' => true]);
})->purpose('Setup the demo application');
