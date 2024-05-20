<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Time;
use Carbon\Carbon;

class AttendenceController extends Controller
{
    public function index(Request $request)
    {
        $attendences = Time::with('user')->whereDate('created_at',Carbon::today())->get();

        if ($request->wantsJson()) {
            return response()->json(['attendences'=>$attendences]);  
        }

        return view('admin.attendences.index', compact('attendences')); 
    }

    public function search(Request $request)
    {
        $from_date = Carbon::parse($request->from_date);
        $to_date = Carbon::parse($request->to_date);
        $attendences = Time::with('user')->where('created_at','>=',$from_date)->where('created_at','<=',$to_date)->get();

        if ($request->wantsJson()) {
            return response()->json(['attendences'=>$attendences]);  
        }

        return view('admin.attendences.index', compact('attendences')); 
    }
}
