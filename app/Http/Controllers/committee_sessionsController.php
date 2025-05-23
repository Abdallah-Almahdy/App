<?php

namespace App\Http\Controllers;

use App\Http\Resources\SessionsCollection;
use App\Http\Resources\SessionsResource;
use App\Models\Committee;
use App\Models\committee_sessions;
use App\Models\Session;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;

class committee_sessionsController extends Controller
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
            return new SessionsCollection(committee_sessions::all());
        }
        $committee = $user->committees()->first();
        $sessions = $committee->sessions()->get();

        if (!Gate::allows('show-sessions', $committee)) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        return new SessionsCollection($sessions);
    }

    public function store(Request $request, $committee_id)
    {
        if (!Gate::allows('manage-sessions', Committee::find($committee_id))) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        committee_sessions::validate($request);

        $session = committee_sessions::create([
            'title' => $request->post('title'),
            'description' => $request->post('description'),
            'date' => $request->post('date'),
            'user_id' => auth::user()->id,
            'committee_id' => $committee_id,
            'link' => $request->post('link'),
        ]);

        return new SessionsResource($session);
    }

    public function show($id)
    {
        $user = auth()->user();
        $userCommittee = $user->committees->first();

        if (!Gate::allows('show-sessions', $userCommittee)) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $session = committee_sessions::find($id);
        if (!$session) {
            return request()->json(['message' => 'Session not found'], 404);
        }
        return new SessionsResource($session);
    }

    public function update(Request $request, $committee_id, $id)
    {
        if (!Gate::allows('manage-sessions', Committee::find($committee_id))) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }
        committee_sessions::validate($request);
        $session = committee_sessions::find($id);
        if (!$session) {
            return response()->json(['message' => 'Session not found'], 404);
        }
        $session->update([
            'title' => $request->title,
            'description' => $request->description,
            'date' => $request->date,
            'link' => $request->link,
        ]);
        return  new SessionsResource($session);
    }

    public function destroy($committee_id,$id)
    {
        if (!Gate::allows('manage-sessions', Committee::find($committee_id))) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }
        $session = committee_sessions::find($id);
        if (!$session) {
            return response()->json(['message' => 'Session not found'], 404);
        }
        $session->delete();
        return response()->json(['message' => 'Session deleted successfully'], 200);
    }
}
