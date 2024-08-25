<?php

namespace App\Http\Controllers;

use App\Models\CustomDomainRequest;
use App\Models\Mail\TestMail;
use App\Models\Plan;
use App\Models\Company;
use App\Models\Utility;
use App\Models\Settings;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Artisan;
use App\Models\EmailTemplate;
use App\Models\EmailTemplateLang;
use App\Models\UserEmailTemplate;

class SettingController extends Controller
{
  
    public function index()
    {
        if (\Auth::user()->can('Manage Settings')) {
            $settings = Utility::settings();
            if (Auth::user()->type == 'super admin') {
                $admin_payment_setting = Utility::getAdminPaymentSetting();
                $EmailTemplates = EmailTemplate::all();
                return view('settings.index', compact('settings', 'admin_payment_setting', 'EmailTemplates'));
            } else {
                $user           = Auth::user();
                $company_settings = Company::where('id', $user->current_company)->first();
                

               
               }   return view('settings.index', compact('company_settings'));
                
        } else {
            return redirect()->back()->with('error', 'Permission denied.');
        }
    }
    

    public function saveBusinessSettings(Request $request)
    {
        $user = \Auth::user();
        if (\Auth::user()->type == 'super admin') {
            if ($request->dark_logo) {
                $logoName = 'logo-dark.png';
                $dir = 'uploads/logo/';

                $validation = [
                    'mimes:' . 'png',
                    'max:' . '20480',
                ];
                $path = Utility::upload_file($request, 'dark_logo', $logoName, $dir, $validation);
                if ($path['flag'] == 1) {

                    $dark_logo = $path['url'];
                } else {

                    return redirect()->back()->with('error', __($path['msg']));
                }
            }

            if ($request->light_logo) {
                $logoName = 'logo-light.png';
                $dir = 'uploads/logo/';
                $validation = [
                    'mimes:' . 'png',
                    'max:' . '20480',
                ];
                $path = Utility::upload_file($request, 'light_logo', $logoName, $dir, $validation);

                if ($path['flag'] == 1) {
                    $light_logo = $path['url'];
                } else {
                    return redirect()->back()->with('error', __($path['msg']));
                }
            }

            if ($request->favicon) {

                $favicon = 'favicon.png';
                $dir = 'uploads/logo/';
                $validation = [
                    'mimes:' . 'png',
                    'max:' . '20480',
                ];
                $path = Utility::upload_file($request, 'favicon', $favicon, $dir, $validation);
                if ($path['flag'] == 1) {
                    $favicon = $path['url'];
                } else {
                    return redirect()->back()->with('error', __($path['msg']));
                }
            }


            if (!empty($request->title_text) || !empty($request->verification_btn) || !empty($request->footer_text) || !empty($request->default_language) || !empty($request->display_landing_page)) {
                $settings = Utility::settings();
                $post = $request->all();

                if (!isset($request->display_landing_page)) {
                    $post['display_landing_page'] = 'off';
                }

                if (!isset($request->signup_button)) {
                    $post['signup_button'] = 'off';
                }
                if (!isset($request->verification_btn)) {
                    $post['verification_btn'] = 'off';
                }

                if (!isset($request->cust_darklayout)) {
                    $post['cust_darklayout'] = 'off';
                } else {
                    $post['cust_darklayout'] = 'on';
                }

                if (!isset($request->cust_theme_bg)) {
                    $post['cust_theme_bg'] = 'off';
                } else {
                    $post['cust_theme_bg'] = 'on';
                }

                if (isset($request->color) && $request->color_flag == 'false') {
                    $post['color'] = $request->color;
                } else {
                    $post['color'] = $request->custom_color;
                }
                $post['color_flag'] = $request->color_flag;

                if (!isset($request->SITE_RTL)) {
                    $post['SITE_RTL'] = 'off';
                } else {
                    $post['SITE_RTL'] = 'on';
                }

                unset($post['_token'], $post['dark_logo'], $post['light_logo'], $post['small_logo'], $post['favicon']);
                foreach ($post as $key => $data) {
                    $settings = Utility::settings();
                    if (in_array($key, array_keys($settings))) {
                        \DB::insert(
                            'insert into settings (`value`, `name`,`created_by`,`store_id`) values (?, ?, ?, ?) ON DUPLICATE KEY UPDATE `value` = VALUES(`value`) ',
                            [
                                $data,
                                $key,
                                $user->creatorId(),
                                '0',
                            ]
                        );
                    }
                }
            }

            if (isset($request->currency_symbol) && isset($request->currency)) {
                $request->validate(
                        [
                        'currency' => 'required|string|max:10',
                        'currency_symbol' => 'required|string|max:10',
                        ]
                    );

                $currency_data['currency_symbol'] = $request->currency_symbol;
                $currency_data['currency'] = $request->currency;

            } else {
                $currency_data['currency_symbol'] = '$';
                $currency_data['currency'] = 'USD';
            }
            foreach ($currency_data as $key => $data) {
                $arr = [
                    $data,
                    $key,
                    $user->creatorId(),
                ];
                \DB::insert(
                    'insert into admin_payment_settings (`value`, `name`,`created_by`) values (?, ?, ?) ON DUPLICATE KEY UPDATE `value` = VALUES(`value`) ', $arr
                );
            }

        } else if (\Auth::user()->type != 'supre admin') {

            $user = \Auth::user();
            $post = $request->all();

            if ($request->company_logo) {
                $image_size = $request->file('company_logo')->getSize();
                $result = Utility::updateStorageLimit(\Auth::user()->creatorId(), $image_size);
                if ($result == 1) {
                    $logoName     =  $user->id . '_company_logo.png';
                    $dir = 'uploads/logo/';

                    $validation = [
                        'mimes:' . 'png',
                        'max:' . '20480',
                    ];
                    $path = Utility::upload_file($request, 'company_logo', $logoName, $dir, $validation);
                    if ($path['flag'] == 1) {
                        $company_logo = $path['url'];
                    } else {
                        return redirect()->back()->with('error', __($path['msg']));
                    }
                    $company_logo = !empty($request->company_logo) ? $logoName : 'company_logo.png';
                    \DB::insert(
                        'insert into settings (`value`, `name`,`created_by`,`store_id`) values (?, ?, ? ,?) ON DUPLICATE KEY UPDATE `value` = VALUES(`value`) ',
                        [
                            $company_logo,
                            'company_logo',
                            \Auth::user()->id,
                            $user->current_store,
                        ]
                    );
                }
            }
            if ($request->company_light_logo) {
                $image_size = $request->file('company_light_logo')->getSize();
                $result = Utility::updateStorageLimit(\Auth::user()->creatorId(), $image_size);
                if ($result == 1) {
                    $logoName = $user->id . '_company_light_logo.png';
                    $dir = 'uploads/logo/';
                    $validation = [
                        'mimes:' . 'png',
                        'max:' . '20480',
                    ];
                    $path = Utility::upload_file($request, 'company_light_logo', $logoName, $dir, $validation);
                    if ($path['flag'] == 1) {
                        $company_light_logo = $path['url'];
                    } else {
                        return redirect()->back()->with('error', __($path['msg']));
                    }

                    // $path     = $request->file('light_logo')->storeAs('uploads/logo/', $logoName);
                    $company_light_logo = !empty($request->company_light_logo) ? $logoName : 'logo-light.png';

                    \DB::insert(
                        'insert into settings (`value`, `name`,`created_by`,`store_id`) values (?, ?, ? ,?) ON DUPLICATE KEY UPDATE `value` = VALUES(`value`) ',
                        [
                            $company_light_logo,
                            'company_light_logo',
                            \Auth::user()->id,
                            $user->current_store,
                        ]
                    );
                }
            }
            if ($request->company_favicon) {
                $image_size = $request->file('company_favicon')->getSize();
                $result = Utility::updateStorageLimit(\Auth::user()->creatorId(), $image_size);
                if ($result == 1) {
                    $favicon = $user->id . '_favicon.png';
                    $dir = 'uploads/logo/';
                    $validation = [
                        'mimes:' . 'png',
                        'max:' . '20480',
                    ];
                    $path = Utility::upload_file($request, 'company_favicon', $favicon, $dir, $validation);
                    if ($path['flag'] == 1) {
                        $company_favicon = $path['url'];
                    } else {
                        return redirect()->back()->with('error', __($path['msg']));
                    }
                    $company_favicon = !empty($request->favicon) ? $favicon : 'favicon.png';

                    \DB::insert(
                        'insert into settings (`value`, `name`,`created_by`,`store_id`) values (?, ?, ?, ?) ON DUPLICATE KEY UPDATE `value` = VALUES(`value`) ',
                        [
                            $company_favicon,
                            'company_favicon',
                            \Auth::user()->id,
                            $user->current_store,
                        ]
                    );
                }
            }

            if (isset($request->title_text) || isset($request->cust_darklayout) || isset($request->cust_theme_bg) || isset($request->color) || isset($request->custom_color)  || isset($request->timezone)) {

                if (!isset($request->cust_darklayout)) {
                    $post['cust_darklayout'] = 'off';
                } else {
                    $post['cust_darklayout'] = 'on';
                }


                if (!isset($request->cust_theme_bg)) {
                    $post['cust_theme_bg'] = 'off';
                } else {
                    $post['cust_theme_bg'] = 'on';
                }

                if (isset($request->color) && $request->color_flag == 'false') {
                    $post['color'] = $request->color;
                } else {
                    $post['color'] = $request->custom_color;
                }
                $post['color_flag'] = $request->color_flag;

                if (!isset($request->SITE_RTL)) {
                    $post['SITE_RTL'] = 'off';
                } else {
                    $post['SITE_RTL'] = 'on';
                }

                if(isset($request->timezone)){
                    $post['timezone'] = $request->timezone;
                }


                unset($post['_token'], $post['company_light_logo'], $post['company_logo'], $post['company_small_logo'], $post['company_favicon']);

                foreach ($post as $key => $data) {
                    if ($data != '') {
                        \DB::insert(
                            'insert into settings (`value`, `name`,`created_by`,`store_id`) values (?, ?, ?, ?) ON DUPLICATE KEY UPDATE `value` = VALUES(`value`)',
                            [
                                $data,
                                $key,
                                \Auth::user()->id,
                                $user->current_store,
                            ]
                        );
                    }
                }
            }
        } else {
            return redirect()->back()->with('error', __('Permission denied.'));
        }

        return redirect()->back()->with('success', __('Business setting successfully saved.') . ((isset($result) && $result != 1) ? '<br> <span class="text-danger">' . $result . '</span>' : ''));
    }

    public function saveCompanySettings(Request $request)
    {
        if (\Auth::user()->type == 'Owner') {
            $request->validate(
                [
                    'company_name' => 'required|string|max:50',
                    'company_email' => 'required',
                    'company_email_from_name' => 'required|string',
                ]
            );
            $post = $request->all();
            unset($post['_token']);

            foreach ($post as $key => $data) {
                $settings = Utility::settings();
                if (in_array($key, array_keys($settings))) {
                    \DB::insert(
                        'insert into settings (`value`, `name`,`created_by`) values (?, ?, ?) ON DUPLICATE KEY UPDATE `value` = VALUES(`value`) ',
                        [
                            $data,
                            $key,
                            \Auth::user()->current_store,
                        ]
                    );
                }
            }

            return redirect()->back()->with('success', __('Setting successfully updated.'));
        } else {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }

    public function saveEmailSettings(Request $request)
    {
        if (\Auth::user()->type == 'super admin') {
            $request->validate(
                [
                    'mail_driver' => 'required|string|max:50',
                    'mail_host' => 'required|string|max:50',
                    'mail_port' => 'required|string|max:50',
                    'mail_username' => 'required|string|max:50',
                    'mail_password' => 'required|string|max:50',
                    'mail_encryption' => 'required|string|max:50',
                    'mail_from_address' => 'required|string|max:50',
                    'mail_from_name' => 'required|string|max:50',
                ]
            );

            // $arrEnv = [
            //     'MAIL_DRIVER' => $request->mail_driver,
            //     'MAIL_HOST' => $request->mail_host,
            //     'MAIL_PORT' => $request->mail_port,
            //     'MAIL_USERNAME' => $request->mail_username,
            //     'MAIL_PASSWORD' => $request->mail_password,
            //     'MAIL_ENCRYPTION' => $request->mail_encryption,
            //     'MAIL_FROM_NAME' => $request->mail_from_name,
            //     'MAIL_FROM_ADDRESS' => $request->mail_from_address,
            // ];

            $post = [
                'mail_driver' => $request->mail_driver,
                'mail_host' => $request->mail_host,
                'mail_port' => $request->mail_port,
                'mail_username' => $request->mail_username,
                'mail_password' => $request->mail_password,
                'mail_encryption' => $request->mail_encryption,
                'mail_from_name' => $request->mail_from_name,
                'mail_from_address' => $request->mail_from_address,
            ];
            foreach ($post as $key => $data) {
                $settings = Utility::settings();
                if (in_array($key, array_keys($settings))) {
                    \DB::insert(
                        'insert into settings (`value`, `name`,`created_by`,`store_id`) values (?, ?, ?, ?) ON DUPLICATE KEY UPDATE `value` = VALUES(`value`) ', [
                            $data,
                            $key,
                            \Auth::user()->creatorId(),
                            '0',
                        ]
                    );
                }
            }

            // Artisan::call('config:cache');
            // Artisan::call('config:clear');
            // Utility::setEnvironmentValue($arrEnv);
            return redirect()->back()->with('success', __('Setting successfully updated.'));
        } else {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }

    public function saveSystemSettings(Request $request)
    {
        if (\Auth::user()->type == 'Owner') {
            $request->validate(
                [
                    'site_currency' => 'required',
                ]
            );
            $post = $request->all();
            unset($post['_token']);
            if (!isset($post['shipping_display'])) {
                $post['shipping_display'] = 'off';
            }
            foreach ($post as $key => $data) {
                $settings = Utility::settings();
                if (in_array($key, array_keys($settings))) {
                    \DB::insert(
                        'insert into settings (`value`, `name`,`created_by`,`created_at`,`updated_at`) values (?, ?, ?, ?, ?) ON DUPLICATE KEY UPDATE `value` = VALUES(`value`) ',
                        [
                            $data,
                            $key,
                            \Auth::user()->current_store,
                            date('Y-m-d H:i:s'),
                            date('Y-m-d H:i:s'),
                        ]
                    );
                }
            }

            return redirect()->back()->with('success', __('Setting successfully updated.'));
        } else {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }

    public function savePusherSettings(Request $request)
    {
        if (\Auth::user()->type == 'super admin') {
            $request->validate(
                [
                    'pusher_app_id' => 'required',
                    'pusher_app_key' => 'required',
                    'pusher_app_secret' => 'required',
                    'pusher_app_cluster' => 'required',
                ]
            );

            $arrEnvStripe = [
                'PUSHER_APP_ID' => $request->pusher_app_id,
                'PUSHER_APP_KEY' => $request->pusher_app_key,
                'PUSHER_APP_SECRET' => $request->pusher_app_secret,
                'PUSHER_APP_CLUSTER' => $request->pusher_app_cluster,
            ];

            Artisan::call('config:cache');
            Artisan::call('config:clear');
            $envStripe = Utility::setEnvironmentValue($arrEnvStripe);

            if ($envStripe) {
                return redirect()->back()->with('success', __('Pusher successfully updated.'));
            } else {
                return redirect()->back()->with('error', __('Something went wrong.'));
            }
        } else {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }

    public function saveCookieSettings(Request $request)
    {

        $validator = \Validator::make(
            $request->all(),
            [
                'cookie_title' => 'required',
                'cookie_description' => 'required',
                'strictly_cookie_title' => 'required',
                'strictly_cookie_description' => 'required',
                'more_information_description' => 'required',
                'contactus_url' => 'required',
            ]
        );

        $post = $request->all();

        unset($post['_token']);

        if ($request->enable_cookie) {
            $post['enable_cookie'] = 'on';
        } else {
            $post['enable_cookie'] = 'off';
        }
        if ($request->cookie_logging) {
            $post['cookie_logging'] = 'on';
        } else {
            $post['cookie_logging'] = 'off';
        }

        $post['cookie_title']            = $request->cookie_title;
        $post['cookie_description']            = $request->cookie_description;
        $post['strictly_cookie_title']            = $request->strictly_cookie_title;
        $post['strictly_cookie_description']            = $request->strictly_cookie_description;
        $post['more_information_description']            = $request->more_information_description;
        $post['contactus_url']            = $request->contactus_url;

        $settings = Utility::settings();
        foreach ($post as $key => $data) {

            if (in_array($key, array_keys($settings))) {
                \DB::insert(
                    'insert into settings (`value`, `name`,`created_by`,`created_at`,`updated_at`) values (?, ?, ?, ?, ?) ON DUPLICATE KEY UPDATE `value` = VALUES(`value`) ',
                    [
                        $data,
                        $key,
                        \Auth::user()->creatorId(),
                        date('Y-m-d H:i:s'),
                        date('Y-m-d H:i:s'),
                    ]
                );
            }
        }
        return redirect()->back()->with('success', 'Cookie setting successfully saved.');
    }
}