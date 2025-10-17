<?php

use Illuminate\Foundation\Inspiring;
use laravelpr3\vendor\laravel\framework\src\Illuminate\Support\Facades\Artisan;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');
