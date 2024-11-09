<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use App\Models\Register;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;

class AttendanceController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        try {
            $query = Attendance::with('register');

            // Apply other query string parameters dynamically
            foreach ($request->query() as $key => $value) {
                if (Schema::hasColumn('attendance', $key)) {
                    $query->where($key, $value);
                }
            }

            $users = $query->whereNull('deleted_at')->get();

            $response = [
                'status' => true,
                'data' => $users,
                'message' => 'Attendance Retrieved Successfully',
            ];

            // Return success response
            return response()->json($response, 200);
        } catch (\Exception $e) {
            // Return error response in case of exception
            $response = [
                'status' => false,
                'data' => array(),
                'message' => 'An error occurred while: ' . $e->getMessage(),
            ];

            return response()->json($response, 500);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $response = [
            'status' => false,
            'data' => [],
            'message' => '',
        ];
    
        // Validate the incoming user_unique_id
        $validated = $request->validate([
            'user_unique_id' => 'required|string',
        ]);
    
        try {
            // Find the user by `user_unique_id`
            $register = Register::where('user_unique_id', $validated['user_unique_id'])->first();
    
            // If user is not found, return a 404 error response
            if (!$register) {
                $response['message'] = 'User not found.';
                return response()->json($response, 404);
            }
    
            // Get the last attendance record for the user
            $lastAttendance = Attendance::where('user_unique_id', $validated['user_unique_id'])->orderBy('created_at', 'desc')->first();
    
            // Define attendance actions for each count
            $attendanceActions = [
                1 => 'Attendance marked and kit given. Thank you.',
                2 => 'Breakfast Taken. Thank you.',
                3 => 'Lunch Taken. Thank you.',
                4 => 'Dinner Taken. Thank you.',
                5 => 'Second Breakfast Taken. Thank you.',
                6 => 'Second Lunch Taken. Thank you.',
            ];
    
            // If no previous attendance, create a new one with count_attendance = 1
            if (!$lastAttendance) {
                $attendance = new Attendance();
                $attendance->user_unique_id = $validated['user_unique_id'];
                $attendance->registered_id = $register->id;
                $attendance->count_attendance = 1; // Start at 1
                $attendance->scanned_at = now(); // Store the scanned time
                $attendance->save();
    
                $response['message'] = $attendanceActions[1];
            } else {
                // Check if the user has already scanned 6 times
                if ($lastAttendance->count_attendance >= 6) {
                    $response['message'] = 'Maximum attendance count reached.';
                    return response()->json($response, 200);  // Success but max reached
                }
    
                // Otherwise, create a new attendance record with the next count
                $attendance = new Attendance();
                $attendance->user_unique_id = $validated['user_unique_id'];
                $attendance->registered_id = $register->id;
                $attendance->count_attendance = $lastAttendance->count_attendance + 1; // Increment the attendance count
                $attendance->scanned_at = now(); // Store the scanned time
                $attendance->save();
    
                $response['message'] = $attendanceActions[$attendance->count_attendance];
            }
    
            // Successful response with updated attendance data
            $response['status'] = true;
            $response['data'] = [
                'user_unique_id' => $attendance->user_unique_id,
                'count_attendance' => $attendance->count_attendance,
            ];
    
            return response()->json($response, 200);
    
        } catch (\Exception $e) {
            $response['message'] = 'Error updating attendance: ' . $e->getMessage();
            return response()->json($response, 500);  // Internal server error
        }
    }
    
    
    


    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
