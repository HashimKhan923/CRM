<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Time;

class AttendenceController extends Controller
{
    public function index()
    {
        $Attendence = Time::with('user')->whereDate('created_at',Carbon::today())->get();

        return response()->json(['Attendence'=>$Attendence]);
    }

    public function search()
    {
        $data = Time::with('user')->where('created_at','>=',$request->from_date)->where('created_at','<=',$request->to_date)->get();
    }
}
