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

    public function time_in(Request $request,$id)
    {

        

        $check_user = User::where('id',$id)->first();

        $check_shift = Shift::where('id',$check_user->shift_id)->first();

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

        if($CurrentTime->gt($ShiftTimeIn) && $CurrentTime->lt($ShiftTimeOut))
        {

            $check = Time::where('user_id',$id)->whereDate('created_at', Carbon::today('Asia/Karachi'))->first();

            if(!$check)
            {
                $new = new Time();
                $new->user_id = $id;
                $new->time_in = Carbon::now('Asia/Karachi');
                $new->save();

                if ($request->wantsJson()) {
                    $response = ['status'=>true,"message" => "Attendence Marked Successfully!"];
                    return response($response, 200);
                    }
            
                    session()->flash('success', 'Attendence Marked Successfully!');
            
                    return redirect()->back();
    
            }
            else
            {
                if ($request->wantsJson()) {
                    $response = ['status'=>true,"message" => "Your Attendence is Already Marked!"];
                    return response($response, 200);
                    }
            
                    session()->flash('success', 'Your Attendence is Already Marked!');
            
                    return redirect()->back();
            }



        }
        else
        {
            if ($request->wantsJson()) {
                $response = ['status'=>true,"message" => "Your shift is not started yet!"];
                return response($response, 200);
                }
        
                session()->flash('success', 'Your shift is not started yet!');
        
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
    
        $totalShiftHours = $shiftEnd->diffInHours($shiftStart);
    
        $timeRecord = Time::where('user_id', $id)
                          ->whereDate('created_at', Carbon::today())
                          ->first();
    
        $timeRecord->time_out = Carbon::now('Asia/Karachi');
        $timeRecord->save();
    
        $timeIn = Carbon::parse($timeRecord->time_in);
        $timeOut = Carbon::parse($timeRecord->time_out);
        
        if ($timeOut->lessThan($timeIn)) {
            $timeOut->addDay();
        }
    
        $totalAttendanceHours = $timeOut->diffInHours($timeIn);
    
        if ($timeIn->greaterThan($shiftStart->addMinutes(15))) {
            $timeRecord->late_status = 1;
        }
        if ($totalAttendanceHours >= $totalShiftHours) {
            $timeRecord->status = 'completed';
        } elseif ($totalAttendanceHours >= $totalShiftHours / 2) {
            $timeRecord->status = 'half';
        } elseif ($totalAttendanceHours >= $totalShiftHours / 4) {
            $timeRecord->status = 'short';
        }
        
        $timeRecord->save();
    
        if ($request->wantsJson()) {
            return response()->json(['status' => true, 'message' => 'Time out Successfully!'], 200);
        }
    
        session()->flash('success', 'Time out Successfully!');
        return redirect()->back();
    }
    
    
}
