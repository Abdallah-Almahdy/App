<?php

namespace App\Http\Controllers;

use App\Http\Resources\TaskCollection;
use App\Http\Resources\TaskResource;
use App\Models\Committee;
use App\Models\Task;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class TaskController extends Controller
{


    public function index($committee_id)
    {
        $committee = Committee::find($committee_id);

        if (auth()->user()->is_super_admin) {
            return new TaskCollection(Task::all());
        }

        if (!$committee || !Gate::allows('show-tasks', $committee)) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }



        return new TaskCollection(Task::where('committee_id', $committee_id)->get());
    }

    public function store(Request $request, $commitee_id)
    {

        if (!Gate::allows('manage-tasks', Committee::find($commitee_id))) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        Task::validate($request);
        $task = Task::create([
            'title' => $request->post('title'),
            'description' => $request->post('description'),
            'link' => $request->post('link'),
            'user_id' => 1,
            'committee_id' => $commitee_id
        ]);


        return new TaskResource($task);
    }

    public function show($committee_id, $id)
    {
        if (!Gate::allows('show-tasks', Committee::find($committee_id))) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }
        
        $task = Task::find($id);
        if (!$task) {
            return response()->json(['message' => 'Task not found'], 404);
        }

        return new TaskResource($task);
    }

    public function update(Request $request, $commitee_id, $id)
    {
        $task = Task::find($id);
        if (!$task) {
            return response()->json(['message' => 'Task not found'], 404);
        }
        if (!Gate::allows('manage-tasks', Committee::find($task->committee->id))) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        Task::validate($request);

        $task->update([
            'title' => $request->post('title'),
            'description' => $request->post('description'),
            'link' => $request->post('link'),
        ]);
        return  new TaskResource($task);
    }

    public function destroy($committee_id, $id)
    {

        $task = Task::find($id);

        if (!Gate::allows('manage-tasks', Committee::find($committee_id))) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        if (!$task) {
            return response()->json(['message' => 'Task not found'], 404);
        }

        $task->delete();
        return response()->json(['message' => 'Task deleted successfully'], 200);
    }
}
