<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Time;
use App\Models\User;
use App\Models\Shift;
use Carbon\Carbon;


class AttendenceController extends Controller
{
    public function index(Request $request, $id)
    {
        $attendences = Time::where('user_id',$id)->whereDate('created_at', Carbon::today('Asia/Karachi'))->get();

        if ($request->wantsJson()) {
            return response()->json(['attendences'=>$attendences]);  
        }

        return view('user.attendences.index', compact('attendences')); 
    }

    public function search(Request $request)
    {
        $from_date = Carbon::parse($request->from_date)->startOfDay();
        $to_date = Carbon::parse($request->to_date)->endOfDay();

        $attendences = Time::with('user')->where('created_at','>=',$from_date)->where('created_at','<=',$to_date)->where('user_id',auth()->user()->id)->get();

        if ($request->wantsJson()) {
            return response()->json(['attendences'=>$attendences]);  
        }

        return view('user.attendences.index', compact('attendences')); 
    }

    public function time_in(Request $request, $id)
    {
        $check_user = User::where('id', $id)->first();
        $check_shift = Shift::where('id', $check_user->shift_id)->first();
    
        $ShiftTimeIn = Carbon::parse($check_shift->time_from);
        $ShiftTimeOut = Carbon::parse($check_shift->time_to);
        $ShiftTimeIn = $ShiftTimeIn->toTimeString();
        $ShiftTimeOut = $ShiftTimeOut->toTimeString();
        $ShiftTimeIn = Carbon::parse($ShiftTimeIn);
        $ShiftTimeOut = Carbon::parse($ShiftTimeOut);
    
        $CurrentTime = Carbon::now('Asia/Karachi');
        $CurrentTime = $CurrentTime->toTimeString();
        $CurrentTime = Carbon::parse($CurrentTime);
    
        if ($ShiftTimeOut->lt($ShiftTimeIn)) {
            $ShiftTimeOut->addDay();
        }
    
        if ($CurrentTime->gt($ShiftTimeIn) && $CurrentTime->lt($ShiftTimeOut)) {
            $check = Time::where('user_id', $id)->whereDate('created_at', Carbon::today('Asia/Karachi'))->first();
    
            if (!$check) {
                $new = new Time();
                $new->user_id = $id;
                $new->time_in = Carbon::now('Asia/Karachi');
    
                // Check if the user is late
                if ($CurrentTime->greaterThan($ShiftTimeIn->copy()->addMinutes(15))) {
                    $new->late_status = 1;
                }
    
                $new->save();
    
                if ($request->wantsJson()) {
                    $response = ['status' => true, "message" => "Attendance Marked Successfully!"];
                    return response($response, 200);
                }
    
                session()->flash('success', 'Attendance Marked Successfully!');
                return redirect()->back();
    
            } else {
                if ($request->wantsJson()) {
                    $response = ['status' => true, "message" => "Your Attendance is Already Marked!"];
                    return response($response, 200);
                }
    
                session()->flash('success', 'Your Attendance is Already Marked!');
                return redirect()->back();
            }
    
        } else {
            if ($request->wantsJson()) {
                $response = ['status' => true, "message" => "Your shift has not started yet!"];
                return response($response, 200);
            }
    
            session()->flash('success', 'Your shift has not started yet!');
            return redirect()->back();
        }
    }
    

    public function time_out(Request $request, $id)
    {
        $user = User::find($id);
        $shift = Shift::find($user->shift_id);
        
        $shiftStart = Carbon::parse($shift->time_from);
        $shiftEnd = Carbon::parse($shift->time_to);
        
        if ($shiftEnd->lessThan($shiftStart)) {
            $shiftEnd->addDay();
        }
    
        $totalShiftMinutes = $shiftEnd->diffInMinutes($shiftStart);
    
        $timeRecord = Time::where('user_id', $id)
                          ->whereDate('created_at', Carbon::today('Asia/Karachi'))
                          ->first();
    
        if (!$timeRecord) {
            return response()->json(['status' => false, 'message' => 'No time in record found for today'], 400);
        }
    
        $timeRecord->time_out = Carbon::now('Asia/Karachi');
        $timeRecord->save();
    
        $timeIn = Carbon::parse($timeRecord->time_in, 'Asia/Karachi');
        $timeOut = Carbon::parse($timeRecord->time_out, 'Asia/Karachi');
    
        if ($timeOut->lessThan($timeIn)) {
            $timeOut->addDay();
        }
    
        $totalAttendanceMinutes = $timeOut->diffInMinutes($timeIn);
    
        if ($totalAttendanceMinutes >= $totalShiftMinutes) {
            $timeRecord->status = 'Completed';
        } elseif ($totalAttendanceMinutes >= $totalShiftMinutes / 2) {
            $timeRecord->status = 'Half';
        } elseif ($totalAttendanceMinutes >= $totalShiftMinutes / 4) {
            $timeRecord->status = 'Short';
        } else {
            $timeRecord->status = 'Absent';
        }
        
        $timeRecord->save();
    
        if ($request->wantsJson()) {
            return response()->json(['status' => true, 'message' => 'Time out Successfully!'], 200);
        }
    
        session()->flash('success', 'Time out Successfully!');
        return redirect()->back();
    }
    
    
    
    
    
    
}
