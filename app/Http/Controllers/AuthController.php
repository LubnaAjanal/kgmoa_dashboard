<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Admin;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class AuthController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
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

    public function login(Request $request)
    {
        // Initialize response array
        $response = [
            'status' => false,
            'data' => [],
            'message' => '',
        ];

        try {
            // Validate input data
            $request->validate([
                'email' => 'required|string|email', // Validate email field
                'password' => 'required|string',
            ]);

            // Fetch the user by email
            $admin = Admin::where('email', $request->email)->first();

            if ($admin && $admin->password === $request->password) {
                // If passwords match, authenticate user
                Session::put('admin', $admin); // Store the entire Admin model in session
                // dd(session()->all());

                // Success response
                $response['status'] = true;
                $response['data'] = $admin;
                $response['message'] = 'Login successful';

                return response()->json($response);
            }

            // Authentication failed
            $response['message'] = 'Invalid email or password';
            return response()->json($response, 401);
        } catch (\Exception $e) {
            // Handle any unexpected errors
            $response['message'] = 'An error occurred: ' . $e->getMessage();
            return response()->json($response, 500);
        }
    }
    
    // Logout method to clear session
    public function logout(Request $request)
    {
        Auth::logout(); // Clear the authenticated user
        Session::flush(); // Clear all session data

        return response()->json([
            'message' => 'Logged out successfully'
        ]);
    }
}