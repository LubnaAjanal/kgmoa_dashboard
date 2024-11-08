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
    
            // Find or create an attendance record for this user
            $attendance = Attendance::firstOrNew(['user_unique_id' => $validated['user_unique_id']]);
            $attendance->registered_id = $register->id;
    
            // Define attendance actions for each count
            $attendanceActions = [
                1 => 'Attendance marked and kit given',
                2 => 'Breakfast',
                3 => 'Lunch',
                4 => 'Dinner',
                5 => 'Second day breakfast',
                6 => 'Second day Lunch'
            ];
    
            // Check and update the attendance count based on the current state
            if (is_null($attendance->count_attendance)) {
                // First scan, initialize count_attendance to 1
                $attendance->count_attendance = 1;
                $response['message'] = $attendanceActions[1];
            } elseif ($attendance->count_attendance < 6) {
                // Increment count_attendance if it's less than 6
                $attendance->count_attendance += 1;
                $response['message'] = $attendanceActions[$attendance->count_attendance];
            } else {
                // Max count of 6 reached
                $response['message'] = 'Maximum attendance count reached.';
                return response()->json($response, 200);  // Success with a message
            }
    
            // Save the updated attendance count
            $attendance->save();
    
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
