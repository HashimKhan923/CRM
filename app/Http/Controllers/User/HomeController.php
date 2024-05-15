<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Breaks;
use App\Models\Time;
use App\Models\Shift;
use App\Models\User;
use Carbon\Carbon;

class HomeController extends Controller
{
    public function index($id)
    {
    
        $all_breaks = Breaks::where('user_id',$id)->whereDate('created_at', Carbon::today('Asia/Karachi'))->get();

        $user = User::where('id',$id)->first();
        $shiftk = Shift::where('id',$user->shift_id)->first();

        $shift = Carbon::parse($shiftk->time_to)->diff(Carbon::parse($shiftk->time_from));
        $shift = Carbon::parse($shift->h.':'.$shift->i.':'.$shift->s);

        $TotalBreakTime = Carbon::parse('00:00:00');
        foreach($all_breaks as $break)
        {
            $start = Carbon::parse($break->time_in);
            $end = Carbon::parse($break->time_out);


            $diff = $end->diff($start);
            $hours = $diff->h;
            $minutes = $diff->i;
            $seconds = $diff->s;


            $TotalBreakTime = $TotalBreakTime->addHours($hours)->addMinutes($minutes)->addSeconds($seconds);
        }
            
        

        $Time = Time::where('user_id',$id)->whereDate('created_at', Carbon::today('Asia/Karachi'))->first();

        $Time_in = Carbon::parse($Time->time_in)->toTimeString();
        
        $Time_now = Carbon::now('Asia/Karachi')->toTimeString();
        $Time_now = Carbon::parse($Time_now);
        
        $currentTotalTime = $Time_now->diff($Time_in);

        
        $hour = $currentTotalTime->h;
        $minute = $currentTotalTime->i;
        $second = $currentTotalTime->s;

        $totalTime = Carbon::parse($hour.':'.$minute.':'.$second);
        
        $remainingTime = Carbon::parse($shiftk->time_to)->diff($Time_now);
        $remainingTime = Carbon::parse($remainingTime->h.':'.$remainingTime->i.':'.$remainingTime->s);
        
        if($TotalBreakTime)
        {
            $totalTime = $totalTime->subHours($TotalBreakTime->hour)->subMinutes($TotalBreakTime->minute)->subSeconds($TotalBreakTime->second);
        }


        $message = "";
       if($totalTime->isAfter($shift))
       {
            $message = "Shift Completed Successfully!";
       }

       $response = ['status'=>true,"TotalTime"=>$totalTime->toTimeString(),"RemainingTime"=>$remainingTime->toTimeString(),"message" => $message];
       return response($response, 200);




    }
}
