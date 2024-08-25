<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Inspectors;
use App\Models\Plan;
use App\Models\Company;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Role;
use Illuminate\Validation\Rule;
use App\Models\Farmer;
use Illuminate\Support\Facades\Crypt;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\InspectorsImport;
use Illuminate\Support\Facades\Log;
class InspectorController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if (\Auth::user()->can('Manage Inspectors')) {
            $inspectors = Inspectors::where('current_company', '=', \Auth::user()->current_company)->where('current_company', \Auth::user()->current_company)->get();
           
            return view('inspectors.index', compact('inspectors'));
        } else {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }

    public function importForm()
    {
        return view('inspectors.import');
    }


   public function import(Request $request)
{
    $rules = [
        'file' => 'required|mimes:csv,txt,xlsx',
    ];

    $validator = \Validator::make($request->all(), $rules);

    if ($validator->fails()) {
        $messages = $validator->getMessageBag();
        \Log::error('File validation failed: ' . $messages->first());
        return redirect()->back()->with('error', $messages->first());
    }

    try {
        // Import data using InspectorsImport class
        $inspectors = (new InspectorsImport())->toArray(request()->file('file'))[0];
        $totalInspectors = count($inspectors) - 1;
        $errorArray = [];

        for ($i = 1; $i <= count($inspectors) - 1; $i++) {
            $inspector = $inspectors[$i];
            $inspectorByEmail = Inspectors::where('email', $inspector[2])->first();

            $inspectorData = $inspectorByEmail ?? new Inspectors();

            $inspectorData->username = $inspector[0];
            $inspectorData->name = $inspector[1];
            $inspectorData->email = $inspector[2];
            $inspectorData->phone = $inspector[3];
            $inspectorData->id_number = $inspector[4];
            $inspectorData->current_company = \Auth::user()->company_id; // Set the company_id from the authenticated user

            if (empty($inspectorByEmail)) {
                $errorArray[] = $inspectorData;
            } else {
                $inspectorData->save();
            }
        }

        $errorRecord = [];

        if (empty($errorArray)) {
            $data['status'] = 'success';
            $data['msg'] = __('Record successfully imported');
        } else {
            $data['status'] = 'error';
            $data['msg'] = count($errorArray) . ' ' . __('Record import failed out of ' . $totalInspectors . ' records');

            foreach ($errorArray as $errorData) {
                $errorRecord[] = implode(',', $errorData->toArray());
            }

            \Session::put('errorArray', $errorRecord);
            \Log::error('Import failed for ' . count($errorArray) . ' records.');
            \Log::error('Failed records: ' . implode('; ', $errorRecord));
        }

        return redirect()->back()->with($data['status'], $data['msg']);

    } catch (\Exception $e) {
        \Log::error('An error occurred during import: ' . $e->getMessage());
        return redirect()->back()->with('error', __('An error occurred during import'));
    }
}

    
    public function create()
    {
        if (\Auth::user()->can('Create Inspectors')) {
            return view('inspectors.create');
        } else {
            return response()->json(['error' => __('Permission denied.')], 401);
        }
    }


    public function store(Request $request)
    {
        if (\Auth::user()->can('Create Inspectors')) {
            $validator = \Validator::make(
                $request->all(),
                [
                    'name' => 'required',
                    'username' => 'required',
                    'email' => [
                        'required',
                        Rule::unique('inspectors')->where(function ($query) {
                            return $query->where('created_by', \Auth::user()->id)->where('current_company', \Auth::user()->current_company);
                        })
                    ],
                ]
            );
            if ($validator->fails()) {
                $messages = $validator->getMessageBag();

                return redirect()->back()->with('error', $messages->first());
            }
            $default_language = DB::table('settings')->select('value')->where('name', 'owner_default_language')->first();
            $objUser    = \Auth::user()->creatorId();
            $objUser = Inspectors::find($objUser);
            $date = date("Y-m-d H:i:s");
           
                $user                       =  new Inspectors();
                $user->name                 =  $request['name'];
                                $user->username                 =  $request['username'];
                   $user->id_number                 =  $request['id_number'];
                $user->email                =  $request['email'];
                     $user->pin                =  $request['pin'];
                $user->phone                =  $request['phone'];
                $user->password             = Hash::make($request['password']);
                $user->type                 = 'Inspector';
                $user->lang                 = $default_language->value ?? 'en';
                $user->created_by           = \Auth::user()->creatorId();
                $user->email_verified_at    = $date;
                $user->current_company        = \Auth::user()->current_company;
                $user->is_enable_login      = !empty($request['password_switch']) && $request['password_switch'] == 'on' ? 1 : 0;
                $user->save();

               
            
            return redirect()->route('inspectors.index')->with('success', __('Inspector successfully created.') .((isset($msg)) ? '<br> <span class="text-danger">' . $msg . '</span>' : ''));
        } else {
            return response()->json(['error' => __('Permission denied.')], 401);
        }
    }
    public function updatePin(Request $request, $id)
    {
        if (\Auth::user()->can('Reset Password')) {
            // Validate the PIN and confirmation
            $validator = \Validator::make(
                $request->all(),
                [
                    'pin' => 'required|digits_between:1,4|confirmed',
                    'pin_confirmation' => 'required|digits_between:1,4',
                ]
            );
    
            if ($validator->fails()) {
                $messages = $validator->getMessageBag();
                return redirect()->back()->with('error', $messages->first());
            }
    
            $user = Inspectors::where('id', $id)->first();
    
            // Update the user's PIN without hashing
            $user->forceFill([
                'pin' => $request->pin,
            ])->save();
    
            return redirect()->back()->with(
                'success',
                'User PIN successfully updated.'
            );
        } else {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }
    
    public function show($id)

    {
        $id = Crypt::decrypt($id);
        if (\Auth::user()->can('Show Inspectors')) {
            $inspector  = Inspectors::find($id);
            $inspections = Farmer::where('inspector_id',$inspector->id)->get();
            $approved = Farmer::where('inspector_id','Approved')->count();
            $inspected = Farmer::where('inspection_status','Inspected')->count();
            $pending = Farmer::where('inspector_id','Not Inspected')->count();


            return view('inspectors.show', compact('inspector','inspections','approved','inspected','pending'));
        } else {
            return response()->json(['error' => __('Permission denied.')], 401);
        }
    }

    public function edit($id)
    {
        $id = Crypt::decrypt($id);

        if (\Auth::user()->can('Edit Inspectors')) {
            $inspector  = Inspectors::find($id);
            return view('inspectors.edit', compact('inspector'));
        } else {
            return response()->json(['error' => __('Permission denied.')], 401);
        }
    }


    public function update(Request $request, $id)
    {
        if (\Auth::user()->can('Edit Inspectors')) {
            $validator = \Validator::make(
                $request->all(),
                [
                    'name' => 'required',
                    'email' => 'unique:users,email,' . $id,
                ]
            );
            if ($validator->fails()) {
                $messages = $validator->getMessageBag();

                return redirect()->back()->with('error', $messages->first());
            }
            $inspector = Inspectors::findOrFail($id);

            $role          = Role::findById($request->role);
            $input         = $request->all();
            $input['type'] = $role->name;
            $inspector->fill($input)->save();

            return redirect()->route('inspectors.index')->with('success', 'Inspector successfully updated.');
        }
        else{
            return response()->json(['error' => __('Permission denied.')], 401);
        }
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        if (\Auth::user()->can('Delete User')) {
            $user = User::findOrFail($id);
            $user->delete();

            return redirect()->route('users.index')->with('success', 'User successfully deleted.');
        } else {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }
    public function reset($id)
    {
        if (\Auth::user()->can('Reset Password')) {
            $Id        = \Crypt::decrypt($id);

            $inspector = Inspectors::find($Id);


            return view('inspectors.reset', compact('inspector'));
        } else {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }
   
  }
    
   

