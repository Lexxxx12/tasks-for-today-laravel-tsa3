<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    protected $fillable = ['title', 'status', 'task_date'];

    protected function casts(): array
    {
        return ['task_date' => 'date'];
    }

    public function scopeForToday(Builder $query): Builder
    {
        return $query->whereDate('task_date', today()->toDateString());
    }

    public function statusLabel(): string
    {
        return match ($this->status) {
            'completed' => 'Completed', 'in_progress' => 'In progress', default => 'Pending'
        };
    }
}
