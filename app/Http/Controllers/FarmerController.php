<?php

namespace App\Http\Controllers;

use App\Models\Document;
use App\Models\Farmer;
use App\Models\FarmerDocument;
use App\Mail\UserCreate;
use App\Models\User;
use App\Models\Utility;
use File;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use App\Exports\InspectedFarmersExport;
use App\Exports\UninspectedFarmersExport;
use App\Exports\NonCompliantFarmersExport;
use App\Exports\CompliantFarmersExport;
use App\Models\Crop;




use App\Exports\FarmersExport;

use App\Models\LoginDetail;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

//use Faker\Provider\File;

class FarmerController extends Controller
{
    /**
    * Display a listing of the resource.
    *
    * @return \Illuminate\Http\Response
    */
    public function index()
    {
        
        if (\Auth::user()->can('Manage Farmers')) {
            
            $farmers = Farmer::get();
            
            
            
            return view('farmers.index', compact('farmers'));
        } else {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }
    public function inspected()
    {
        
        if (\Auth::user()->can('Manage Farmers')) {
            
            $farmers = Farmer::where('company_id', \Auth::user()->current_company)->where('inspection_status','Complete')->get();
            
            
            
            return view('farmers.inspected', compact('farmers'));
        } else {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }
    public function uninspected()
    {
        
        if (\Auth::user()->can('Manage Farmers')) {
            
            $farmers = Farmer::where('company_id', \Auth::user()->current_company)->where('inspection_status','Not Inspected')->get();
            
            
            
            return view('farmers.uninspected', compact('farmers'));
        } else {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }
    public function compliant()
    {
        
        if (\Auth::user()->can('Manage Farmers')) {
            
            $farmers = Farmer::where('created_by', \Auth::user()->creatorId())->where('inspection_status','Complete')->with([ 'user'])->get();
            
            
            
            return view('farmers.compliant', compact('farmers'));
        } else {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }
    public function noncompliant()
    {
        
        if (\Auth::user()->can('Manage Farmers')) {
            
            $farmers = Farmer::where('created_by', \Auth::user()->creatorId())->where('inspection_status','Incomplete')->with([ 'user'])->get();
            
            
            
            return view('farmers.non_compliant', compact('farmers'));
        } else {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }
    public function sanctioned()
    {
        
        if (\Auth::user()->can('Manage Farmers')) {
            
            $farmers = Farmer::where('created_by', \Auth::user()->creatorId())->where('sanctioned_status',1)->with([ 'user'])->get();
            
            
            
            return view('farmers.sanctioned', compact('farmers'));
        } else {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }
    public function create()
    {
        if (\Auth::user()->can('Create Farmers')) {
            $company_settings = Utility::settings();
            $farmers        = Farmer::where('created_by', \Auth::user()->creatorId())->get();
            
            return view('farmers.create', compact('farmers'));
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

                        'company_id' => \Auth::user()->company_id,
                        
                        'created_by' => \Auth::user()->creatorId(),
                        ]
                        
                    );
                    
                    
                    
                    return redirect()->route('farmer.index')->with('success', __('Farmer successfully created.'));
                    // return redirect()->route('employee.index')->with('success', __('Employee successfully created.') . ((isset($result) && $result != 1) ? '<br> <span class="text-danger">' . $result . '</span>' : ''));
                } else {
                    return redirect()->back()->with('error', __('Permission denied.'));
                }
            }
            
            public function edit($id)
            {
                $id = Crypt::decrypt($id);
                if (\Auth::user()->can('Edit Farmers')) {
                    $farmer     = Farmer::find($id);
                    
                    return view('farmers.edit', compact('farmer'));
                } else {
                    return redirect()->back()->with('error', __('Permission denied.'));
                }
            }
            
            public function update(Request $request, $id)
            {
                
                if (\Auth::user()->can('Edit Farmers')) {
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
                            
                            return redirect()->back()->with('error', $messages->first());
                        }
                        
                        $employee = Farmer::findOrFail($id);
                        
                        
                        
                        $employee = Farmer::findOrFail($id);
                        $input    = $request->all();
                        $employee->fill($input)->save();
                        
                        
                        
                        return redirect()->route('farmers.index')->with('success', __('Employee successfully updated.') . ((isset($result) && $result != 1) ? '<br> <span class="text-danger">' . $result . '</span>' : ''));
                        
                    } else {
                        return redirect()->back()->with('error', __('Permission denied.'));
                    }
                }
                
                public function destroy($id)
                {
                    if (Auth::user()->can('Delete Farmers')) {
                        $farmer      = Farmer::findOrFail($id);
                        
                        $farmer->delete();
                        
                        return redirect()->route('farmer.index')->with('success', 'Farmer successfully deleted.');
                    } else {
                        return redirect()->back()->with('error', __('Permission denied.'));
                    }
                }
                
                
                
                public function show($id)
                {
                    
                    if (\Auth::user()->can('Show Farmers')) {
                        try {
                            $fmId        = \Illuminate\Support\Facades\Crypt::decrypt($id);
                        } catch (\RuntimeException $e) {
                            return redirect()->back()->with('error', __('Farmer not avaliable'));
                        }

                        $farmer     = Farmer::find($fmId);
                        $crops     = Crop::where('farmer_id',$fmId)->get();

                        
                        $fmId        = Crypt::decrypt($id);
                        
                      
                        return view('farmers.show', compact('farmer','crops'));
                    } else {
                        return redirect()->back()->with('error', __('Permission denied.'));
                    }
                }
                
                
                
                function farmerNumber()
                {
                    $latest = Farmer::where('created_by', '=', \Auth::user()->creatorId())->latest('id')->first();
                    if (!$latest) {
                        return 1;
                    }
                    
                    return $latest->id + 1;
                }
                
                public function export()
                {
                    $name = 'farners_' . date('Y-m-d i:h:s');
                    $data = Excel::download(new FarmersExport(), $name . '.xlsx');
                    
                    
                    return $data;
                }
                public function export_inspected()
                {
                    $name = 'inspected-farners_' . date('Y-m-d i:h:s');
                    $data = Excel::download(new InspectedFarmersExport(), $name . '.xlsx');
                    
                    
                    return $data;
                }
                
                public function export_uninspected()
                {
                    $name = 'un-inspected-farners_' . date('Y-m-d i:h:s');
                    $data = Excel::download(new UninspectedFarmersExport(), $name . '.xlsx');
                    
                    
                    return $data;
                }
                public function export_compliant()
                {
                    $name = 'compliant-farners_' . date('Y-m-d i:h:s');
                    $data = Excel::download(new CompliantFarmersExport(), $name . '.xlsx');
                    
                    
                    return $data;
                }
                
                public function export_non_compliant()
                {
                    $name = 'non-compliant-farners_' . date('Y-m-d i:h:s');
                    $data = Excel::download(new NonCompliantFarmersExport(), $name . '.xlsx');
                    
                    
                    return $data;
                }
                
                public function importFile()
                {
                    return view('farmers.import');
                }
                
                public function import(Request $request)
                {
                    $rules = [
                        'file' => 'required|mimes:csv,txt',
                    ];
                    
                    $validator = \Validator::make($request->all(), $rules);
                    
                    if ($validator->fails()) {
                        $messages = $validator->getMessageBag();
                        
                        return redirect()->back()->with('error', $messages->first());
                    }
                    
                    $farmers = (new FarmersImport())->toArray(request()->file('file'))[0];
                    $totalFarmers = count($farmers) - 1;
                    $errorArray = [];
                    
                    for ($i = 1; $i <= count($farmers) - 1; $i++) {
                        
                        $farmer = $farmers[$i];
                        $farmerByEmail = Farmer::where('grower_id', $farmer[2])->first();
                        
                        $farmerData = $farmerByEmail ?? new Farmer();
                        
                        $farmerData->name = $farmer[0];
                        $farmerData->grower_id = $farmer[1];
                        $farmerData->national_id = $farmer[2];
                        $farmerData->gender = $farmer[3];
                        $farmerData->village = $farmer[4];
                        $farmerData->phone = $farmer[5]; // Corrected index
                        $farmerData->address = $farmer[6]; // Corrected index
                        
                        $farmerData->created_by = \Auth::user()->creatorId();
                        
                        if (empty($farmerByEmail)) {
                            $errorArray[] = $farmerData;
                        } else {
                            $farmerData->save();
                        }
                    }
                    
                    $errorRecord = [];
                    
                    if (empty($errorArray)) {
                        $data['status'] = 'success';
                        $data['msg'] = __('Record successfully imported');
                    } else {
                        $data['status'] = 'error';
                        $data['msg'] = count($errorArray) . ' ' . __('Record imported fail out of' . ' ' . $totalFarmers . ' ' . 'record');
                        
                        foreach ($errorArray as $errorData) {
                            $errorRecord[] = implode(',', $errorData->toArray());
                        }
                        
                        \Session::put('errorArray', $errorRecord);
                    }
                    
                    return redirect()->back()->with($data['status'], $data['msg']);
                }
                
                
                
                
                public function profile(Request $request)
                {
                    if (\Auth::user()->can('Manage Employee Profile')) {
                        $employees = Farmer::where('created_by', \Auth::user()->creatorId())->with([ 'user']);
                        if (!empty($request->branch)) {
                            $employees->where('branch_id', $request->branch);
                        }
                        if (!empty($request->department)) {
                            $employees->where('department_id', $request->department);
                        }
                        if (!empty($request->designation)) {
                            $employees->where('designation_id', $request->designation);
                        }
                        $employees = $employees->get();
                        
                        
                        return view('farmers.profile', compact('employees'));
                    } else {
                        return redirect()->back()->with('error', __('Permission denied.'));
                    }
                }
                
                
                public function lastLogin(Request $request)
                {
                    $users = User::where('created_by', \Auth::user()->creatorId())->get();
                    
                    $time = date_create($request->month);
                    $firstDayofMOnth = (date_format($time, 'Y-m-d'));
                    $lastDayofMonth =    \Carbon\Carbon::parse($request->month)->endOfMonth()->toDateString();
                    $objUser = \Auth::user();
                    
                    $usersList = User::where('created_by', '=', $objUser->creatorId())
                    ->whereNotIn('type', ['super admin', 'company'])->get()->pluck('name', 'id');
                    $usersList->prepend('All', '');
                    if ($request->month == null) {
                        $userdetails = DB::table('login_details')
                        ->join('users', 'login_details.user_id', '=', 'users.id')
                        ->select(DB::raw('login_details.*, users.id as user_id , users.name as user_name , users.email as user_email ,users.type as user_type'))
                        ->where(['login_details.created_by' => \Auth::user()->creatorId()])
                        ->whereMonth('date', date('m'))->whereYear('date', date('Y'));
                    } else {
                        $userdetails = DB::table('login_details')
                        ->join('users', 'login_details.user_id', '=', 'users.id')
                        ->select(DB::raw('login_details.*, users.id as user_id , users.name as user_name , users.email as user_email ,users.type as user_type'))
                        ->where(['login_details.created_by' => \Auth::user()->creatorId()]);
                    }
                    if (!empty($request->month)) {
                        $userdetails->where('date', '>=', $firstDayofMOnth);
                        $userdetails->where('date', '<=', $lastDayofMonth);
                    }
                    if (!empty($request->employee)) {
                        $userdetails->where(['user_id'  => $request->employee]);
                    }
                    $userdetails = $userdetails->get();
                    
                    return view('employee.lastLogin', compact('users', 'usersList', 'userdetails'));
                }
                
                
                
                
                
                
                
                
                
                public function view($id)
                {
                    $users = LoginDetail::find($id);
                    return view('employee.user_log', compact('users'));
                }
                
                public function logindestroy($id)
                {
                    $employee = LoginDetail::where('user_id', $id)->delete();
                    
                    return redirect()->back()->with('success', 'Employee successfully deleted.');
                }
            }
            