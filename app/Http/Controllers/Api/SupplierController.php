<?php

namespace App\Http\Controllers\Api;
use App\Models\Suppliers;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class SupplierController extends Controller
{
    public function storeSupplier(Request $request)
    {
        try {
            // Validate the incoming request data
            $validatedData = $request->validate([
                'name' => 'required|string',
                'gender' => 'required|string',
                'household_size' => 'required|integer',
                'dob' => 'required|date',
                'id_number' => 'required|string',
                'grower_id' => 'required|string',
                'phone_number' => 'required|string',
            ]);
    
            // Create a new Farmer instance and save it to the database
            $supplier = Suppliers::create($validatedData);
    
            return response()->json(['status' => true, 'message' => 'Supplier created successfully', 'data' => $supplier], 201);
        } catch (QueryException $e) {
            return response()->json(['status' => false, 'message' => 'Failed to create farmer', 'error' => $e->getMessage()], 500);
        } catch (\Exception $e) {
            return response()->json(['status' => false, 'message' => 'An unexpected error occurred', 'error' => $e->getMessage()], 500);
        }
    }
    public function getSuppliers(Request $request)
    {
        try {
      
            $farmers = Suppliers::where('company_id', \Auth::user()->current_company)->get();
    
            // Log success
            Log::info('Suppliers fetched successfully.');
    
            return response()->json($farmers);
        } catch (\Exception $e) {
            $message = $e->getMessage();
            $code = $e->getCode();
    
            // Log error
            Log::error('An error occurred while fetching suppliers. Message: ' . $message, ['code' => $code]);
    
            return response()->json([
                'status' => false,
                'error' => [
                    'message' => 'An error occurred',
                    'code' => $code,
                ],
            ], 500);
        }
    }
    
    
    public function updateField(Request $request)
    {
        try {
            // Validate incoming request
            $request->validate([
                'supplier_id' => 'required|exists:farmers,id',
            ]);

            $supplier = Suppliers::find($request->supplier_id);

            if (!$supplier) {
                Log::error('Supplier not found', ['supplier_id' => $request->supplier_id]);
                return response()->json([
                    'status' => 'false',
                    'message' => 'Supplier not found.'
                ], 404);
            }

            // Log the incoming data
            Log::info('Updating supplier', ['supplier_id' => $request->supplier_id, 'data' => $request->all()]);

            // Iterate through the request data and update the fields if necessary
            foreach ($request->all() as $field => $value) {
                if ($field != 'supplier_id' && $farmer->{$field} != $value) {
                    $farmer->{$field} = $value;
                }
            }

            // Save the farmer's updated data
            $farmer->save();

            // Log successful update
            Log::info('Supplier updated successfully', ['supplier_id' => $supplier->id]);

            return response()->json([
                'status' => 'true',
                'message' => 'Supplier details updated successfully.'
            ]);

        } catch (\Exception $e) {
            // Log the exception
            Log::error('Error updating supplier', [
                'supplier_id' => $request->supplier_id ?? null,
                'error' => $e->getMessage()
            ]);

            return response()->json([
                'status' => 'false',
                'message' => 'An error occurred while updating farmer details.',
                'error' => $e->getMessage()
            ], 500);
        }
    }
  
    public function getSupplier(Request $request,$id)
    {
        try {
            // Get the authenticated user's creator ID
            $creatorId = Auth::user()->current_company;
    
            // Fetch farmers created by the authenticated user
            $farmer = Suppliers::where('company_id', $creatorId)->where('id', $id)->first();
    
            // Log success
            Log::info('Supplier fetched successfully.');
    
            return response()->json($farmer);
        } catch (\Exception $e) {
            $message = $e->getMessage();
            $code = $e->getCode();
    
            // Log error
            Log::error('An error occurred while fetching supplier. Message: ' . $message, ['code' => $code]);
    
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

