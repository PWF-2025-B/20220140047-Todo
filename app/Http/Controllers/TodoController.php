<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Todo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TodoController extends Controller
{
    public function index()
    {
        $todos = Todo::where('user_id', auth()->user()->id)
        ->orderBy('is_done', 'asc')
        ->orderBy('created_at', 'desc')
        ->with('category')
        ->get();
    
        // dd($todos);
    
        $todosCompleted = Todo::where('user_id', auth()->user()->id)
            ->where('is_done', true)
            ->count();
        
        return view('todo.index', compact('todos', 'todosCompleted'));
    
    }
    public function create()
    {
        $categories = Category::where('user_id', auth()->id())->get(); 
        return view('todo.create', compact('categories'));
    }
    public function edit(Todo $todo)
    {
        if (auth::id() == $todo->user_id) {
            // dd($todo);
            $categories = Category::all(); 
            return view ('todo.edit', compact('todo', 'categories'));
        } else {
            return redirect()->route('todo.index')->with('error', 'You are not authorized to edit this todo.');
        }
    }

    public function update(Request $request, Todo $todo)
    {
    $request->validate([
        'title' => 'required|max:255',
        'category_id' => 'nullable|exists:categories,id'

    ]);

    // Practical
    // $todo->title = $request->title;
    // $todo->save();

    // Eloquent Way - Readable
    $todo->update([
        'title' => ucfirst($request->title),
        'category_id' => $request->category_id,
    ]);

    return redirect()->route('todo.index')->with('success', 'Todo updated successfully!');
    }

    public function store(Request $request, Todo $todo)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'category_id' => 'nullable|exists:categories,id'
        ]);

        $todo = Todo::create([
            'title' => ucfirst($request->title),
            'category_id' => $request->category_id,
            'user_id' => Auth::id(),
        ]);
        return redirect()->route('todo.index')->with('success', 'Todo created successfully.');
    }

    public function complete(Todo $todo){
        if (Auth::id() == $todo->user_id) {
            $todo->update(['is_done' => true]);
            return redirect()->route('todo.index')->with('success', 'Todo completed successfully.');
        } else {
            return redirect()->route('todo.index')->with('error', 'You are not authorized to complete this todo.');
        }
    }

    public function uncomplete(Todo $todo){
        if (Auth::id() == $todo->user_id) {
            $todo->update(['is_done' => false]);
            return redirect()->route('todo.index')->with('success', 'Todo uncompleted successfully.');
        } else {
            return redirect()->route('todo.index')->with('error', 'You are not authorized to uncomplete this todo.');
        }
    }
    public function destroy(Todo $todo)
    {
        if (auth()->user()->id == $todo->user_id) {
            $todo->delete();
            return redirect()->route('todo.index')->with('success', 'Todo deleted successfully!');
        } else {
            return redirect()->route('todo.index')->with('danger', 'You are not authorized to delete this todo!');
        }
    }

    public function destroyCompleted()
    {
        // get all todos for current user where is_complete is true
        $todosCompleted = Todo::where('user_id', auth()->user()->id)
            ->where('is_done', true)
            ->get();

        foreach ($todosCompleted as $todo) {
            $todo->delete();
        }

        // dd($todosCompleted);
        return redirect()->route('todo.index')->with('success', 'All completed todos deleted successfully!');
    }
}
