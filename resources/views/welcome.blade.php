@extends('layouts.app')
@section('title', 'Today')
@section('content')
<div class="eyebrow">LET’S MAKE ROOM FOR WHAT MATTERS</div><div class="page-heading"><div><h1>A fresh day.<br><em>A clear focus.</em></h1><p>Welcome back. Here’s what’s on your list for today.</p></div><div class="date-card"><span>{{ today()->format('F Y') }}</span><strong>{{ today()->format('d') }}</strong><span>{{ today()->format('l') }}</span></div></div>
@php($completed = $tasks->where('status', 'completed')->count())
<div class="stats"><article><span>Today’s tasks</span><strong>{{ $tasks->count() }}<small>scheduled for today</small></strong></article><article><span>Completed</span><strong>{{ $completed }}<small>one step at a time</small></strong></article><article><span>Still to do</span><strong>{{ $tasks->count() - $completed }}<small>you’ve got this</small></strong></article><article class="progress-card"><span>Daily progress</span><strong>{{ $tasks->count() ? round($completed / $tasks->count() * 100) : 0 }}<sup>%</sup></strong><progress aria-label="Today's task completion" value="{{ $completed }}" max="{{ max(1, $tasks->count()) }}"></progress></article></div>
<section class="panel"><div class="panel-heading"><div><h2>Today’s focus <span class="count">{{ $tasks->count() }}</span></h2><p>Only the tasks scheduled for {{ today()->format('F j') }}.</p></div><a class="button secondary" href="{{ route('tasks') }}">All tasks <span aria-hidden="true">↗</span></a></div>@include('partials.task-table')</section>
<div class="note"><span aria-hidden="true">✦</span><p>Progress starts with one task.<small>Keep your focus here, and take the day one step at a time.</small></p></div>
@endsection
