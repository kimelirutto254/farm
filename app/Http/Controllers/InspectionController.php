<?php

namespace App\Http\Controllers;

use App\Models\Document;
use App\Models\FarmerPolygon;
use App\Models\Inspection;
use App\Models\Farmer;

use App\Models\FarmerDocument;
use App\Models\Utility;
use App\Models\InspectionResponse;
use App\Models\FarmRequirement;
use App\Models\FarmRequirementLarge;



use File;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use App\Exports\FarmersExport;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use App\Models\GpsCoordinate;
use App\Models\Crop;




class InspectionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

        if (\Auth::user()->can('Manage Inspections')) {
          
                $inspections = Inspection::get();
      


            return view('inspections.index', compact('inspections'));
        } else {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }

    public function filter(Request $request)
    {
        $query = Farmer::query();
    
        // Filter by date range
        if ($request->has('date_start') && $request->has('date_end')) {
            $query->whereBetween('inspection_date', [$request->input('date_start'), $request->input('date_end')]);
        }
    
        // Filter by inspection status
        if ($request->has('inspection_status')) {
            $query->where('inspection_status', $request->input('inspection_status'));
        }
    
        // Filter by route
        if ($request->has('route')) {
            $query->where('route', 'like', '%' . $request->input('route') . '%');
        }
    
        $farmers = $query->get();
        
    
        // You may need to format the data according to your needs before sending it as a response
        return response()->json($farmers);
    }
    
    public function notinspected()
    {

        if (\Auth::user()->can('Manage Inspections')) {
          
                $inspections = Inspection::get();
      


            return view('inspections.notinspected', compact('inspections'));
        } else {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }

    public function create()
    {
        if (\Auth::user()->can('Create Farmers')) {
            $company_settings = Utility::settings();
            $employees        = User::where('created_by', \Auth::user()->creatorId())->get();
            $employeesId      = \Auth::user()->farmerIdFormat($this->farmerNumber());

            return view('farmers.create', compact('employees', 'employeesId'));
        } else {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }

    public function store(Request $request)
    {
        if (\Auth::user()->can('Create Farmers')) {
            $validator = \Validator::make(
                $request->all(),
                [
                    'first_name' => 'required',
                    'id_number' => 'required',
                    'gender' => 'required',
                    'grower_id' => 'required',
                ]
            );
            if ($validator->fails()) {
                $messages = $validator->getMessageBag();

                return redirect()->back()->withInput()->with('error', $messages->first());
            }

         
            $default_language = DB::table('settings')->select('value')->where('name', 'default_language')->where('created_by', \Auth::user()->creatorId())->first();

            // new company default language
            if ($default_language == null) {
                $default_language = DB::table('settings')->select('value')->where('name', 'default_language')->first();
            }

            $farmer = Farmer::create(
                [
                    'first_name' => $request['first_name'],
                    'middle_name' => $request['middle_name'],
                    'last_name' => $request['last_name'],
                    'dob' => $request['dob'],
                    'gender' => $request['gender'],
                    'grower_id' =>$request['grower_id'],
                    'region' => $request['region'],
                    'town' => $request['town'],
                    'id_number' => $request['id_number'],
                    'phone_number' => $request['phone_number'],
                    'route' => $request['route'],
                    'collection_center' => $request['collection_center'],
                    'nearest_center' => $request['nearest_center'],
                    'leased_land' => $request['leased_land'],
                    'created_by' => \Auth::user()->creatorId(),
                ]

            );

      
           
            return redirect()->route('farmer.index')->with('success', __('Farmer successfully created.'));
            // return redirect()->route('employee.index')->with('success', __('Employee successfully created.') . ((isset($result) && $result != 1) ? '<br> <span class="text-danger">' . $result . '</span>' : ''));
        } else {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }

    





    public function show($id)
    {
        if (\Auth::user()->can('Show Farmers')) {
            try {
                $fmId = Crypt::decrypt($id);
            } catch (\RuntimeException $e) {
                return redirect()->back()->with('error', __('Farmer not available'));
            }
    
            $farmer = Farmer::find($fmId);
    
            if (!$farmer) {
                return redirect()->back()->with('error', __('Farmer not found'));
            }
    
            $gpsCoordinates = FarmerPolygon::where('farmer_id', $fmId)->first();
            $inspectionResponses = InspectionResponse::where('farmer_id', $fmId)->get();
            $crops = Crop::where('farmer_id', $fmId)->get();
            $inspections = Inspection::where('farmer_id', $fmId)->get();
    
            // Fetch the polygon data
            $latitude = $gpsCoordinates->latitude;
            $longitude = $gpsCoordinates->longitude;

            $farmAreaPolygons = $gpsCoordinates->farm_area_polygons;
            $cropAreaPolygons = $gpsCoordinates->crop_area_polygons;
            $conservationAreaPolygons = $gpsCoordinates->conservation_area_polygons;
            $otherCropsAreaPolygons = $gpsCoordinates->other_crops_area_polygons;
            $residentialAreaPolygons = $gpsCoordinates->residential_area_polygons;
    
            // Fetch location data from residentialAreaPolygons
            $location = $residentialAreaPolygons;
    
            // Fetch Mapbox token and style from environment
            $mapboxToken = 'pk.eyJ1IjoiZGtydXR0bzIiLCJhIjoiY2xzOGt2dTZsMDBmNDJqcGpyanpwZjNtMiJ9.Q3_HknGeRmjlCsJKKFDbSQ';
            $mapboxStyle = 'mapbox://styles/dkrutto2/cls8lc5wg01et01r4gx88auaj';
    
            // Fetch responses that are 'yes' or no response
            $responses = DB::table('inspections')
                ->leftJoin('farm_requirements', 'inspections.code', '=', 'farm_requirements.requirement_code')
                ->select('inspections.id as inspection_id', 'inspections.code', 'inspections.response', 
                         'farm_requirements.chapter', 'farm_requirements.subchapter', 'farm_requirements.requirement')
                ->where('inspections.farmer_id', $fmId)
                ->where(function ($query) {
                    $query->where('inspections.response', '=', 'yes')
                          ->orWhereNull('inspections.response');
                })
                ->get();
    
            // Fetch non-compliances where response is 'no'
            $nonCompliances = DB::table('inspections')
                ->leftJoin('non_conformities', 'inspections.id', '=', 'non_conformities.inspection_id')
                ->leftJoin('farm_requirements', 'inspections.code', '=', 'farm_requirements.requirement_code')
                ->select('inspections.id as inspection_id', 'inspections.code', 'farm_requirements.requirement', 
                         'non_conformities.timeline', 'non_conformities.followup_date', 'non_conformities.status')
                ->where('inspections.farmer_id', $fmId)
                ->where('inspections.response', '=', 'no')
                ->get();
    
            return view('farmers.show_inspection', compact(
                'farmer', 'crops', 'responses', 'nonCompliances', 'gpsCoordinates', 'inspectionResponses',
                'farmAreaPolygons','latitude','longitude', 'cropAreaPolygons', 'conservationAreaPolygons',
                'otherCropsAreaPolygons', 'residentialAreaPolygons', 'location', 
                'mapboxToken', 'mapboxStyle'
            ));
        } else {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }
    


   
    public function noFarmPolygon($id)
    {
        // Logic for No Farm Polygon with specific ID
        $fmId = Crypt::decrypt($id);

        $farmer = Farmer::find($fmId); // Example of retrieving a farmer by ID
        $farmer->inspection_status = 'No Polygon';
        $farmer->save();
        $farmers = Farmer::all();
        return redirect()->route('farmers.index')->with('success', 'Inspection Status  Updated.');
    }

    public function noCenterCoordinate($id)
    {
        // Logic for No Center Coordinate with specific ID
        $fmId = Crypt::decrypt($id);

        $farmer = Farmer::find($fmId); // Example of retrieving a farmer by ID
        $farmer->inspection_status = 'No Center Coordinate';
        $farmer->save();
        return redirect()->route('farmers.index')->with('success', 'Inspection Status  Updated.');
    }

    public function continuousImprovement($id)
    {
        // Logic for Continuous Improvement with specific ID
        $fmId = Crypt::decrypt($id);

        $farmer = Farmer::find($fmId); // Example of retrieving a farmer by ID
        $farmer->inspection_status = 'Non Compliant';
        $farmer->save();
        return redirect()->route('farmers.index')->with('success', 'Inspection Status  Updated.');
    }

    public function approve($id)
    {
        $fmId = Crypt::decrypt($id);

        $farmer = Farmer::find($fmId); // Example of retrieving a farmer by ID
        $farmer->inspection_status = 'Approved';
        $farmer->save();
        return redirect()->route('farmers.index')->with('success', 'Inspection Status  Updated.');
    }

    public function reject($id)
    {
        $fmId = Crypt::decrypt($id);

        $farmer = Farmer::find($fmId); // Example of retrieving a farmer by ID
        $farmer->inspection_status = 'Approved';
        $farmer->save();
        return redirect()->route('farmers.index')->with('success', 'Inspection Status  Updated.');
    }

    public function editChecklist($id)
    {
        // Logic for Edit Checklist with specific ID
        $farmer = Farmer::find($id);
        return view('edit-checklist', compact('farmer'));
    }

    public function downloadReport($id)
    {
        // Logic for Download Report with specific ID
        
    }




}
