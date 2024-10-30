<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Todo;
use Illuminate\Support\Facades\Log;


class TodoController extends Controller
{
    public function index()
    {
        $currentTodo = Todo::whereNull('status')->get(); 
        $completedTodo = Todo::where('status', 1)->get(); 

        $todosRows = Todo::count();

        return view('todos.index', compact('currentTodo','completedTodo','todosRows' ));
    }

    public function store(Request $request)
    {

        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string|max:255',
        ]);


        Log::channel('stderr')->info('asdsadsa');
        Todo::create($request->all());

        return redirect()->route('todos.index');
    }

    public function update(Request $request, Todo $todo)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string|max:255',
        ]);

        $todo->update($request->all());

        return redirect()->route('todos.index');
    }

    public function destroy(Todo $todo)
    {
        $todo->delete();

        return redirect()->route('todos.index');
    }

    public function status(Request $request, Todo $todo)
    {
        $request->validate([
            'status' => 'required|integer|max:1',
        ]);

        $todo->update($request->all());

        return redirect()->route('todos.index');
    }
}
