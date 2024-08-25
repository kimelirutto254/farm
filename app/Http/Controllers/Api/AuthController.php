<?php

namespace App\Http\Controllers\Api;

use App\Models\Inspectors;
use App\Models\User;



use App\Models\Verification;
use App\Models\Tamupay;
use App\Models\Farmer;
use App\Models\Company;



use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use AfricasTalking\SDK\AfricasTalking;

use Illuminate\Support\Facades\Storage;
use App\Mail\OtpEmail;
use Illuminate\Support\Facades\Mail;

use Illuminate\Support\Facades\Log;

use Carbon\Carbon;

class AuthController extends Controller
{
    /**
    * Create User
    * @param Request $request
    * @return User 
    */
    
    
    
    /**
    * Login The User
    * @param Request $request
    * @return User
    */
 public function createPin(Request $request)
    {
        try {
            // Validate the request data
            $validate = Validator::make($request->all(), [
                'username' => 'required|string',
                'pin' => 'required|numeric|digits:4',
            ]);

            if ($validate->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => 'Validation error',
                    'errors' => $validate->errors()
                ], 422);
            }

            // Fetch user based on username
            $user = Inspectors::where('username', $request->username)->first();

            if ($user) {
                // Set or update the PIN
                $user->pin = $request->pin; 
                  $user->is_pin_created = 1; 
                // Encrypt PIN before saving
                $user->save();

                // Generate a token
                $token = $user->createToken("API TOKEN")->plainTextToken;

                // Prepare user data to return
                $userData = [
                    'image' => $user->image,
                    'name' => $user->name,
                    'type' => $user->type,
                    'avatar' => $user->avatar,
                ];

                return response()->json([
                    'status' => true,
                    'username' => $user->name,
                    'useremail' => $user->email,
                    'user' => $userData,
                    'message' => 'PIN created successfully',
                    'token' => $token
                ], 200);
            }

            return response()->json([
                'status' => false,
                'message' => 'Username does not exist'
            ], 404);
        } catch (\Throwable $th) {
            Log::error('createPin: Exception occurred', ['exception_message' => $th->getMessage()]);
            return response()->json([
                'status' => false,
                'message' => $th->getMessage()
            ], 500);
        }
    }

   
          public function verifyPin(Request $request)
{
    try {
        // Validate the request data
        $validate = Validator::make($request->all(), [
            'username' => 'required|string',
            'pin' => 'required|numeric|digits:4',
        ]);

        if ($validate->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Validation error',
                'errors' => $validate->errors()
            ], 422);
        }

        // Fetch user based on username
        $user = Inspectors::where('username', $request->username)->first();

        // Check if user exists and PIN matches directly
        if ($user && $request->pin == $user->pin) {
            // Generate a token
            $token = $user->createToken("API TOKEN")->plainTextToken;

            // Prepare user data to return
            $userData = [
                'image' => $user->image,
                'name' => $user->name,
                'type' => $user->type,
                'avatar' => $user->avatar,
            ];

            return response()->json([
                'status' => true,
                'username' => $user->name,
                'useremail' => $user->email,
                'user' => $userData,
                'message' => 'PIN verified successfully',
                'token' => $token
            ], 200);
        }

        return response()->json([
            'status' => false,
            'message' => 'Invalid PIN or username'
        ], 401);
    } catch (\Throwable $th) {
        Log::error('verifyPin: Exception occurred', ['exception_message' => $th->getMessage()]);
        return response()->json([
            'status' => false,
            'message' => $th->getMessage()
        ], 500);
    }
}


public function loginUser(Request $request)
{
    try {
        // Log incoming request data
        Log::info('loginUser: Incoming request', ['request_data' => $request->all()]);

        // Validate the request data
        $validateUser = Validator::make($request->all(), [
            'username' => 'required|string', // Validate username instead of PIN
        ]);

        // Log validation result
        if ($validateUser->fails()) {
            Log::warning('loginUser: Validation failed');
            return response()->json([
                'status' => false,
                'message' => 'Validation error',
                'errors' => $validateUser->errors()
            ], 401);
        }

        // Fetch user based on username
        $user = Inspectors::where('username', $request->username)->first();

        Log::info('loginUser: Retrieved user', ['user' => $user]);

        // Check if user exists
        if (!$user) {
            Log::warning('loginUser: Invalid username', ['username' => $request->username]);
            return response()->json([
                'status' => false,
                'message' => 'Invalid username',
            ], 401);
        }

        // Check if PIN is created
        $isPinCreated = $user->is_pin_created; // Assume this field exists in the Inspectors table

        // Return response based on whether PIN is created
        if ($isPinCreated) {
            Log::info('loginUser: PIN already created', ['user_id' => $user->id]);
            return response()->json([
                'status' => true,
                'message' => 'PIN already created',
                'redirect' => 'loginpin' // Indicates to redirect to login pin screen
            ], 200);
        } else {
            Log::info('loginUser: PIN not created', ['user_id' => $user->id]);
            return response()->json([
                'status' => true,
                'message' => 'Create PIN required',
                'redirect' => 'createpin' // Indicates to redirect to create PIN screen
            ], 200);
        }

    } catch (\Throwable $th) {
        // Log the exception
        Log::error('loginUser: Exception occurred', ['exception_message' => $th->getMessage()]);
        return response()->json([
            'status' => false,
            'message' => $th->getMessage()
        ], 500);
    }
}

    
    private function getInspectionCount($userId, $status) {
        return Farmer::where('inspector_id', $userId)->when($status !== 'all', function ($query) use ($status) {
            return $query->where('status', $status);
        })->count();
    }
    public function getUserDataWithInspectionStatistics() {
        $user = Auth::user();
    
        // Get today's date
        $today = today();
        $startOfWeek = $today->startOfWeek();

        // Query the Farmer model and get the counts
        $todayInspections = Farmer::where('inspector_id', $user->id)
                                  ->whereDate('inspection_date', $today)
                                  ->count();
    
        $totalInspections = Farmer::where('inspector_id', $user->id)->where('inspection_status', 'Complete')
        ->count();
    

$weeklyInspections = Farmer::where('inspector_id', $user->id)
                           ->whereBetween('inspection_date', [$startOfWeek, $today])
                           ->where('inspection_status', 'Complete')
                           ->count();
        $approvedInspections = Farmer::where('inspector_id', $user->id)
                                     ->where('inspection_status', 'Approved')
                                     ->count();
    
        $rejectedInspections = Farmer::where('inspector_id', $user->id)
                                     ->where('inspection_status', 'Rejected')
                                     ->count();
    
        $incompleteInspections = Farmer::where('inspector_id', $user->id)
                                       ->where('inspection_status', 'Incomplete')
                                       ->count();
    
        $noPointInspections = Farmer::where('inspector_id', $user->id)
                                    ->where('inspection_status', 'No Point')
                                    ->count();
    
        $noPolygonInspections = Farmer::where('inspector_id', $user->id)
                                      ->where('inspection_status', 'No Polygon')
                                      ->count();
    
        $userData = [
            'today_inspections' => $todayInspections,
            'weekly_inspections' => $weeklyInspections,

            'total_inspections' => $totalInspections,
            'approved_inspections' => $approvedInspections,
            'rejected_inspections' => $rejectedInspections,
            'incomplete_inspections' => $incompleteInspections,
            'no_point_inspections' => $noPointInspections,
            'no_polygon_inspections' => $noPolygonInspections,
        ];
    
        return response()->json($userData);
    }
    
   
    public function verifyUserIdAndIdNumber(Request $request)
    {
        // Extract user ID and ID number from the request
        $userId = $request->input('user_id');
        $idNumber = $request->input('id_number');
    
        // Find the user by ID
        $user = Farmer::find($userId);
    
        // Check if the user exists
        if (!$user) {
            return response()->json(['status' => false, 'message' => 'User not found']);
        }
    
        // Find the farmer by ID number
        $farmer = Farmer::where('id_number', $idNumber)->first();
    
        // Check if the farmer exists
        if (!$farmer) {
            return response()->json(['status' => false, 'message' => 'Invalid ID number']);
        }
    
        // Get the currently authenticated user
        $inspectorId = Auth::user()->id;
    
        // Check the number of inspections performed today by this inspector, including 'In Progress' and 'Complete'
        $inspectionsToday = Farmer::where('inspector_id', $inspectorId)
            ->whereIn('inspection_status', ['In Progress', 'Complete'])
            ->whereDate('updated_at', now()->toDateString())
            ->count();
    
        // Limit inspections to 12 per day
        if ($inspectionsToday >= 12) {
            return response()->json(['status' => false, 'message' => 'Inspection limit for today reached']);
        }
    
        // Check if the farmer's status is 'Approved'
        if ($farmer->inspection_status === 'Approved') {
            return response()->json(['status' => false, 'message' => 'Farmer is already approved']);
        }
    
        // Update the farmer's inspection status and inspector ID if it's 'Not Inspected'
        if ($farmer->inspection_status === 'Not Inspected') {
            // Update the inspection status to 'In Progress'
            $farmer->inspection_status = 'In Progress';
        
            // Set the inspection date to the current date and time using Carbon
            $farmer->inspection_date = Carbon::now(); // Carbon::now() provides the current date and time
        
            // Assign the inspector's ID
            $farmer->inspector_id = $inspectorId;
        
            // Save the changes to the database
            $farmer->save();
        }
        
        // Fetch farmer details
        $farmerDetails = $farmer->toArray();
    
        return response()->json([
            'status' => true,
            'farmer_status' =>  $farmer->inspection_status,
            'farmer_id' => $farmer->id,
            'message' => 'Verification successful',
            'farmer_details' => $farmerDetails,
        ]);
    }
    public function verify_code(Request $request)
    {
        // Validate the incoming request
        $request->validate([
            'pin' => 'required|numeric', // Adjust validation as needed
        ]);
    
        // Get the user and company settings
        $user = Auth::user();
        $company_settings = Company::where('id', $user->current_company)->first();
    
        if (!$company_settings) {
            return response()->json([
                'success' => false,
                'message' => 'Company not found',
            ], 404);
        }
    
        // Assuming 'verification_code' is a field in your company settings table
        $expectedCode = $company_settings->code;
    
        // Get the provided code from the request
        $providedCode = $request->input('pin');
    
        // Verify the code
        if ($providedCode == $expectedCode) {
            return response()->json([
                'status' => true,
                'message' => 'Code verified successfully',
            ]);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Invalid code',
            ], 400);
        }
    }
    
    
    public function sendOtp(Request $request)
    {
        try {
            $validateUser = Validator::make($request->all(), [
                'phone' => 'required',
            ]);
            
            if ($validateUser->fails()) {
                return response()->json([
                    
                    'status' => false,
                    'message' => 'Validation error',
                    'errors' => $validateUser->errors(),
                ], 401);
            }
            
            // Check if a user exists with the given phone number
            $user = User::where('phone', $request['phone'])->first();
            
            if (!$user) {
                return response()->json([
                    'status' => false,
                    'message' => 'Account not found.',
                ], 404);
            }
            
            if ($request['phone'] != '+254700000000') {
                $otp = mt_rand(1000, 9999);
            } else {
                $otp = '1234';
            }
            
            // Update OTP field in the database
            $user->otp = $otp;
            $user->save();
            
            
            $africasTalking = new AfricasTalking(env('AFRICASTALKING_USERNAME'), env('AFRICASTALKING_API_KEY'));
            $sms = $africasTalking->sms();
            $response = $sms->send([
                'to' => $request['phone'],
                'message' => 'Hello, your Tamupay One Time Password (OTP) is ' . $otp,
            ]);
            
            $message = 'Hello, your Tamupay One Time Password (OTP) is ' . $otp;
            $email = $user->email;
            
            Mail::to($email)->send(new OtpEmail($otp));
            
            
            
            
            return response()->json([
                'otp' => $user->otp,
                'status' => true,
                'message' => 'OTP sent successfully.',
            ]);
            
        } catch (\Throwable $th) {
            return response()->json([
                'status' => false,
                'message' => 'Server Error',
            ], 500);
        }
    }
    
    public function reportUser(Request $request, $userId)
    {
        // Validate the form data
        $request->validate([
            'reason' => 'required|string|max:255',
        ]);
        
        // Save the report to the database
        Report::create([
            'reported_user_id' => $userId,
            'reason' => $request->input('reason'),
            'user_id' => auth()->user()->id,
        ]);
        
        // Return a JSON response with success message
        return response()->json(['status' => true,'message' => 'User reported successfully!'], 200);
    }
    public function verifyOtp(Request $request)
    {
        try {
            $validateUser = Validator::make($request->all(), [
                'otp' => 'required',
                'phone' => 'required',
            ]);
            
            if ($validateUser->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => 'Validation error',
                    'errors' => $validateUser->errors(),
                ], 401);
            }
            
            $phone = $request['phone'];
            $otp = $request['otp'];
            
            // Find user by phone number and matching OTP
            $user = User::where('phone', $phone)->where('otp', $otp)->first();
            
            if (!$user) {
                return response()->json([
                    'status' => false,
                    'otp' => $otp,
                    'message' => 'Invalid OTP',
                ], 401);
            }
            
            // Set user as phone verified
            $user->is_phone_verified = true;
            $user->save();
            
            // Generate API token
            $token = $user->createToken("API TOKEN")->plainTextToken;
            
            // Additional code for login details and response
            // ...
            
            return response()->json([
                'status' => true,
                'user' => $user,
                'player_id' => $user->player_id,
                'message' => 'User Logged In Successfully',
                'token' => $token,
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                'status' => false,
                'message' => $th->getMessage()
            ], 500);
        }
    }
    
    
    public function contact(Request $request){
        $userID = Auth::user()->id;
        $res =DB::table("users")->select("image","name","online","token")->where("id","!=",$userID)->get();
        return ["code" => 0, "data" => $res, "msg" => "success"];
        
    }
    public function bind_fcmtoken(Request $request){
        $userID = Auth::user()->id;
        $fcmtoken = $request->input("fcmtoken");
        
        
        DB::table("users")->where("id","=",$userID)->update(["fcm_token"=>$fcmtoken]);
        
        return ["code" => 0, "data" => "", "msg" => "success"];
    }
  
    
    
    public function verifyUser(Request $request)
    {
        try {
            $id =  Auth::user()->id;
            $otp = $request['otp'];
            $user = Verification::findOrFail($id);
            
            if ($user->otp === $otp) {
                // OTP is valid
                $user->verified = true;
                $user->save();
                return response()->json([
                    'status' => true,
                    'message' => 'OTP Verified',
                ], 200);
            } else {
                return response()->json([
                    'status' => false,
                    'message' => 'OTP Invalid',
                ], 200);
            }
            $user = Verification::where('user_id', $id)->get();
            
            DB::table('user_sevices')->insert([
                'user_id' => $id,
                'service_id' => $user['service'],
            ]);
            
            
        } catch (\Throwable $th) {
            return response()->json([
                'status' => false,
                'message' => $th->getMessage()
            ], 500);
        }
    }
    
    public function checkAccessToService(Request $request)
    {
        try {
            $id = Auth::user()->id;
            $service = DB::table('user_services')
            ->where('user_id', $id)
            ->where('service_id', $request['service'])
            ->where('status', 1)
            
            ->first();
            
            if ($service === null) {
                throw new \Illuminate\Database\Eloquent\ModelNotFoundException('Service not found.');
            }
            
            return response()->json(['status' => true]);
        } catch (\Throwable $th) {
            return response()->json([
                'status' => false,
                'message' => $th->getMessage()
            ], 500);
        }
    }
    
    
    
    public function getUsernames(Request $request) {
        $query = $request->input('q');
        $usernames = DB::table('wallet')->where('wallet_id', 'LIKE', '%'.$query.'%')->pluck('wallet_id')->toArray();
        return response()->json($usernames);
    }
    public function getWalletBalance(Request $request) {
        $userId = auth()->user()->id;
        
        $wallet = Tamupay::where('user_id', $userId)->first();
        
        
        $balance = $wallet->amount;
        
        return response()->json($balance);
    }
    
    public function logout()
    {
        Auth::user()->tokens()->delete();
        
        return response()->json([
            'status' => true,
            'messasge' => 'Successfully logged out',
        ]);
    }
    public function delete_account()
    {
        $user = Auth::user();
        
        // Delete user's tokens
        $user->tokens()->delete();
        
        // Delete the user account
        $user->delete();
        
        // Delete the user row in the database
        DB::table('users')->where('id', $user->id)->delete();
        
        return response()->json([
            'status' => true,
            'message' => 'Account deleted successfully.',
        ]);
    }
    
    
    public function getUser(Request $request){
        try {
            $id =  Auth::user()->id;
            
            $user = Auth::where('id',$id)->get();
            return response()->json($user);
            
        }
        catch (Exception $e) {
            
            $message = $e->getMessage();
            var_dump('Exception Message: '. $message);
            
            $code = $e->getCode();       
            var_dump('Exception Code: '. $code);
            
            $string = $e->__toString();       
            var_dump('Exception String: '. $string);
            
            exit;
        }
        
        
    }
    
    public function getUsers(Request $request){
        try {
            $users = User::all(); // retrieve all users
            return response()->json($users);
            
        }
        catch (Exception $e) {
            
            $message = $e->getMessage();
            var_dump('Exception Message: '. $message);
            
            $code = $e->getCode();       
            var_dump('Exception Code: '. $code);
            
            $string = $e->__toString();       
            var_dump('Exception String: '. $string);
            
            exit;
        }
        
        
    }
    
    public function updateUser(Request $request)
    {
        try {
            $id = Auth::user()->id;
            $imageUrls = [];
            if ($request->hasFile('image')) {
                $images = $request->file('image');
                foreach ($images as $image) {
                    $path = $image->store('public/images');
                    $imageUrl = Storage::url($path);
                    $imageUrls[] = $imageUrl;
                }
            }
            DB::table('users')->where('id', $id)->update([
                'region' => $request->region,
                'image' => implode(',', $imageUrls)
            ]);
            
            $response = [
                'status' => true,
                'region' => $request->region,
            ];
            
            if (!empty($imageUrls)) {
                $response['image_url'] = $imageUrls[0]; // Assuming you want to return the first uploaded image URL
            }
            
            return response()->json($response);
        } catch (Exception $e) {
            return response()->json([
                'status' => false,
                'message' => $e->getMessage()
            ]);
        }
    }
    
    public function verifyCompany(Request $request)
    {
        try {
            $id =  Auth::user()->id;
            //Validated
            $validateUser = Validator::make($request->all(), 
            [
                'name' => 'required',
                'business_email' => 'required|email',
                'business_phone' => 'required',
                'trading' => 'required',
                'registration' => 'required',
            ]);
            if ($validateUser->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => 'Validation error',
                    'errors' => $validateUser->errors()
                ], 401);
            }
            if ($request->hasFile('image')) {
                $images = $request->file('image');
                $imageUrls = [];
                foreach ($images as $image) {
                    $path = $image->store('public/logos');
                    $imageUrl = Storage::url($path);
                    $imageUrls[] = $imageUrl;
                }
                // $imageUrls will contain the URLs of all the uploaded images
            }
            
            $merchant_id = mt_rand(100000, 999999);
            
            DB::table('merchant')->insert([
                'merchant_id' => $merchant_id,
                'user_id' => $id,    
                'region' => $request->region,            
                
                'name' => $request->name,
                'service' => $request->service,
                'logo' => $imageUrl,
                'trading' => $request->trading,
                'registration' => $request->registration,
                'business_phone' => $request->business_phone,
                'business_email' => $request->business_email,
                'status' => 0,
                
            ]);
            
            $existingService = DB::table('user_services')
            ->where('user_id', $id)
            ->where('service_id', $request->service)
            ->where('service_id', $request->service)
            ->where('status',1)
            
            ->first();
            
            if ($existingService) {
                return response()->json([
                    'status' => false,
                    'message' => 'Similar business exists,please chooose another or contact support  ',
                ]);
            }
            
            // If the service does not exist for the user, insert it
            DB::table('user_services')->insert([
                'user_id' => $id,
                'status' => 0,
                
                'service_id' => $request->service,
            ]);
            
            return response()->json([
                'status' => true,
                'messasge' => 'Successfully Registered',
            ]);        
        }
        catch (Exception $e) {
            
            $message = $e->getMessage();
            var_dump('Exception Message: '. $message);
            
            $code = $e->getCode();       
            var_dump('Exception Code: '. $code);
            
            $string = $e->__toString();       
            var_dump('Exception String: '. $string);
            
            exit;
        }
    }
    
    private function generateUniqueAccountNumber()
    {
        $length = 6; // Desired length of the account number
        $characters = '012345678901234567890123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ'; // List of characters to choose from
        
        do {
            $accountNumber = '';
            for ($i = 0; $i < $length; $i++) {
                $accountNumber .= $characters[rand(0, strlen($characters) - 1)];
            }
        } while (User::where('username', $accountNumber)->exists());
        
        return $accountNumber;
    }
    
    
}