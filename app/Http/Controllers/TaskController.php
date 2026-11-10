<?php

namespace App\Http\Controllers;

use App\Models\Task;
use Illuminate\View\View;

class TaskController extends Controller
{
    public function welcome(): View
    {
        $tasks = Task::forToday()->orderBy('id')->get();

        return view('welcome', compact('tasks'));
    }

    public function index(): View
    {
        $tasks = Task::query()->orderBy('task_date')->orderBy('id')->get();

        return view('tasks', compact('tasks'));
    }
}
