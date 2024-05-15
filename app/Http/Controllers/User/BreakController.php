<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Breaks;
use Carbon\Carbon;


class BreakController extends Controller
{

    


    public function index($id)
    {
        $data = Breaks::where('user_id',$id)->whereDate('created_at', Carbon::today('Asia/Karachi'))->get();

        return response()->json(['data'=>$data]);
    }

    public function break_in(Request $request)
    {
        $new = new Breaks();
        $new->user_id = $request->user_id;
        $new->time_id = $request->time_id;
        $new->type = $request->type;
        $new->time_in = Carbon::now('Asia/Karachi');
        $new->save();

        return response()->json(['message'=>'Break in Successfully!']);
    }

    public function break_out($id)
    {
        
        $break_out = Breaks::where('user_id',$id)->whereDate('created_at', Carbon::today('Asia/Karachi'))->latest('created_at')->first();
        $break_out->time_out = Carbon::now('Asia/Karachi');
        $break_out->save();

        return response()->json(['message'=>'Break out Successfully!']);
    }
}
