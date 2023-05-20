<?php

use Illuminate\Foundation\Inspiring;
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

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');


Artisan::command('cache:all', function () {
    $this->call('cache:clear');
    $this->call('config:cache');
    $this->call('route:cache');
    $this->call('view:cache');
})->purpose('Clear all cache');

Artisan::command('cache:clear-all', function () {
    $this->call('cache:clear');
    $this->call('config:clear');
    $this->call('route:clear');
    $this->call('view:clear');
})->purpose('Clear all cache');

Artisan::command('cache:gen', function () {
    $this->call('cache:all');
    $this->call('cache:clear-all');
})->purpose('Total cache generation');


