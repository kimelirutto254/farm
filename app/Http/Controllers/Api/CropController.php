<?php

namespace App\Http\Controllers\Api;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use App\Models\Crop;

class CropController extends Controller
{
    public function index($id)
    {
        try {
            $crops = Crop::where('farmer_id', $id)->get(); // Retrieve crops by farmer_id

            // Return crops data
            return response()->json($crops);
        } catch (\Exception $e) {
            // Log the exception message
            \Log::error('Failed to fetch crops: ' . $e->getMessage());

            // Return an error response
            return response()->json([
                'status' => false,
                'message' => 'Failed to fetch crops'
            ], 500);
        }
    }
    public function store(Request $request)
    {
        try {
            // Validate the request data
            $validatedData = $request->validate([
                'crop' => 'required|string|max:255',
                'variety' => 'required|string|max:255',
                'age' => 'required|string',
                'population' => 'required|string',
                'farmer_id' => 'required|string',

            ]);

            // Create a new Crop record farmer_id
            $crop = new Crop();
            $crop->farmer_id = $validatedData['farmer_id'];
            $crop->crop = $validatedData['crop'];
            $crop->variety = $validatedData['variety'];
            $crop->age = $validatedData['age'];
            $crop->population = $validatedData['population'];
            $crop->save();

            // Return a successful response
            return response()->json([
                'status' => true,
                'message' => 'Crop added successfully'
            ], 201);
        } catch (\Exception $e) {
            // Log the exception message
            \Log::error('Failed to add crop: ' . $e->getMessage());

            // Return an error response
            return response()->json([
                'status' => false,
                'message' => 'Failed to add crop'
            ], 500);
        }
    }
    public function destroy($id)
    {
        try {
            $crop = Crop::findOrFail($id); // Find the crop by id

            // Delete the crop
            $crop->delete();

            // Return a successful response
            return response()->json([
                'status' => true,
                'message' => 'Crop deleted successfully'
            ], 200);
        } catch (\Exception $e) {
            // Log the exception message
            \Log::error('Failed to delete crop: ' . $e->getMessage());

            // Return an error response
            return response()->json([
                'status' => false,
                'message' => 'Failed to delete crop'
            ], 500);
        }
    }
}
