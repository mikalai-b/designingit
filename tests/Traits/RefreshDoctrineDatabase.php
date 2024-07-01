<?php

namespace Tests\Traits;

use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;

trait RefreshDoctrineDatabase
{
    function refreshDoctrineDatabase()
    {
        Artisan::call('migrate:reset', ['--force' => true]);
        DB::unprepared(file_get_contents(base_path('tests/Stubs/db-structure.sql')));
    }
}