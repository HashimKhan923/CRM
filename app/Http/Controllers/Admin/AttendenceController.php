<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Time;

class AttendenceController extends Controller
{
    public function index()
    {
        $Attendence = Time::with('user')->whereDate('created_at',Carbon::today());

        return response()->json(['Attendence'=>$Attendence]);
    }
}
