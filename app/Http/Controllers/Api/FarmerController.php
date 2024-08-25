<?php

namespace App\Http\Controllers\Api;
use App\Models\Farmer;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class FarmerController extends Controller
{
    public function storeFarmer(Request $request)
    {
        try {
            // Validate the incoming request data
            $validatedData = $request->validate([
                'first_name' => 'required|string',
                'middle_name' => 'required|string',
                'last_name' => 'required|string',
                'gender' => 'required|string',
                'household_size' => 'required|integer',
                'dob' => 'required|date',
                'id_number' => 'required|string',
                'grower_id' => 'required|string',
                'phone_number' => 'required|string',
                'farm_size' => 'required|numeric', // New field
                'production_area' => 'required|numeric', // New field
                'permanent_male_workers' => 'required|integer', // New field
                'permanent_female_workers' => 'required|integer', // New field
                'temporary_male_workers' => 'required|integer', // New field
                'temporary_female_workers' => 'required|integer', // New field
            ]);
    
            // Add additional fields to the validated data
            $validatedData['inspection_status'] = 'Not Inspected';
            $validatedData['inspector_id'] = Auth::id(); // Get the authenticated user's ID
            $validatedData['company_id'] = Auth::user()->current_company; // Get the authenticated user's ID
    
            // Create a new Farmer instance and save it to the database
            $farmer = Farmer::create($validatedData);
    
            return response()->json([
                'status' => true,
                'message' => 'Farmer created successfully',
                'data' => $farmer
            ], 201);
        } catch (QueryException $e) {
            return response()->json([
                'status' => false,
                'message' => 'Failed to create farmer',
                'error' => $e->getMessage()
            ], 500);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'An unexpected error occurred',
                'error' => $e->getMessage()
            ], 500);
        }
    }
    public function getFarmers(Request $request)
{
    try {
        $userId = \Auth::user()->id;
        $companyId = \Auth::user()->current_company;
    
        $farmers = Farmer::where('company_id', $companyId)
            ->where(function($query) use ($userId) {
                $query->where(function($subQuery) use ($userId) {
                    $subQuery->orWhere('inspector_id', $userId)
                             ->orWhereNull('inspector_id');
                });
            })
            ->orderByRaw("CASE 
                WHEN inspection_status IS NOT NULL THEN 1
                ELSE 2 
            END")  // Ensure non-null inspection_status comes first
            ->orderBy('inspection_status', 'ASC')  // Secondary sort by inspection_status
            ->orderBy('id', 'ASC')  // Optional: Tertiary sort by ID
            ->get();
    
        // Log success
        Log::info('Farmers fetched successfully.');
    
        return response()->json($farmers);
    } catch (\Exception $e) {
        $message = $e->getMessage();
        $code = $e->getCode();
    
        // Log error
        Log::error('An error occurred while fetching farmers. Message: ' . $message, ['code' => $code]);
    
        return response()->json([
            'status' => false,
            'error' => [
                'message' => 'An error occurred',
                'code' => $code,
            ],
        ], 500);
    }
}


    public function searchFarmers(Request $request)
{
    $query = $request->input('query', '');

    $farmers = Farmer::where('first_name', 'LIKE', "%{$query}%")
            ->orWhere('middle_name', 'LIKE', "%{$query}%")
            ->orWhere('last_name', 'LIKE', "%{$query}%")
            ->orWhere('growerId', 'LIKE', "%{$query}%")
            ->orWhere('village', 'LIKE', "%{$query}%")
            ->get();

    return response()->json($farmers);
}
    
    public function updateField(Request $request)
    {
        try {
            // Validate incoming request
            $request->validate([
                'farmer_id' => 'required|exists:farmers,id',
            ]);

            // Find the farmer by ID
            $farmer = Farmer::find($request->farmer_id);

            // If the farmer doesn't exist, return an error response
            if (!$farmer) {
                Log::error('Farmer not found', ['farmer_id' => $request->farmer_id]);
                return response()->json([
                    'status' => 'false',
                    'message' => 'Farmer not found.'
                ], 404);
            }

            // Log the incoming data
            Log::info('Updating farmer', ['farmer_id' => $request->farmer_id, 'data' => $request->all()]);

            // Iterate through the request data and update the fields if necessary
            foreach ($request->all() as $field => $value) {
                if ($field != 'farmer_id' && $farmer->{$field} != $value) {
                    $farmer->{$field} = $value;
                }
            }

            // Save the farmer's updated data
            $farmer->save();

            // Log successful update
            Log::info('Farmer updated successfully', ['farmer_id' => $farmer->id]);

            return response()->json([
                'status' => 'true',
                'message' => 'Farmer details updated successfully.'
            ]);

        } catch (\Exception $e) {
            // Log the exception
            Log::error('Error updating farmer', [
                'farmer_id' => $request->farmer_id ?? null,
                'error' => $e->getMessage()
            ]);

            return response()->json([
                'status' => 'false',
                'message' => 'An error occurred while updating farmer details.',
                'error' => $e->getMessage()
            ], 500);
        }
    }
  
    public function getFarmer(Request $request,$id)
    {
        try {
            // Get the authenticated user's creator ID
            $creatorId = Auth::user()->current_company;
    
            // Fetch farmers created by the authenticated user
            $farmer = Farmer::where('company_id', $creatorId)->where('id', $id)->first();
    
            // Log success
            Log::info('Farmer fetched successfully.');
    
            return response()->json($farmer);
        } catch (\Exception $e) {
            $message = $e->getMessage();
            $code = $e->getCode();
    
            // Log error
            Log::error('An error occurred while fetching farmers. Message: ' . $message, ['code' => $code]);
    
            return response()->json([
                'status' => false,
                'error' => [
                    'message' => 'An error occurred',
                    'code' => $code,
                ],
            ], 500);
        }
    }

    }

