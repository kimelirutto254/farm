<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Languages;
use App\Models\User;
use App\Models\Plan;
use Spatie\Permission\Models\Role;
use App\Models\Company;
use App\Models\UserCompany;
use App\Models\Utility;
use App\Providers\RouteServiceProvider;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Support\Facades\Validator;

class RegisteredUserController extends Controller
{

    use RegistersUsers;


    protected $redirectTo = RouteServiceProvider::HOME;


    public function __construct()
    {
        $this->middleware('guest');
        $settings = Utility::settings();
        if($settings['recaptcha_module'] == 'yes')
        {
            config(['captcha.secret'  => $settings['google_recaptcha_secret']]);
            config(['captcha.sitekey' => $settings['google_recaptcha_key']]);
        }
    }

    // public function create()
    // {
    //     return view('auth.register');
    // }



    public function showRegistrationForm($ref = '',$lang = 'en')
    {
        if ($lang == '') {
            $lang = \App\Models\Utility::getValByName('default_language');
        }

        if (Utility::getValByName('signup_button') == 'on') {
            $language_name = Languages::where('code',$lang)->get()->first();
            \App::setLocale($lang);

            if ($ref == '') {
                $ref = 0;
            }
            
            
                return redirect()->route('register');
            

            return view('auth.register', compact('lang','en'));
        } else {
            return abort('404', 'Page not found');
        }
    }

    /**
     * Handle an incoming registration request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request)
    {
        $settings = Utility::settings();
        $lang = !empty($settings['default_language']) ? $settings['default_language'] : 'en';

      

        if(Utility::getValByName('verification_btn') == 'on'){

            $request->validate([
                'name' => 'required|string|max:255',
                'email' => 'required|string|email|max:255|unique:users',
                'password' => ['required', 'confirmed', 'min:8', Rules\Password::defaults()],
                'company_name' => 'required|string|max:255',


            ]);
            $settings = Utility::settings();
            if ($settings['recaptcha_module'] == 'yes') {
                $validation['g-recaptcha-response'] = 'required|captcha';
            } else {
                $validation = [];
            }
            $this->validate($request, $validation);
            $date = null;
            $user = User::create([
                'name'                  => $request->name,
                'email'                 => $request->email,
                'password'              => Hash::make($request->password),
                'type'                  => 'Owner',
                'lang'                  => !empty($settings['default_language']) ? $settings['default_language'] : 'en',
                'avatar'                => 'avatar.png',
                'email_verified_at'     => $date,
                'created_by'            => 1,
            ]);

            $role_r = Role::findByName('Owner');
            $user->assignRole($role_r);


            $objCompany = Company::create(
                [
                    'created_by' => $user->id,
                    'name' =>  $request->company_name,
                    'email' => $request->email,
                    'logo' => !empty($settings['logo']) ? $settings['logo'] : 'logo.png',
                    'lang' => !empty($settings['default_language']) ? $settings['default_language'] : 'en',

                ]
            );

        
            $objCompany->save();

            $user->current_company = $objCompany->id;
            $user->save();
            UserCompany::create(
                [
                    'user_id' => $user->id,
                    'company_id' => $objCompany->id,
                    'permission' => 'Owner',
                ]
            );

            // Auth::login($user);

            // event(new Registered($user));
            try {
                Utility::getSMTPDetails(1);

                event(new Registered($user));

                Auth::login($user);
            } catch (\Exception $e) {

                $user->delete();
                $objCompany->delete();

                return redirect('/register/' . $lang)->with('status', __('Email SMTP settings does not configure so please contact to your site admin.'));
            }

            return redirect(RouteServiceProvider::HOME);

        } else {

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => ['required', 'confirmed', 'min:8', Rules\Password::defaults()],
            'company_name' => 'required|string|max:255',


        ]);
        $settings = Utility::settings();
        if ($settings['recaptcha_module'] == 'yes') {
            $validation['g-recaptcha-response'] = 'required|captcha';
        } else {
            $validation = [];
        }
        $this->validate($request, $validation);
        if (Utility::getValByName('verification_btn') == 'off') {
            $date = date("Y-m-d H:i:s");
        }else{
            $date = null;
        }
        $user = User::create([
            'name'                  => $request->name,
            'email'                 => $request->email,
            'password'              => Hash::make($request->password),
            'type'                  => 'Owner',
            'lang'                  => !empty($settings['default_language']) ? $settings['default_language'] : 'en',
            'avatar'                => 'avatar.png',
         
            'email_verified_at'     => $date,
            'created_by'            => 1,
        ]);

        $role_r = Role::findByName('Owner');
        $user->assignRole($role_r);


        $objCompany = Company::create(
            [
                'created_by' => $user->id,
                'name' =>  $request->company_name,
                'email' => $request->email,
                'lang' => !empty($settings['default_language']) ? $settings['default_language'] : 'en',

            ]
        );

     
        $objCompany->save();

        $user->current_company = $objCompany->id;
        $user->save();
        UserCompany::create(
            [
                'user_id' => $user->id,
                'company_id' => $objCompany->id,
                'permission' => 'Owner',
            ]
        );

       
        try {
            $dArr = [
                'owner_name' => $user->name,
                'owner_email' => $user->email,
                'owner_password' => $request->password,
            ];

            // $resp = Utility::sendEmailTemplate('Owner Created', $user->email, $dArr, $objCompany);

            // event(new Registered($user));
            Auth::login($user);
        } catch (\Exception $e) {

            $user->delete();
            $objCompany->delete();

            return redirect('/register/' . $lang)->with('status', __('Email SMTP settings does not configure so please contact to your site admin.'));
        }

        return redirect(RouteServiceProvider::HOME);

        }
    }
}
