<?php

namespace App\Http\Controllers;

use App\Models\Admin;
use App\Models\Committee;
use App\Models\Member_committee;
use App\Notifications\AppNotification;
use Illuminate\Http\Request;

class AdminCommitteController extends Controller
{
    // super admin can add and remove committee from admin
    // asd
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

    public function memberRequests(Request $request){
        $user = $request->user();

        $user->committees()->attach($request->post('committee_id'), ['status' => 'inactive']);
        $request->validate(['committee_id' => 'required|exists:committees,id']);

        $user->notfiy(new AppNotification('Request to join', "Hello $user->name, your request to join the committee has been submitted.
                                            We appreciate your interest and will review your application shortly.
                                            If you have any questions or need further assistance, feel free to reach out.
                                            Thank you for your patience!"));
        $committee = Committee::find($request->post('committee_id'));
        $committee->admins()->each(function ($admin) use ($user) {
            $admin->notify(new AppNotification('New member request', "Hello $admin->name, a new member has requested to join the committee.
                                            Please review the application and take the necessary actions.please see the dashboard"));
        });


        return response()->json([
            'message' => 'request sent successfully'
        ], 200);
    }


    // admin
    public function inactiveMembers(Request $request) {

        $admin = $request->user();

        // Get all committee IDs that belong to this admin
        $adminCommitteeIds = $admin->committies()->pluck('id');

        // Get all inactive members that belong to these committees
        $members = Member_committee::wiht('member')->whereIn('committee_id', $adminCommitteeIds)
            ->where('status', 'inactive')
            ->get();

        return response()->json([
            'members' => $members
        ], 200);
    }


// this function is used by admin committee
    public function approveMemberRequest(Request $request) {
        $request->validate([
            'user_id' => 'required|exists:member_committee,id',
            'committee_id' => 'required|exists:committees,id'
        ]);

        $member = Member_committee::where('user_id', $request->post('user_id'))
            ->where('committee_id', $request->post('committee_id'))
            ->first();
        $member->update(['status' => 'active']);

        $user = $member->user;
        $user->notify(new AppNotification('Request approved', "Hello $user->name, your request to join the committee has been approved.
                                            Welcome aboard! We're excited to have you as part of our team.
                                            If you have any questions or need assistance, feel free to reach out.
                                            Looking forward to working together!"));

        return response()->json([
            'message' => 'request approved successfully'
        ], 200);
    }
}
