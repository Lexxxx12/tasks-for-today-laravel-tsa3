<?php

namespace Database\Seeders;

use App\Models\Task;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Relative dates keep a fresh installation demonstrable on any day.
        // Transactional replacement makes repeat seeding deterministic (8 tasks, 1 user).
        DB::transaction(function () {
            Task::query()->delete();
            User::query()->delete();
            $today = today();
            foreach ([
                ['Review today’s priorities', 'completed', 0],
                ['Finish the dashboard layout', 'in_progress', 0],
                ['Test mobile navigation', 'pending', 0],
                ['Prepare project documentation', 'pending', 0],
                ['Create the database schema', 'completed', -1],
                ['Draft the MVC structure', 'completed', -1],
                ['Publish repository to GitHub', 'pending', 1],
                ['Deploy the hosted application', 'pending', 1],
            ] as [$title, $status, $offset]) {
                Task::create(['title' => $title, 'status' => $status, 'task_date' => $today->copy()->addDays($offset)->toDateString()]);
            }
            User::create(['username' => 'sample', 'full_name' => 'Sample', 'email' => 'sample@example.com']);
        });
    }
}
