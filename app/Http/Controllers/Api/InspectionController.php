<?php

namespace App\Http\Controllers\Api;
use Illuminate\Support\Facades\Storage;

use App\Models\FarmRequirement;
use App\Models\FarmRequirementLarge;
use App\Models\Inspection;
use App\Models\Farmer;
use Illuminate\Support\Facades\Log;


use Illuminate\Support\Facades\DB;

use Illuminate\Support\Facades\Validator;


use App\Models\FarmerPolygon;
use App\Models\NonConformity;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

//use Faker\Provider\File;

class InspectionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
   

    
public function getRequirements(Request $request, $id)
{
    // Log the incoming request details
    Log::info('getRequirements small called', ['request' => $request->all(), 'farmer_id' => $id]);

    $farmer = Farmer::findOrFail($id); // Find the farmer by ID or fail if not found
    $farmer->farmer_type = 'large scale'; // Set the farmer type to 'large scale'
    $farmer->save(); // Save the changes to the database
    // Fetch all requirements ordered by 'requirement_code'
    $requirements = FarmRequirement::orderBy('requirement_code')->get();
    Log::info('Fetched requirements', ['requirements_count' => $requirements->count()]);

    // Fetch all related inspections for these requirements
    $inspections = Inspection::whereIn('code', $requirements->pluck('requirement_code'))
                             ->where('farmer_id', $id)
                             ->get()
                             ->map(function ($inspection) {
                                 return [
                                     'id' => $inspection->id,
                                     'requirement_code' => $inspection->field_code,
                                     'subchapter' => $inspection->subchapter,
                                     'chapter' => $inspection->chapter,
                                     'requirement' => $inspection->requirement,
                                     'farm_size' => $inspection->farm_size,
                                     'answer' => $inspection->answer,
                                     'response' => $inspection->response,
                                     'name' => $inspection->name,
                                     'workers' => $inspection->workers,
                                     'aquatic' => $inspection->aquatic,
                                     'pesticide' => $inspection->pesticide,
                                     'supplier' => $inspection->supplier,
                                     'created_at' => $inspection->created_at->toIso8601String(),
                                     'updated_at' => $inspection->updated_at->toIso8601String()
                                 ];
                             });
    Log::info('Fetched inspections', ['inspections_count' => $inspections->count()]);

    // Map through the requirements to include related inspections
    $requirementsWithInspections = $requirements->map(function ($requirement) use ($inspections) {
        // Filter inspections for this requirement
        $relatedInspections = $inspections->filter(function ($inspection) use ($requirement) {
            return $inspection['requirement_code'] === $requirement->requirement_code;
        });

        return [
            'requirement' => [
                'id' => $requirement->id,
                'requirement_code' => $requirement->requirement_code,
                // Add other fields as necessary
            ],
            'inspections' => $relatedInspections->isEmpty() ? null : $relatedInspections
        ];
    });

    // Log the requirements with inspections
    Log::info('Mapped requirements with inspections', [
        'requirements_with_inspections_count' => $requirementsWithInspections->count()
    ]);

    // Check if all inspections are empty
    $allInspectionsEmpty = $requirementsWithInspections->every(function ($item) {
        return is_null($item['inspections']);
    });

    if ($allInspectionsEmpty) {
        // Log that no inspections were found
        Log::info('All inspections are empty for the given requirements');

        // Return only requirements if no inspections are found
        return response()->json($requirements);
    }

    // Return the requirements with inspections as JSON
    return response()->json($requirementsWithInspections);
}
     
public function getRequirementsLarge(Request $request, $id)
{
    // Log the incoming request details (optional)
    Log::info('getRequirementsLarge called', ['request' => $request->all(), 'farmer_id' => $id]);

    // Update the farmer's type to 'large scale'
    try {
        $farmer = Farmer::findOrFail($id); // Find the farmer by ID or fail if not found
        $farmer->farmer_type = 'large scale'; // Set the farmer type to 'large scale'
        $farmer->save(); // Save the changes to the database

        // Log the successful update
        Log::info('Farmer type updated', ['farmer_id' => $id, 'new_type' => $farmer->farmer_type]);
    } catch (\Exception $e) {
        // Log any errors that occur during the update
        Log::error('Error updating farmer type', ['error' => $e->getMessage(), 'farmer_id' => $id]);
        return response()->json(['error' => 'Failed to update farmer type'], 500);
    }

    // Apply ordering before retrieving results
    $requirements = FarmRequirementLarge::orderBy('code')->get();

    // Log the retrieved requirements (optional)
    Log::info('Retrieved requirements', ['requirements' => $requirements]);

    return response()->json($requirements);
}

     public function updateStatus(Request $request)
    {
        // Validate the request
        $request->validate([
            'farmer_id' => 'required|integer|exists:farmers,id',
            'status' => 'required|string',
        ]);

        // Find the farmer by ID
        $farmer = Farmer::find($request->input('farmer_id'));

        if (!$farmer) {
            return response()->json(['status' => false, 'message' => 'Farmer not found.'], 404);
        }

        // Update the farmer's status
        $farmer->status = $request->input('status');
        $farmer->save();

        return response()->json(['status' => true, 'message' => 'Farmer status updated successfully.']);
    }
      public function savePolygonData(Request $request, $id)
      {
          try {
              // Log incoming request data
              Log::info('Received savePolygonData request', [
                  'request_id' => $request->id,
                  'farmer_id' => $id,
                  'request_data' => $request->all(),
              ]);
      
              // Validate the request data
              $validatedData = $request->validate([
                  'type' => 'required|string',
                  'coordinates' => 'required',

                
              ]);
      
              // Log validated data
              Log::info('Validated request data', [
                  'validated_data' => $validatedData,
              ]);
      
              // Determine the type of polygon, coordinates, and location
              $type = $validatedData['type'];
              $coordinates = $validatedData['coordinates'];

      
              // Log type and location
              Log::info('Processing data', [
                  'type' => $type,
                  'coordinates' => $coordinates,

              ]);
      
              // Create or update the FarmerPolygon based on farmer ID
              $polygon = FarmerPolygon::firstOrNew(['farmer_id' => $id]);
      
              // Log if we are updating or creating
              Log::info('Found or created FarmerPolygon', [
                  'farmer_id' => $id,
                  'polygon' => $polygon,
              ]);
      
              // Convert coordinates to JSON
            
      
              // Update polygon fields based on type
              switch ($type) {
                  case 'farm':
                      $polygon->farm_area_polygons = $request->coordinates;
                      break;
                  case 'certified_crops':
                      $polygon->crop_area_polygons = $coordinates;
                      break;
                  case 'conservation':
                      $polygon->conservation_area_polygons = $coordinates;
                      break;
                  case 'other_crops':
                      $polygon->other_crops_area_polygons = $coordinates;
                      break;
                  case 'residential_area':
                      $polygon->residential_area_polygons = $coordinates;
                      break;
              }
      
              // Log the field update
              Log::info('Updated polygon field based on type', [
                  'type' => $type,
                  'field' => "{$type}_area_polygons",
                  'json_coordinates' => $coordinates,
              ]);
      
           
      
              // Save the polygon
              $polygon->save();
              Log::info('Saved FarmerPolygon', [
                  'farmer_id' => $id,
                  'polygon' => $polygon,
              ]);
      
              return response()->json(['status' => true, 'message' => 'Data saved successfully'], 200);
      
          } catch (\Exception $e) {
              // Log the error details
              Log::error('Failed to save polygon data', [
                  'error' => $e->getMessage(),
                  'request_data' => $request->all(),
                  'farmer_id' => $id,
              ]);
      
              // Return a response with the error message
              return response()->json([
                  'message' => 'Failed to save data',
                  'error' => $e->getMessage()
              ], 500);
          }
      }
      public function savePointData(Request $request, $id)
      {
          try {
              // Log incoming request data
              Log::info('Received savePolygonData request', [
                  'request_id' => $request->id,
                  'farmer_id' => $id,
                  'request_data' => $request->all(),
              ]);
      
              // Validate the request data
              $validatedData = $request->validate([
                  'type' => 'required|string',
                  'latitude' => 'required',
                  'longitude' => 'required',
                
              ]);
      
              // Log validated data
              Log::info('Validated request data', [
                  'validated_data' => $validatedData,
              ]);
      
              // Determine the type of polygon, coordinates, and location
              $type = $validatedData['type'];
        

      
              // Log type and location
              Log::info('Processing data', [
                  'type' => $type,
                  'latitude' => $validatedData['latitude'],
                  'longitude' => $validatedData['longitude'],

                  

              ]);
      
              // Create or update the FarmerPolygon based on farmer ID
              $polygon = FarmerPolygon::firstOrNew(['farmer_id' => $id]);
      
              
      
              // Convert coordinates to JSON
            
      
             
              $polygon->latitude =  $validatedData['latitude'];
              $polygon->longitude =  $validatedData['longitude'];

      
      
           
      
              // Save the polygon
              $polygon->save();
              Log::info('Saved FarmerPolygon', [
                  'farmer_id' => $id,
                  'polygon' => $polygon,
              ]);
      
              return response()->json(['status' => true, 'message' => 'Data saved successfully'], 200);
      
          } catch (\Exception $e) {
              // Log the error details
              Log::error('Failed to save polygon data', [
                  'error' => $e->getMessage(),
                  'request_data' => $request->all(),
                  'farmer_id' => $id,
              ]);
      
              // Return a response with the error message
              return response()->json([
                  'message' => 'Failed to save data',
                  'error' => $e->getMessage()
              ], 500);
          }
      }
      
 
     
     
      public function store(Request $request)
      {
          // Log the incoming request data
          Log::info('Incoming inspection data:', $request->all());
      
          try {
              // Validate the request
              $data = $request->validate([
                  'farmer_id' => 'required|integer|exists:farmers,id',
                  'code' => 'nullable|string',
                  'response' => 'required|string',
                  'non_conformities' => 'nullable|string',
              ]);
      
              // Check for existing inspection
              $inspection = Inspection::where('farmer_id', $data['farmer_id'])
                  ->where('code', $data['code'])
                  ->first();
      
              if ($inspection) {
                  // Update existing inspection
                  $inspection->update([
                      'response' => $data['response'],
                  ]);
      
                  Log::info('Inspection updated successfully:', ['inspection_id' => $inspection->id]);
              } else {
                  // Create new inspection
                  $inspection = Inspection::create([
                      'code' => $data['code'],
                      'farmer_id' => $data['farmer_id'],
                      'response' => $data['response'],
                  ]);
      
                  Log::info('Inspection created successfully:', ['inspection_id' => $inspection->id]);
              }
      
              // Save or update non-conformities if response is 'No'
              if ($data['response'] == 'No' && isset($data['non_conformities'])) {
                  // Parse non_conformities string
                  $nonConformities = $this->parseNonConformities($data['non_conformities']);
                  
                  // Save each non-conformity detail
                  foreach ($nonConformities as $item) {
                      NonConformity::updateOrCreate(
                          ['inspection_id' => $inspection->id, 'timeline' => $item['timeline'], 'followup_date' => $item['followup_date'], 'response_details' => $item['response_details']], 
                         
                      );
                  }
      
                  Log::info('Non-conformities saved or updated successfully.', ['inspection_id' => $inspection->id]);
              }
      
              return response()->json(['status' => true, 'message' => 'Inspection saved successfully!', 'inspection' => $inspection], 201);
      
          } catch (\Exception $e) {
              // Log the exception message
              Log::error('Error saving inspection data:', ['error' => $e->getMessage()]);
      
              // Return a JSON response with error information
              return response()->json(['status' => false, 'message' => 'An error occurred while saving the inspection.'], 500);
          }
      }
      
      /**
       * Parse the non_conformities string to extract details.
       *
       * @param string $nonConformities
       * @return array
       */
      protected function parseNonConformities($nonConformities)
      {
          $details = [];
          $parts = explode(', ', $nonConformities);
          
          foreach ($parts as $part) {
              $part = trim($part);
              
              if (strpos($part, 'Timeline:') === 0) {
                  $details[] = ['timeline' => trim(str_replace('Timeline:', '', $part))];
              } elseif (strpos($part, 'Followup Date:') === 0) {
                  if (!empty($details)) {
                      $details[count($details) - 1]['followup_date'] = trim(str_replace('Followup Date:', '', $part));
                  }
              } elseif (strpos($part, 'Response Details:') === 0) {
                  if (!empty($details)) {
                      $details[count($details) - 1]['response_details'] = trim(str_replace('Response Details:', '', $part));
                  }
              }
          }
          
          return $details;
      }
      
       public function storeSignature(Request $request)
    {
        // Log the incoming request data
        Log::info('Incoming signature data:', $request->all());

        try {
            // Validate the request
            $validator = Validator::make($request->all(), [
                'signature' => 'required|string',
                'farmer_id' => 'required|integer|exists:farmers,id',
            ]);

            if ($validator->fails()) {
                return response()->json(['status' => false, 'message' => 'Validation failed.', 'errors' => $validator->errors()], 422);
            }

            // Find the inspection and associated farmer
            $farmer = Farmer::findOrFail($request->farmer_id);
            

            // Decode the base64-encoded signature
            $imageData = base64_decode($request->signature);

            // Define the file path
            $filePath = 'signatures/' . uniqid() . '.png';

            // Save the image to the storage
            Storage::disk('public')->put($filePath, $imageData);

            // Update the farmer's signature with the file path
            $farmer->signature = $filePath;
            $farmer->inspection_status = "Complete";

            $farmer->save();

            Log::info('Signature saved successfully.', ['farmer_id' => $farmer->id, 'file_path' => $filePath]);

            return response()->json(['status' => true, 'message' => 'Signature saved successfully!'], 200);

        } catch (\Exception $e) {
            // Log the exception message
            Log::error('Error saving signature data:', ['error' => $e->getMessage()]);

            // Return a JSON response with error information
            return response()->json(['status' => false, 'message' => 'An error occurred while saving the signature.'], 500);
        }
    }

    
}
