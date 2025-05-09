<?php

namespace App\Http\Controllers;

use App\Models\Committee;
use App\Models\Member_committee;
use App\Notifications\AppNotification;
use Illuminate\Http\Request;

class commiteeJionController extends Controller
{


    public function memberRequests(Request $request)
    {
        $user = $request->user();


        $request->validate(['committee_id' => 'required|exists:committees,id']);
        $user->committees()->attach($request->post('committee_id'), ['status' => 'inactive']);


        $user->notify(new AppNotification('Request to join', "Hello $user->name, your request to join the committee has been submitted.
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
    public function inactiveMembers(Request $request)
    {
        $admin = $request->user();

        // Get all committee IDs that belong to this admin
        $adminCommitteeIds = $admin->committees()->pluck('committees.id');

        // Get all inactive members that belong to these committees
        $members = Member_committee::with('user')
            ->whereIn('committee_id', $adminCommitteeIds)
            ->where('status', 'inactive')
            ->get();

        $inactiveMembers = [];

        foreach ($members as $member) {
            if ($member->user) {
                $inactiveMembers[] = [
                    'id' => $member->user->id,
                    'name' => $member->user->name,
                    'email' => $member->user->email,
                    'committee_id' => $member->committee_id,
                    'status' => $member->status,
                ];
            }
        }

        return response()->json([
            'members' => $inactiveMembers,
        ], 200);
    }


    // this function is used by admin committee
    public function approveMemberRequest(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:member_committees,user_id',
            'committee_id' => 'required|exists:committees,id'
        ]);

        $member = Member_committee::where('user_id', $request->post('user_id'))
            ->where('committee_id', $request->post('committee_id'))
            ->where('status', 'inactive')
            ->first();

        if (!$member) {
            return response()->json([
                'message' => 'Member request not found'
            ], 404);
        }

        $member->update(['status' => 'active']);

        $user = $member->member;
        if ($user) {
            $user->notify(new AppNotification('Request approved', "Hello $user->name, your request to join the committee has been approved.
                                                Welcome aboard! We're excited to have you as part of our team.
                                                If you have any questions or need assistance, feel free to reach out.
                                                Looking forward to working together!"));
        }

        return response()->json([
            'message' => 'request approved successfully'
        ], 200);
    }
}
