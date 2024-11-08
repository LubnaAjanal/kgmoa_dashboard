<?php

namespace App\Http\Controllers;

use App\Models\Register;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;
use Milon\Barcode\Facades\DNS2DFacade;

class RegisterController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        try {
            $query = Register::query();

            // Apply other query string parameters dynamically
            foreach ($request->query() as $key => $value) {
                if (Schema::hasColumn('register', $key)) {
                    $query->where($key, $value);
                }
            }

            $users = $query->whereNull('deleted_at')->get();

            $response = [
                'status' => true,
                'data' => $users,
                'message' => 'User Details Retrieved Successfully',
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
        $response = array(
            'status' => false,
            'data' => array(),
            'message' => '',
        );

        // Validate incoming request
        $validated = $request->validate([
            'fullname' => 'required|string',
            'email' => 'nullable|email|unique:register,email',
            'mobile' => 'required|numeric',
            'gov_id' => 'required|string|unique:register,gov_id',
            'arrival_date' => 'nullable|date',
            'arrival_time' => 'nullable|date_format:H:i',
            'working_place' => 'nullable|string',
            'stay_selected_at' => 'nullable|string',
            'departuring_place' => 'nullable|string',
            'departuring_date' => 'nullable|date',
            'departuring_time' => 'nullable|date_format:H:i',
            'adults' => 'nullable|string',
            'children' => 'nullable|string',
            'accomodation_request' => 'nullable|string',
        ]);

        try {
            // Retrieve the last `user_unique_id` and generate the next sequential ID
            $lastUser = Register::orderBy('id', 'desc')->first();
            if ($lastUser && $lastUser->user_unique_id) {
                $lastId = intval($lastUser->user_unique_id);
                $nextId = str_pad($lastId + 1, 4, '0', STR_PAD_LEFT); // Increment and pad with zeros
            } else {
                $nextId = '0001'; // Start at "0001" if no users exist
            }

            // Add the new unique ID to the validated data
            $validated['user_unique_id'] = $nextId;
            // Create a new Register record
            $register = Register::create($validated);

            // Return response with status, data, and message
            $response = [
                'status' => true,
                'data' => $register,
                'message' => 'User Registered successfully',
            ];
            return response()->json($response, 201); // Created
        } catch (\Exception $e) {
            $response = [
                'status' => false,
                'data' => array(),
                'message' => 'Error registering user: ' . $e->getMessage(),
            ];
            return response()->json($response, 500);
        }
    }

    public function edit(Request $request, $id)
    {

        // Fetch the register data based on the provided ID
        $register = Register::find($id);

        if (!$register) {
            // If no register found, you can return an error or a 404 page
            abort(404, 'Register not found');
        }
        $user_unique_id = $register->user_unique_id;
        $qrCode = DNS2DFacade::getBarcodeHTML($user_unique_id, 'QRCODE');

        // $user_unique_id = $register->user_unique_id;
        // $qrCode = base64_encode(DNS2DFacade::getBarcodePNG($user_unique_id, 'QRCODE'));
        // Pass the register data to the view
        return view('show_idcard', [
            'register' => $register,
            'qrCode' => $qrCode
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        try {
            // Find the register by ID
            $register = Register::findOrFail($id);

            $response = [
                'status' => true,
                'data' => $register,
                'message' => 'User Registered successfully',
            ];
            return response()->json($response, 201); // Created
        } catch (\Exception $e) {
            $response = [
                'status' => false,
                'data' => array(),
                'message' => 'Error registering user: ' . $e->getMessage(),
            ];
            return response()->json($response, 500);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        try {
            // Validate incoming request
            $validated = $request->validate([
                'fullname' => 'required|string',
                'email' => 'nullable|email|unique:register,email,' . $id,
                'mobile' => 'required|numeric',
                'gov_id' => 'required|string|unique:register,gov_id,' . $id,
                'arrival_date' => 'nullable|date',
                'arrival_time' => 'nullable|date_format:H:i',
                'working_place' => 'nullable|string',
                'stay_selected_at' => 'nullable|string',
                'departuring_place' => 'nullable|string',
                'departuring_date' => 'nullable|date',
                'departuring_time' => 'nullable|date_format:H:i',
                'adults' => 'nullable|string',
                'children' => 'nullable|string',
                'accomodation_request' => 'nullable|string',
            ]);

            // Find the register by ID and update it
            $register = Register::findOrFail($id);
            $register->update($validated);

            $response = [
                'status' => true,
                'data' => $register,
                'message' => 'User Registered successfully',
            ];
            return response()->json($response, 201); // Created
        } catch (\Exception $e) {
            $response = [
                'status' => false,
                'data' => array(),
                'message' => 'Error registering user: ' . $e->getMessage(),
            ];
            return response()->json($response, 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            // Find the register by ID
            $register = Register::findOrFail($id);
            // Soft delete the register
            $register->delete();

            $response = [
                'status' => true,
                'data' => $register,
                'message' => 'User Registered successfully',
            ];
            return response()->json($response, 201); // Created
        } catch (\Exception $e) {
            $response = [
                'status' => false,
                'data' => array(),
                'message' => 'Error registering user: ' . $e->getMessage(),
            ];
            return response()->json($response, 500);
        }
    }
}
