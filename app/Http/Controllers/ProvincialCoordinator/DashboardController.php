<?php

namespace App\Http\Controllers\ProvincialCoordinator;

use App\Http\Controllers\Controller;
use App\Models\Center;
use App\Models\Country;
use App\Models\Project;
use App\Models\User;
use App\Traits\GeneralTrait;
use Illuminate\Http\Request;

class DashboardController extends Controller
{ use  GeneralTrait;
    public  function get_stats()
    {
        $centers = Center::get();
        $user = User::get();
        $suppliers = User::where('role_number',4)->get();
        $projects = Project::get();
        $stats = [
            'centers_number' => count($centers),
            'suppliers_number' => count($suppliers),
            'projects_number' => count($projects),
            'users_number' => count($user),

        ];
        return $this->returnData('stats',$stats,'Get stats successfully');

    }
}
