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
        $attendences = Time::with('user')->where('user_id',$id)->whereDate('created_at', Carbon::today('Asia/Karachi'))->get();

        if ($request->wantsJson()) {
            return response()->json(['attendences'=>$attendences]);  
        }

        return view('user.attendences.index', compact('attendences')); 
    }

    public function search(Request $request)
    {
        $attendences = Time::with('user')->where('created_at','>=',$request->from_date)->where('created_at','<=',$request->to_date)->where('user_id',$request->user_id)->get();

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

    public function time_out(Request $request,$id)
    {
        
        $time_out = Time::where('user_id',$id)->whereDate('created_at', Carbon::today())->first();
        $time_out->time_out = Carbon::now('Asia/Karachi');
        $time_out->save();
        if ($request->wantsJson()) {
            $response = ['status'=>true,"message" => "Time out Successfully!"];
            return response($response, 200);
            }
    
            session()->flash('success', 'Time out Successfully!');
    
            return redirect()->back();
    }
}
