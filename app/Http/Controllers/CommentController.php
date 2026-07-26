<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class CommentController extends Controller
{
    
    public function index(Request $request): View
    {
        $search = trim((string) $request->query('search', ''));

        $comments = DB::table('comments')
            ->when($search !== '', function ($query) use ($search) {
                $query->where(function ($subQuery) use ($search) {
                    $subQuery
                        ->where('name', 'like', "%{$search}%")
                        ->orWhere('comment', 'like', "%{$search}%");
                });
            })
            ->orderByDesc('created_at')
            ->get();

        return view('comments.index', [
            'comments' => $comments,
            'search' => $search,
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:100'],
            'comment' => ['required', 'string', 'max:1000'],
        ], [
            'name.required' => 'Please enter your name.',
            'name.max' => 'The name must not exceed 100 characters.',
            'comment.required' => 'Please enter a comment.',
            'comment.max' => 'The comment must not exceed 1000 characters.',
        ]);

        DB::table('comments')->insert([
            'name' => $validated['name'],
            'comment' => $validated['comment'],
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return redirect()
            ->route('comments.index')
            ->with('success', 'Your comment has been added successfully.');
    }
}

