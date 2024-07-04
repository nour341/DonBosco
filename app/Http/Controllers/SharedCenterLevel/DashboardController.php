<?php

namespace App\Http\Controllers\SharedCenterLevel;

use App\Http\Controllers\Controller;
use App\Models\Center;
use App\Models\Project;
use App\Models\User;
use App\Traits\GeneralTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    use  GeneralTrait;
    public  function get_stats()
    {
        // Get the currently authenticated user
        $user = Auth::user();

        // حالة role_number
        $roleNumber = $user->role_number;
        $projects = [];
        if ($roleNumber == 1) {
            // إذا كان المستخدم local
            $projects = Project::where('local_coordinator_id',$user->id)->get();
        } elseif ($roleNumber == 2) {
            // إذا كان المستخدم financial
            $projects = Project::where('financial_management_id',$user->id)->get();
        } elseif ($roleNumber == 3) {
            // إذا كان المستخدم employ
            $projects = $user->projects()->get();
            $projects->each(function ($project) {
                unset($project->pivot); // Remove invoice_id to clean up the response

            });

        }

        $center=Center::find($user->center_id);
        if(!$center){
            return $this->returnError('Failed to get center. the center does not exist',404);
        }

        $center = Center::with(['users' => function ($query) {
            $query->where('role_number', 3);
        }])->find($user->center_id);

        $employees = $center->users;

        $center = Center::with('users' )->find($user->center_id);

        $users = $center->users;

        $stats = [
            'projects_number' => count($projects),
            'employees_number' => count($employees),
            'users_number' => count($users),
        ];
        return $this->returnData('stats',$stats,'Get stats successfully');

    }}
