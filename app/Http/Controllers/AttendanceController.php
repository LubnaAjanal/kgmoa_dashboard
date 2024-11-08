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
            'message' => '',
        ];
    
        $validated = $request->validate([
            'user_unique_id' => 'required|string',
        ]);
    
        try {
            // Find the user by `user_unique_id`
            $register = Attendance::where('user_unique_id', $validated['user_unique_id'])->first();
    
            if (!$register) {
                $response['message'] = 'User not found.';
                return response()->json($response, 404);
            }
    
            // Check the current count in the attendance record
            if (is_null($register->count_attendance)) {
                // Initialize count_attendance to 1 if it’s null
                $register->count_attendance = 1;
            } elseif ($register->count_attendance < 6) {
                // Increment count_attendance if it's less than 6
                $register->count_attendance += 1;
            } else {
                // Max count of 6 reached
                $response['message'] = 'Maximum attendance count reached.';
                return response()->json($response, 200);
            }
    
            // Save the updated attendance count
            $register->save();
    
            // Successful response
            $response = [
                'status' => true,
                'data' => $register,
                'message' => 'Attendance added successfully.',
            ];
            return response()->json($response, 200);
    
        } catch (\Exception $e) {
            $response['message'] = 'Error updating attendance: ' . $e->getMessage();
            return response()->json($response, 500);
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
