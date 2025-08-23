<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

Schedule::command('mbz:label-latest-albums "Century Media" --take=20 --covers')
    ->dailyAt('07:00')
    ->timezone('America/Mazatlan');

Schedule::command('mbz:label-latest-albums "Nuclear Blast" --take=20 --covers')
    ->dailyAt('14:00')
    ->timezone('America/Mazatlan');

Schedule::command('mbz:label-latest-albums "Season of Mist" --take=20 --covers')
    ->dailyAt('20:00')
    ->timezone('America/Mazatlan');