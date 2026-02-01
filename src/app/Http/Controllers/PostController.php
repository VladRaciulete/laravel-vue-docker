<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;

class PostController extends Controller
{
    public function index()
    {
        return Post::all();
    }

    public function store(Request $request)
    {
        // 1. Validation (The Laravel way)
        $validated = $request->validate([
            'title' => 'required|max:255',
            'content' => 'required',
        ]);

        // 2. Creation (Mass Assignment)
        // This works because we added 'title' and 'content' to $fillable
        $post = Post::create($validated);

        // 3. Response
        // Returning the object automatically gives a 201 Created status and JSON
        return $post;
    }
}
