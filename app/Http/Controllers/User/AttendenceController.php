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
    public function index($id)
    {
        $data = Time::with('user')->where('user_id',$id)->whereDate('created_at', Carbon::today('Asia/Karachi'))->get();

        return response()->json(['data'=>$data]);
    }

    public function search(Request $request)
    {
        $data = Time::with('user')->where('created_at','>=',$request->from_date)->where('created_at','<=',$request->to_date)->where('user_id',$request->user_id)->get();

        return response()->json(['data'=>$data]);
    }

    public function time_in($id)
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
    
                return response()->json(['message'=>'Attendence Marked Successfully!']);
            }
            else
            {
                return response()->json(['message'=>'Your Attendence is Already Marked!']);
            }



        }
        else
        {
            return response()->json(['message'=>'Your shift is not started yet!']);
        }




    }

    public function time_out($id)
    {
        
        $time_out = Time::where('user_id',$id)->whereDate('created_at', Carbon::today())->first();
        $time_out->time_out = Carbon::now('Asia/Karachi');
        $time_out->save();

        return response()->json(['message'=>'Time out Successfully!']);
    }
}
