<?php

namespace Tests\Feature;

use App\Models\Task;
use App\Models\User;
use Database\Seeders\DatabaseSeeder;
use Illuminate\Database\QueryException;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Carbon;
use Tests\TestCase;

class TaskManagementTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        Carbon::setTestNow(Carbon::parse('2026-11-10 00:05:00', 'Asia/Manila'));
    }

    protected function tearDown(): void
    {
        Carbon::setTestNow();
        parent::tearDown();
    }

    public function test_seeder_matches_required_record_counts_and_is_repeatable(): void
    {
        $this->seed(DatabaseSeeder::class);
        $this->seed(DatabaseSeeder::class);
        $this->assertDatabaseCount('tasks', 8);
        $this->assertDatabaseCount('users', 1);
        $this->assertSame(3, Task::query()->distinct()->count('task_date'));
        $this->assertSame(4, Task::forToday()->count());
    }

    public function test_home_filters_by_manila_date_and_excludes_adjacent_dates(): void
    {
        $this->seed();
        $this->get('/')->assertOk()->assertSee('Test mobile navigation')->assertDontSee('Create the database schema')->assertDontSee('Deploy the hosted application');
        Carbon::setTestNow(Carbon::parse('2026-11-11 00:01:00', 'Asia/Manila'));
        $this->get('/')->assertOk()->assertSee('Deploy the hosted application')->assertDontSee('Test mobile navigation');
    }

    public function test_task_list_includes_all_records_in_date_order(): void
    {
        $this->seed();
        $this->get('/tasks')->assertOk()->assertSeeInOrder(['Create the database schema', 'Draft the MVC structure', 'Review today’s priorities', 'Finish the dashboard layout', 'Test mobile navigation', 'Prepare project documentation', 'Publish repository to GitHub', 'Deploy the hosted application']);
    }

    public function test_profile_and_about_show_the_expected_information(): void
    {
        $this->seed();
        $this->get('/profile')->assertOk()->assertSee('Sample')->assertSee('sample@example.com');
        $this->get('/about')->assertOk()->assertSee('Lexus Gabrielle')->assertSee('Medina')->assertSee('Doc Roman Villones');
    }

    public function test_empty_today_state_does_not_substitute_other_dates(): void
    {
        $this->seed();
        Carbon::setTestNow(Carbon::parse('2026-11-20', 'Asia/Manila'));
        $this->get('/')->assertOk()->assertSee('No tasks are scheduled')->assertDontSee('Test mobile navigation');
        $this->get('/tasks')->assertOk()->assertSee('Test mobile navigation');
    }

    public function test_task_titles_are_escaped_and_default_status_is_pending(): void
    {
        $task = Task::create(['title' => '<script>alert(1)</script>', 'task_date' => today()]);
        $this->assertSame('pending', $task->fresh()->status);
        $this->get('/')->assertOk()->assertSee('&lt;script&gt;alert(1)&lt;/script&gt;', false)->assertDontSee('<script>alert(1)</script>', false);
    }

    public function test_username_is_unique_at_database_level(): void
    {
        $this->seed();
        $this->expectException(QueryException::class);
        User::create(['username' => 'sample', 'full_name' => 'Duplicate', 'email' => 'duplicate@example.com']);
    }
}
