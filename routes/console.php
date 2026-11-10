<?php

use App\Models\Task;
use Illuminate\Support\Facades\Artisan;

Artisan::command('app:seed-if-empty', function () {
    if (! Task::query()->exists()) {
        $this->call('db:seed', ['--force' => true]);
    }
})->purpose('Initialize the demonstration records on a new database');
