<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

Schedule::command('scrape:metal-archives --offset=0')
    ->dailyAt('07:00')
    ->timezone('America/Mazatlan');

Schedule::command('scrape:metal-archives --offset=20')
    ->dailyAt('14:00')
    ->timezone('America/Mazatlan');

Schedule::command('scrape:metal-archives --offset=40')
    ->dailyAt('22:00')
    ->timezone('America/Mazatlan');