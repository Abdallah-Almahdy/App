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
    public function __construct()
    {
        $this->middleware('hasAccess');
    }

    public function index(Request $request)
    {
        $user = $request->user();

        // Handle super admin case first
        if ($user->is_super_admin) {
            return new TaskCollection(Task::all());
        }

        $committee = $user->committees()->first();

        if (!$committee || !Gate::allows('show-tasks', $committee)) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        return new TaskCollection(Task::where('committee_id', $committee->id)->get());
    }


    public function store(Request $request, $committee_id)
    {

        if (!Gate::allows('manage-tasks', Committee::find($committee_id)) ) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }


        Task::validate($request);

        $task = Task::create([
            'title' => $request->post('title'),
            'description' => $request->post('description'),
            'link' => $request->post('link'),
            'user_id' => $request->user()->id,
            'committee_id' => $committee_id
        ]);

        return new TaskResource($task);
    }

    public function show($id)
    {
        $user = auth()->user();
        if($user->is_super_admin) {
            return new TaskResource(Task::find($id));
        }

        $committee = $user->committees->first();

        if (!Gate::allows('show-tasks', $committee) )
        {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $task = Task::find($id);
        if (!$task) {
            return response()->json(['message' => 'Task not found'], 404);
        }

        return new TaskResource($task);
    }

    public function update(Request $request, $committee_id,$id)
    {

        if (!Gate::allows('manage-tasks', Committee::find($committee_id)) ) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }
        $task = Task::find($id);
        if (!$task) {
            return response()->json(['message' => 'Task not found'], 404);
        }
        Task::validate($request);

        $task->update([
            'title' => $request->title,
            'description' => $request->description,
            'link' => $request->link,
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
