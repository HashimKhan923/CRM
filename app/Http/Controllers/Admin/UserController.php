<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Hash;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $all_users = User::with('shift','department')->where('role_id',2)->get();

        if ($request->wantsJson()) {
            return response()->json(['all_users'=>$all_users]);  
        }

        return view('admin.users.index', compact('all_users'));
    }

    public function create_form()
    {
        return view('admin.users.create');
    }

    public function create(Request $request)
    {

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'phone_number' => 'required|unique:users,phone_number',
            'address' => 'required',
            'designation' => 'required',
            'department_id' => 'required',
            'shift_id' => 'required',
            'role_id' => 'required',
            'password' => 'required',
        ]);

        $user = new User();
        $user->name = $request->name;
        $user->email = $request->email;
        $user->phone_number = $request->phone_number;
        $user->designation = $request->designation;
        $user->shift_id = $request->shift_id;
        $user->password = Hash::make($request->password);
        $user->role_id = $request->role_id;
        $user->department_id = $request->department_id;
        $user->status = 1;
        $user->save();

        if ($request->wantsJson()) {
        $response = ['status'=>true,"message" => "Register Successfully"];
        return response($response, 200);
        }

        session()->flash('success', 'User Registerd Successfully');

        return redirect()->route('admin.users.show');
    }

    public function update_form($id)
    {
        $data = User::with('shift','department')->where('id',$id)->first();

        return view('admin.users.update',compact('data'));
    }

    public function update(Request $request)
    {

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email',
            'phone_number' => 'required|unique:users,phone_number',
            'address' => 'required',
            'designation' => 'required',
            'department_id' => 'required',
            'shift_id' => 'required',
            'role_id' => 'required',
        ]);

        $user = User::where('id',$request->user_id)->first();
        $user->name = $request->name;
        $user->email = $request->email;
        $user->phone_number = $request->phone_number;
        $user->designation = $request->designation;
        $user->shift_id = $request->shift_id;
        $user->password = Hash::make($request->password);
        $user->role_id = $request->role_id;
        $user->department_id = $request->department_id;
        $user->save();

        if ($request->wantsJson()) {
        $response = ['status'=>true,"message" => "Updated Successfully"];
        return response($response, 200);
        }

        session()->flash('success', 'User Updated Successfully');

        return redirect()->route('admin.users.show');
    }

    public function changeStatus($id)
    {
        $status = User::where('id',$id)->first();

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

    public function delete(Request $request, $id)
    {
        User::find($id)->delete();

        if ($request->wantsJson()) {
            $response = ['status'=>true,"message" => "User Deleted Successfully"];
            return response($response, 200);
            }
    
            session()->flash('success', 'User Deleted Successfully');
    
            return redirect()->route('admin.users.show');

    }
}
