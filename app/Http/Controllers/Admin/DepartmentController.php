<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Department;
class DepartmentController extends Controller
{
    public function index()
    {
        $departments = Department::all();

        return response()->json(['departments'=>$departments]);
    }

    public function create(Request $request)
    {
        $new = new Department();
        $new->name = $request->name;
        $new->save();

        $response = ['status'=>true,"message" => "New Department Created Successfully!"];
        return response($response, 200);
    }

    public function update(Request $request)
    {
        $update = Department::where('id',$request->id)->first();
        $update->name = $request->name;
        $update->save();

        $response = ['status'=>true,"message" => "Department Updated Successfully!"];
        return response($response, 200);
    }

    public function delete($id)
    {
        Department::find($id)->delete();

        $response = ['status'=>true,"message" => "Deleted Successfully!"];
        return response($response, 200);

    }

    public function changeStatus($id)
    {
        $status = Department::where('id',$id)->first();

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
