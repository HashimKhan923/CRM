@extends('layouts.master')

@section('content')
<div class="container-fluid px-4">
    <h1 class="mt-4">Breaks</h1>
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item"><a href="index.html">Dashboard</a></li>
        <li class="breadcrumb-item active">Breaks</li>
    </ol>

    <div class="container mt-5">
        <form class="row g-3 " style="margin-left: -83px">
            <div class="col-md-4">
                <label for="inputName" class="form-label">From Date:</label>
                <input type="date" class="form-control" id="inputName" placeholder="Name">
            </div>
            <div class="col-md-4">
                <label for="inputEmail" class="form-label">To Date:</label>
                <input type="date" class="form-control" id="inputEmail" placeholder="Email">
            </div>
            <div class="col-md-4 d-flex align-items-end">
                <button type="submit" class="btn btn-primary">Search</button>
            </div>
        </form>
    </div>

    @if (session('success'))
        <div class="alert alert-success mt-3">
            {{ session('success') }}
        </div>
    @endif

    <div class="card mb-4 mt-4">
        <div class="card-body">
            <table id="datatablesSimple" class="table table-striped">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Department</th>
                        <th>Shift</th>
                        <th>Time in</th>
                        <th>Time out</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($breaks as $break)
                        <tr>
                            <td>{{$break->user->name}}</td>
                            <td>{{$break->user->email}}</td>
                            <td>{{$break->user->department->name}}</td>
                            <td>{{$break->user->shift->name}}</td>
                            <td>{{$break->time_in}}</td>
                            <td>{{$break->time_out}}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
