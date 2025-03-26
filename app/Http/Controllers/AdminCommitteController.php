<?php

namespace App\Http\Controllers;

use App\Models\Admin;
use Illuminate\Http\Request;

class AdminCommitteController extends Controller
{
    // super admin can add and remove committee from admin

    public function setAdmin(Request $request)
    {
        $request->validate([
            'admin_id' => 'required|exists:admins,id',
            'committee_id' => 'required|exists:committees,id'
        ]);

        $admin = Admin::find($request->post('admin_id'));
        $admin->committees()->attach($request->post('committee_id'));

        return response()->json([
            'message' => 'permission added successfully'
        ], 200);
    }

    public function removeAdmin(Request $request)
    {
        $request->validate([
            'admin_id' => 'required|exists:admins,id',
            'committee_id' => 'required|exists:committees,id'
        ]);

        $admin = Admin::find($request->post('admin_id'));
        $admin->committees()->detach($request->post('committee_id'));

        return response()->json([
            'message' => 'permission removed successfully'
        ], 200);
    }
}
