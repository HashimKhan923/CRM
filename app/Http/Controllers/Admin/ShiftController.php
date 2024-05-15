<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Shift;
use App\Models\ProductVariant;

class ShiftController extends Controller
{
    public function index(Request $request)
    {
        $shifts = Shift::all();

        if ($request->wantsJson()) {
            return response()->json(['shifts'=>$shifts]);  
        }

        return view('admin.shifts.index', compact('shifts')); 
    }

    public function create_form()
    {
        return view('admin.shifts.create');
    }

    public function create(Request $request)
    {
        $new = new Shift();
        $new->name = $request->name;
        $new->time_from = $request->time_from;
        $new->time_to = $request->time_to;
        $new->save();

        if ($request->wantsJson()) {
            $response = ['status'=>true,"message" => "New Shift Created Successfully!"];
            return response($response, 200);
            }
    
            session()->flash('success', 'New Shift Created Successfully!');
    
            return redirect()->route('admin.shift.show');
    }

    public function update(Request $request)
    {
        $update = Shift::where('id',$request->id)->first();
        $update->name = $request->name;
        $update->time_from = $request->time_from;
        $update->time_to = $request->time_to;
        $update->save();

        $response = ['status'=>true,"message" => "Shift Updated Successfully!"];
        return response($response, 200);
    }

    public function delete($id)
    {
        Shift::find($id)->delete();

        $response = ['status'=>true,"message" => "Deleted Successfully!"];
        return response($response, 200);

    }

    public function changeStatus($id)
    {
        $status = Shift::where('id',$id)->first();

        if($status->status == 1)
        {
            $status->status = 0;
        }
        else
        {
            $status->status = 1;
        }
        $status->save();

        $response = ['status'=>true,"message" => "Status Changed Successfully!"];
        return response($response, 200);

    }
}
