<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Validator;

class AuthController extends Controller
{
    public function register (Request $request) {
    
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            "email" => "required|email|unique:users,email",
            'password' => 'required|string|min:6',
            "phone" => 'required',
        ]);
        if ($validator->fails())
        {
            return response(['errors'=>$validator->errors()->all()], 422);
        }
        
        $user = new User();
        $user->name = $request->name;
        $user->email = $request->email;
        $user->phone_number = $request->phone;
        $user->designation = $request->designation;
        $user->shift_id = $request->shift_id;
        $user->password = Hash::make($request->password);
        $user->role_id = $request->role_id;
        $user->is_active = 1;
        $user->save();

        $token = $user->createToken('Laravel Password Grant Client')->accessToken;
        $response = ['status'=>true,"message" => "Register Successfully",'token' => $token];
        return response($response, 200);
    }





}
