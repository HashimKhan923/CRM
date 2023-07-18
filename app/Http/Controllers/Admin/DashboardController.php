<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Department;
use App\Models\Shift;

class DashboardController extends Controller
{
    public function index()
    {
        $UserCount = User::count();
        $DepartmentCount = Department::count();
        $ShiftCount = Shift::count();

        return response()->json(['UserCount'=>$UserCount,'DepartmentCount'=>$DepartmentCount,'ShiftCount'=>$ShiftCount]);
    }
}
