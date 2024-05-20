@extends('layouts.master')

@section('content')
<div class="container-fluid px-4">
    <h1 class="mt-4">Dashboard</h1>
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item active">Dashboard</li>
    </ol>
    @if (session('success'))
    <div class="alert alert-success">
        {{ session('success') }}
    </div>
    @endif
    <div class="row" style="background-color:#343A40; color:white; padding-top:11px; border-radius:10px">


            <div class="col-md-4">
                @if($currentTotalTime != null)
                <h2>Total Time <span id="timer_h">{{$currentTotalTime->h}}</span>:<span id="timer_i">{{$currentTotalTime->i}}</span>:<span id="timer_s">{{$currentTotalTime->s}}</span> </h2>
                @else
                <h2>Total Time 00:00:00</h2>
                @endif
            </div>

            <div class="col-md-4">
            @if($remainingTime != '')
                <h2>Remaining Time <span id="C_timer_h">{{$remainingTime->h}}</span>:<span id="C_timer_i">{{$remainingTime->i}}</span>:<span id="C_timer_s">{{$remainingTime->s}}</span> </h2>
                @else
                <h2>Remaining Time 00:00:00</h2>
                @endif
            </div>




        <div class="col-md-2">
        @if($currentTotalTime != null)
        <div class="row">
            <div class="col-md-6">
            <a href="{{route('user.time_out',auth()->user()->id)}}" class="btn btn-danger">End Shift</a>
            </div>
        
               <div class="col-md-6">
               <div class="dropdown">
               <button class="btn btn-info dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                   Break
               </button>
               <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                   <a class="dropdown-item" href="{{ route('user.break_in', ['user_id' => auth()->user()->id, 'time_id' => $Time->id, 'break_type' => 'Prayer']) }}">Prayer</a>
                   <a class="dropdown-item" href="{{route('user.break_in',['user_id' => auth()->user()->id, 'time_id' => $Time->id, 'break_type' => 'Lunch'])}}">Lunch</a>
                   <a class="dropdown-item" href="{{route('user.break_in',['user_id' => auth()->user()->id, 'time_id' => $Time->id, 'break_type' => 'Tea'])}}">Tea</a>
                   <a class="dropdown-item" href="{{route('user.break_in',['user_id' => auth()->user()->id, 'time_id' => $Time->id, 'break_type' => 'Others'])}}">Others</a>
               </div>
               </div>
               </div>

        </div>

        @else
        <a href="{{route('user.time_in',auth()->user()->id)}}" class="btn btn-primary">Start Shift</a>
        @endif    
        </div>
        <div class="col-md-2">
            <h5>Shift:@if(isset($ShiftN)) {{$ShiftN->name}} @endif</h5>
            <hr style="background-color:white">
            <h5>Total Time: @if(isset($totalShiftHours)){{$totalShiftHours}} Hrs @endif</h5>
        </div>
    </div>
    <hr>
    <div class="row">
        <div class="col-xl-6">
            <div class="card mb-4">
                <div class="card-header">
                    <i class="fas fa-chart-area me-1"></i>
                    Area Chart Example
                </div>
                <div class="card-body"><canvas id="myAreaChart" width="100%" height="40"></canvas></div>
            </div>
        </div>
        <div class="col-xl-6">
            <div class="card mb-4">
                <div class="card-header">
                    <i class="fas fa-chart-bar me-1"></i>
                    Bar Chart Example
                </div>
                <div class="card-body"><canvas id="myBarChart" width="100%" height="40"></canvas></div>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        function formatTime(unit) {
            return unit < 10 ? '0' + unit : unit;
        }
            let checkTime = document.getElementById('timer_h');
            if(checkTime)
            {
            let hoursElement = checkTime;
            let minutesElement = document.getElementById('timer_i');
            let secondsElement = document.getElementById('timer_s');

            let hours = parseInt(hoursElement.textContent) || 0;
            let minutes = parseInt(minutesElement.textContent) || 0;
            let seconds = parseInt(secondsElement.textContent) || 0;


                function updateTimer() {
                    seconds++;
                    if (seconds === 60) {
                        seconds = 0;
                        minutes++;
                        if (minutes === 60) {
                            minutes = 0;
                            hours++;
                        }
                    }

                    // Update the timer display
                    hoursElement.textContent = formatTime(hours);
                    minutesElement.textContent = formatTime(minutes);
                    secondsElement.textContent = formatTime(seconds);
                }
        }


/////////////////////////////////////////////////////////////////////////////
        let checkBreak = document.getElementById('B_timer_h');
        if(checkBreak != null)
        {
        let B_hoursElement = checkBreak
        let B_minutesElement = document.getElementById('B_timer_i');
        let B_secondsElement = document.getElementById('B_timer_s');

        let B_hours = parseInt(B_hoursElement.textContent) || 0;
        let B_minutes = parseInt(B_minutesElement.textContent) || 0;
        let B_seconds = parseInt(B_secondsElement.textContent) || 0;


        function updateBTimer() {
            B_seconds++;
            if (B_seconds === 60) {
                B_seconds = 0;
                B_minutes++;
                if (B_minutes === 60) {
                    B_minutes = 0;
                    B_hours++;
                }
            }

            // Update the timer display
            B_hoursElement.textContent = formatTime(B_hours);
            B_minutesElement.textContent = formatTime(B_minutes);
            B_secondsElement.textContent = formatTime(B_seconds);
        }

        setInterval(updateBTimer, 1000);

        }
        


/////////////////////////////////////////////////////////////////////////////

        let C_hoursElement = document.getElementById('C_timer_h');
        let C_minutesElement = document.getElementById('C_timer_i');
        let C_secondsElement = document.getElementById('C_timer_s');

        let C_hours = parseInt(C_hoursElement.textContent) || 0;
        let C_minutes = parseInt(C_minutesElement.textContent) || 0;
        let C_seconds = parseInt(C_secondsElement.textContent) || 0;



        function updateCTimer() {
            if (C_hours === 0 && C_minutes === 0 && C_seconds === 0) {
                clearInterval(timerInterval); // Stop the timer when it reaches zero
                return;
            }
            C_seconds--;
            if (C_seconds < 0) {
                C_seconds = 59;
                C_minutes--;
                if (C_minutes < 0) {
                    C_minutes = 59;
                    C_hours--;
                }
            }

            // Update the timer display
            C_hoursElement.textContent = formatTime(C_hours);
            C_minutesElement.textContent = formatTime(C_minutes);
            C_secondsElement.textContent = formatTime(C_seconds);
        }

        // Start the timer
        setInterval(updateTimer, 1000);
        setInterval(updateCTimer, 1000);
    });
</script>

@endsection
