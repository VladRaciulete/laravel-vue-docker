<?php

namespace App\Http\Controllers;

use App\Models\User;
use Inertia\Inertia;
use Inertia\Response;

class TeamController extends Controller
{
    public function index(): Response
    {
        return Inertia::render('Team/Index', [
            'users' => User::all(['id', 'name', 'email', 'created_at'])
        ]);
    }
}
