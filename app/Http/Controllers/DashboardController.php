<?php

namespace App\Http\Controllers;


use App\Models\PlanOrder;

use App\Models\Farmer;
use App\Models\User;
use App\Models\Utility;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Nwidart\Modules\Facades\Module;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{

    
    public function index()
    {
      
       

        if (\Auth::check()) {
            if (\Auth::user()->can('Manage Dashboard')) {
                if (\Auth::user()->type != 'super admin') {
                    $farmers      = Farmer::where('created_by', '=', \Auth::user()->creatorId())->get();;

                    $countfarmers = count($farmers);
                  
                    $inspectedfarmers  = Farmer::where('inspection_status', '=', 'Complete')->where('company_id', '=', \Auth::user()->current_company)->count();
                    $notinspectedfarmers  = Farmer::where('inspection_status', '=', 'Incomplete')->where('company_id', '=', \Auth::user()->current_company)->count();

                    $compliantfarmers  = Farmer::where('inspection_status', '=', 'Compliant')->where('company_id', '=', \Auth::user()->current_company)->count();
                    $noncompliantfarmers  = Farmer::where('inspection_status', '=', 'Not Compliant')->where('company_id', '=', \Auth::user()->current_company)->count();

                    $noncompliantfarmers  = Farmer::where('inspection_status', '=', 'Not Compliant')->where('company_id', '=', \Auth::user()->current_company)->count();

                    $tenDaysAgo = Carbon::now()->subDays(10);

                    // Query to get recent inspections
                    $recentInspections = DB::table('farmers')
                        ->join('inspectors', 'farmers.inspector_id', '=', 'inspectors.id')
                        ->where('farmers.inspection_date', '>=', $tenDaysAgo)
                        ->select('farmers.*', 'inspectors.name as inspector_name')
                        ->limit(10)
                        ->get();
    
    
                        $today = Carbon::now()->toDateString();

                        $todayFarmers = Farmer::whereDate('created_at', $today)
                            ->where('created_by', \Auth::user()->creatorId())
                            ->get();
                        
                        $countTodayFarmers = count($todayFarmers);
                        
                        $todayInspectedFarmers = Farmer::whereDate('inspection_date', $today)
                            ->where('inspection_status', 'Complete')
                            ->where('company_id', \Auth::user()->current_company)
                            ->count();
                        
                        $countTodayApproved = Farmer::whereDate('updated_at', $today)
                            ->where('inspection_status', 'Approved')
                            ->where('company_id', \Auth::user()->current_company)
                            ->count();
                            $todayRejectedFarmers = Farmer::whereDate('updated_at', $today)
                            ->where('inspection_status', 'Rejected')
                            ->where('company_id', \Auth::user()->current_company)
                            ->count();
                        
                       
    
                    

                    return view('home', compact('compliantfarmers','todayInspectedFarmers', 'noncompliantfarmers', 'inspectedfarmers', 'notinspectedfarmers', 'countfarmers', 'farmers', 'todayRejectedFarmers',  'countTodayApproved'));
                } else {
                    $user                       = \Auth::user();
                    $user['total_user']         = $user->countCompany();
                    $user['total_paid_user']    = $user->countPaidCompany();
                    $user['total_orders']       = Order::total_orders();
                    $user['total_orders_price'] = Order::total_orders_price();
                    $user['total_plan_price']   = PlanOrder::total_plan_price();
                    $user['total_plan']         = Plan::total_plan();
                    $user['most_purchese_plan'] = (!empty(Plan::most_purchese_plan()) ? Plan::most_purchese_plan()->name : '-');
                    $chartData                  = $this->getOrderChart(['duration' => 'week']);

                    return view('home', compact('user', 'chartData'));
                }
            } else {
                return redirect()->back()->with('error', 'Permission denied.');
            }
        } else {
            
                    return redirect('login');
                
            
        }
    }

   

    public function profile()
    {
        $userDetail = \Auth::user();

        return view('profile', compact('userDetail'));
    }

    public function editprofile(Request $request)
    {
        $userDetail = \Auth::user();

        $user = User::findOrFail($userDetail['id']);
        $this->validate(
            $request,
            [
                'name' => 'required|max:120',
                'email' => 'required|email|unique:users,email,' . $userDetail['id'],
                // 'profile' => 'required',
            ]
        );

        if ($request->hasFile('profile')) {
            $image_size = $request->file('profile')->getSize();
            $result = Utility::updateStorageLimit(\Auth::user()->creatorId(), $image_size);
            if ($result == 1) {
                $filenameWithExt = $request->file('profile')->getClientOriginalName();
                $filename        = pathinfo($filenameWithExt, PATHINFO_FILENAME);
                $extension       = $request->file('profile')->getClientOriginalExtension();
                $fileNameToStore = $filename . '_' . time() . '.' . $extension;
                $settings = Utility::getStorageSetting();
                // $settings = Utility::settings();
                $dir        = 'uploads/profile';

                $image_path = $dir . $userDetail['avatar'];
                if (File::exists($image_path)) {
                    File::delete($image_path);
                }
                $path = Utility::upload_file($request, 'profile', $fileNameToStore, $dir, []);
                if ($path['flag'] == 1) {
                    $url = $path['url'];
                } else {
                    return redirect()->back()->with('error', __($path['msg']));
                }
            } else {
                return redirect()->back()->with('error', 'Plan storage limit is over so please upgrade the plan.');
            }
        }

        if (!empty($request->profile)) {
            $user['avatar'] = $fileNameToStore;
        }
        $user['name']  = $request['name'];
        $user['email'] = $request['email'];
        $user->save();


        return redirect()->back()->with('success', __('Profile successfully updated.'));
    }

    public function updatePassword(Request $request)
    {
        if (\Auth::Check()) {
            $request->validate(
                [
                    'current_password' => 'required',
                    'new_password' => 'required|min:6',
                    'confirm_password' => 'required|same:new_password',
                ]
            );
            $objUser          = \Auth::user();
            $request_data     = $request->All();
            $current_password = $objUser->password;
            if (Hash::check($request_data['current_password'], $current_password)) {
                $user_id            = \Auth::User()->id;
                $obj_user           = User::find($user_id);
                $obj_user->password = Hash::make($request_data['new_password']);;
                $obj_user->save();

                return redirect()->route('profile', $objUser->id)->with('success', __('Password successfully updated.'));
            } else {
                return redirect()->route('profile', $objUser->id)->with('error', __('Please enter correct current password.'));
            }
        } else {
            return redirect()->route('profile', \Auth::user()->id)->with('error', __('Something is wrong.'));
        }
    }

    
}
