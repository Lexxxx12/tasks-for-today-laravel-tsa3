<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\View\View;

class ProfileController extends Controller
{
    public function show(): View
    {
        return view('profile', ['user' => User::query()->firstOrFail()]);
    }
}
