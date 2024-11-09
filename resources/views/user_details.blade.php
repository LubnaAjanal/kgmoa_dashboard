@extends('layouts.main')

<style>
    .attendance-circle {
        width: 30px;
        height: 30px;
        border-radius: 50%;
        border: 2px solid #747574;
        margin-right: 10px;
        background-color: #747574;
    }

    .attendance-circle.filled {
        border-color: #28a745 !important;
        background-color: #28a745 !important;
    }

    .status-message {
        font-size: 1.1em;
        color: #000000;
        margin-top: 20px;
        padding: 10px 15px;
        border-radius: 8px;
        background-color: #e6e7e8;
        border: 1px solid #ddd;
        display: inline-flex;
        justify-content: space-between; /* Distribute space between message and icon */
        align-items: center; /* Vertically center the content */
        max-width: 100%;
        word-wrap: break-word;
        text-align: left; /* Align text to the left for better readability */
    }

    .attendance-container {
        display: inline-flex;
        justify-content: center;
        align-items: center;
        width: 100%; /* Ensures full width to center the .status-message */
    }

    .attendance-heading {
        color: #4B49AC;
        font-weight: bold;
    }

    .status-message i {
        font-size: 1.5em; /* Icon size */
        color: #28a745; /* Default green color for check */
    }

    .status-message .wrong {
        color: #dc3545; /* Red color for wrong */
    }
</style>

@section('content')
    <div class="row">
        <div class="col-12 col-xl-12 mb-4 mb-xl-0">
            <h3 class="text-center" style="text-transform: uppercase;">
                Attendance Progress of <span class="attendance-heading">{{ $register->fullname }}</span>
            </h3>

            <div class="attendance-container d-flex justify-content-center">
                @for ($i = 1; $i <= 6; $i++)
                    <div class="attendance-circle {{ $i <= $attendance->count_attendance ? 'filled' : '' }}"></div>
                @endfor
            </div>

            {{-- Status message based on count_attendance --}}
            <div class="status-message">
                First attendance marked, and kit given
                @if ($attendance->count_attendance == 1)
                    <i class="fa {{ $attendance->count_attendance >= 1 ? 'fa-check' : 'fa-times wrong' }}"></i>
                    Breakfast taken
                @elseif ($attendance->count_attendance == 2)
                    <i class="fa {{ $attendance->count_attendance >= 2 ? 'fa-check' : 'fa-times wrong' }}"></i>
                    Lunch taken
                @elseif ($attendance->count_attendance == 3)
                    <i class="fa {{ $attendance->count_attendance >= 3 ? 'fa-check' : 'fa-times wrong' }}"></i>
                    Dinner taken
                @elseif ($attendance->count_attendance == 4)
                    <i class="fa {{ $attendance->count_attendance >= 4 ? 'fa-check' : 'fa-times wrong' }}"></i>
                    Second day breakfast taken
                @elseif ($attendance->count_attendance == 5)
                    <i class="fa {{ $attendance->count_attendance >= 5 ? 'fa-check' : 'fa-times wrong' }}"></i>
                    Second day lunch taken
                @elseif ($attendance->count_attendance == 6)
                    <i class="fa {{ $attendance->count_attendance >= 6 ? 'fa-check' : 'fa-times wrong' }}"></i>
                @else
                    No attendance recorded yet
                    <i class="fa fa-times wrong"></i>
                @endif
            </div>
        </div>
    </div>
@endsection
