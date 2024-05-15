@extends('layouts.master')

@section('content')
<div class="container-fluid px-4">
                        <h1 class="mt-4">Add New User</h1>
                        <ol class="breadcrumb mb-4">
                            <li class="breadcrumb-item"><a href="index.html">Dashboard</a></li>
                            <li class="breadcrumb-item active">Add New User</li>
                        </ol>

                        <div class="card mb-4">

                            <div class="card-body">
                            <form method="post" action="{{route('admin.users.create')}}">
                                @csrf
                                        <div class="form-row">
                                            <div class="form-group col-md-4">
                                            <label for="">Name</label>
                                            <input type="text" class="form-control" name="name" id="" placeholder="Name">
                                            @error('name')
                                                <div class="text-danger">{{ $message }}</div>
                                            @enderror
                                            </div>
                                            <div class="form-group col-md-4">
                                            <label for="inputPassword4">Email</label>
                                            <input type="email" class="form-control" name="email" id="inputPassword4" placeholder="Email">
                                            @error('email')
                                                <div class="text-danger">{{ $message }}</div>
                                            @enderror
                                            </div>
                                            <div class="form-group col-md-4">
                                            <label for="inputPassword4">Phone Number</label>
                                            <input type="number" class="form-control" name="phone_number" id="inputPassword4" placeholder="phone number">
                                            @error('phone_number')
                                                <div class="text-danger">{{ $message }}</div>
                                            @enderror
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label for="inputAddress">Address</label>
                                            <input type="text" class="form-control" name="address" id="inputAddress" placeholder="Address">
                                            @error('address')
                                                <div class="text-danger">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <div class="form-row">
                                            <div class="form-group col-md-4">
                                            <label for="">Designation</label>
                                            <input type="text" class="form-control" name="designation" placeholder="Designation" id="">
                                            @error('designation')
                                                <div class="text-danger">{{ $message }}</div>
                                            @enderror
                                            </div>

                                            <div class="form-group col-md-4">
                                            <label for="inputState">Department</label>

                                            <select id="inputState" name="department_id" class="form-control">
                                                <option>-- Select Department --</option>
                                                @php
                                                    $Departments = App\Models\Department::all();
                                                @endphp
                                                @if($Departments)
                                                @foreach($Departments as $item)
                                                <option value="{{ $item->id }}">{{ $item->name }}</option>
                                                @endforeach
                                                @endif
                                            </select>
                                            @error('department_id')
                                                <div class="text-danger">{{ $message }}</div>
                                            @enderror
                                            </div>

                                            <div class="form-group col-md-4">
                                            <label for="inputState">Shift</label>
                                            <select id="inputState" name="shift_id" class="form-control">
                                                <option>-- Select Shift --</option>
                                                @php
                                                    $Shifts = App\Models\Shift::all();
                                                @endphp
                                                @if($Shifts)
                                                @foreach($Shifts as $item)
                                                <option value="{{ $item->id }}">{{ $item->name }}</option>
                                                @endforeach
                                                @endif
                                            </select>
                                            @error('shift_id')
                                                <div class="text-danger">{{ $message }}</div>
                                            @enderror
                                            </div>
                                        </div>
                                        <div class="form-row">
                                        <div class="form-group col-md-4">
                                            <label for="inputState">Role</label>
                                            <select id="inputState" name="role_id" class="form-control">
                                            <option>-- Select Role --</option>
                                                @php
                                                    $Roles = App\Models\Role::all();
                                                @endphp
                                                @if($Roles)
                                                @foreach($Roles as $item)
                                                <option value="{{ $item->id }}">{{ $item->name }}</option>
                                                @endforeach
                                                @endif
                                            </select>
                                            @error('role_id')
                                                <div class="text-danger">{{ $message }}</div>
                                            @enderror
                                            </div>

                                            <div class="form-group col-md-4">
                                            <label for="inputCity">Password</label>
                                            <input type="password" name="password" class="form-control" id="inputCity">
                                            @error('password')
                                                <div class="text-danger">{{ $message }}</div>
                                            @enderror
                                            </div>

                                        </div>

                                        <button type="submit" class="btn btn-primary">Submit</button>
                            </form>
                            </div>
                        </div>
                    </div>

                    @endsection('content')