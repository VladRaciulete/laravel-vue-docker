<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
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

    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|lowercase|email|max:255|unique:'.User::class,
        ]);

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make('tempPassword'), //TODO
            'company_id' => $request->user()->company_id,
        ]);

        return redirect()->route('team.index');
    }
}
