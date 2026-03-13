<?php

namespace App\Http\Controllers;

use App\Models\Todo;
use Illuminate\Http\Request;

class TodoController extends Controller
{
    public function index()
    {
        $todos = Todo::orderByDesc('created_at')->get();

        return view('todos.index', compact('todos'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'priority' => 'required|in:high,medium,low',
        ]);

        Todo::create($validated);

        return redirect('/');
    }

    public function update(int $id)
    {
        $todo = Todo::findOrFail($id);
        $todo->update(['is_completed' => !$todo->is_completed]);

        return redirect('/');
    }

    public function destroy(int $id)
    {
        Todo::findOrFail($id)->delete();

        return redirect('/');
    }
}
