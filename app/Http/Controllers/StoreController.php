<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\Location;
use App\Models\Mail\ProdcutMail;
use App\Models\Order;
use App\Models\Plan;
use App\Models\PlanOrder;
use App\Models\Product;
use App\Models\ProductCategorie;
use App\Models\ProductCoupon;
use App\Models\ProductTax;
use App\Models\ProductVariantOption;
use App\Models\Product_images;
use App\Models\PurchasedProducts;
use App\Models\Shipping;
use App\Models\Store;
use App\Models\User;
use App\Models\UserDetail;
use App\Models\UserStore;
use App\Models\Utility;
use Spatie\Permission\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\CustomerExport;
use App\Exports\StoreExport;
use App\Models\CustomDomainRequest;
use App\Models\PixelFields;
use App\Models\Settings;
use Illuminate\Validation\Rule;

class StoreController extends Controller
{
    public function __construct()
    {
        if (Auth::check()) {
            $user = Auth::user()->current_store;
            $store = Store::where('id', $user)->first();
            \App::setLocale(isset($store->lang) ? $store->lang : 'en');
        } else {
            $slug = \Route::current()->parameter('slug');
            $store = Store::where('slug', $slug)->first();
            $store_lang = isset($store->lang) ? $store->lang : 'en';
            $sessoion_lang = session()->get('lang');
            $lang = !empty($sessoion_lang) ? $sessoion_lang : $store_lang;
            \App::setLocale($lang);
        }
    }

    public function index()
    {
        if (\Auth::user()->type == 'super admin') {
            $users = User::with('currentPlan')->select(
                [
                    'users.*',
                    'stores.is_store_enabled as store_display',
                ]
            )->join('stores', 'stores.id', '=', 'users.current_store')->where('users.created_by', \Auth::user()->creatorId())->where('users.type', '=', 'Owner')->groupBy('users.id')->get();
            $stores = Store::get();

            $user         = \Auth::user()->current_store;

            $store = Store::where('id', $user)->first();

            return view('admin_store.index', compact('stores', 'users', 'store'));
        }
    }

    public function export()
    {
        $name = 'stores_' . date('Y-m-d i:h:s');
        $data = Excel::download(new StoreExport(), $name . '.xlsx');
        return $data;
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if (\Auth::user()->can('Create Store')) {
            $user         = Auth::user();
            $store_id = $user->current_store;
            $store_settings = Store::where('id',  $store_id)->first();
            return view('admin_store.create', compact('store_settings'));
        } else {
            return redirect()->back()->with('error', 'Permission denied.');
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if (\Auth::user()->type == 'super admin') {

            do {
                $refferal_code = rand(100000 , 999999);
                $checkCode = User::where('type','Owner')->where('referral_code', $refferal_code)->get();
            } while ($checkCode->count());
            
            $validator = \Validator::make(
                $request->all(),
                [
                    'name' => 'required',
                    'email' => [
                        'required',
                        Rule::unique('users')->where(function ($query) {
                            return $query->where('created_by', \Auth::user()->id);
                        })
                    ],
                ]
            );
            if ($validator->fails()) {
                $messages = $validator->getMessageBag();

                return redirect()->back()->with('error', $messages->first());
            }

            $settings = Utility::settings();
            $date = date("Y-m-d H:i:s");
            $objUser = User::create(
                [
                    'name'              => $request['name'],
                    'email'             => $request['email'],
                    'password'          => !empty($request['password_switch']) && $request['password_switch'] == 'on' ? Hash::make($request['password']) : null,
                    'type'              => 'Owner',
                    'lang'              => !empty($settings['default_language']) ? $settings['default_language'] : 'en',
                    'avatar'            => 'avatar.png',
                    'plan'              => Plan::first()->id,
                    'email_verified_at' => $date,
                    'referral_code'     => $refferal_code,
                    'created_by'        => 1,
                    'is_active'         => !empty($request['password_switch']) && $request['password_switch'] == 'on' ? 1 : 0,
                    'is_enable_login'   => !empty($request['password_switch']) && $request['password_switch'] == 'on' ? 1 : 0,
                ]
            );
            $objUser->assignRole('Owner');

            $objStore = Store::create(
                [
                    'name' => $request['store_name'],
                    'email' => $request['email'],
                    'logo' => !empty($settings['logo']) ? $settings['logo'] : 'logo.png',
                    'invoice_logo' => !empty($settings['logo']) ? $settings['logo'] : 'invoice_logo.png',
                    'lang' => !empty($settings['default_language']) ? $settings['default_language'] : 'en',
                    'currency' => !empty($settings['currency_symbol']) ? $settings['currency_symbol'] : '$',
                    'currency_code' => !empty($settings['currency']) ? $settings['currency'] : 'USD',
                    'paypal_mode' => 'sandbox',
                    'created_by' => $objUser->id,
                ]
            );

            $objStore->enable_storelink = 'on';
            $objStore->theme_dir        = 'theme7';
            $objStore->store_theme = 'theme7-v1';
            $objStore->content = 'Hi,

Your order is confirmed & your order no. is {order_no}
Your order detail is:
Name : {customer_name}
Address : {billing_address} , {shipping_address}
~~~~~~Welcome to {store_name},~~~~~~~~~~
{item_variable}
~~~~~~~~~~~~~~~~
Qty Total : {qty_total}
Sub Total : {sub_total}
Discount Price : {discount_amount}
Shipping Price : {shipping_amount}
Tax : {item_tax}
Total : {item_total}
~~~~~~~~~~~~~~~~~~
To collect the order you need to show the receipt at the counter.
Thanks {store_name}';
            $objStore->item_variable = '{sku} : {quantity} x {product_name} - {variant_name} + {item_tax} = {item_total}';
            $objStore->save();
            $objUser->current_store = $objStore->id;
            $objUser->save();
            UserStore::create(
                [
                    'user_id' => $objUser->id,
                    'store_id' => $objStore->id,
                    'permission' => 'Owner',
                ]
            );
            // try {
                $dArr = [
                    'owner_name' => $objUser->name,
                    'owner_email' => $objUser->email,
                    'owner_password' => $request->password,
                ];

                $resp = Utility::sendEmailTemplate('Owner Created', $objUser->email, $dArr, $objStore);
            // } catch (\Exception $e) {

            //     $smtp_error = "<br><span class='text-danger'>" . __('E-Mail has been not sent due to SMTP configuration') . '</span>';
            // }

            return redirect()->back()->with('success', __('Successfully Created!' . ((isset($resp['error'])) ? '<br><span class="text-danger">' . $resp['error'] . '</span>' : '')));
            // return redirect()->back()->with('success', __('Successfully Created!')) . ((isset($smtp_error)) ? $smtp_error : '');

        } else {
            if (\Auth::user()->type != 'super admin') {

                if ($request->themefile == "theme1" || $request->themefile == "theme2" || $request->themefile == "theme3" || $request->themefile == "theme4" || $request->themefile == "theme5" || $request->themefile == "theme6") {
                    $theme_file = "theme_1_to_6";
                } else {
                    $theme_file = $request->themefile;
                }

                $user = \Auth::user();
                $total_store = $user->countStore();
                $creator = User::find($user->creatorId());
                $plan = Plan::find($creator->plan);
                $settings = Utility::settings();

                if ($total_store < $plan->max_stores || $plan->max_stores == -1) {
                    $objStore = Store::create(
                        [
                            'created_by' => \Auth::user()->id,
                            'name' => $request['store_name'],
                            'logo' => !empty($settings['logo']) ? $settings['logo'] : 'logo.png',
                            'invoice_logo' => !empty($settings['logo']) ? $settings['logo'] : 'invoice_logo.png',
                            'email' => $user->email,
                            'lang' => !empty($settings['default_language']) ? $settings['default_language'] : 'en',
                            'currency' => !empty($settings['currency_symbol']) ? $settings['currency_symbol'] : '$',
                            'currency_code' => !empty($settings['currency']) ? $settings['currency'] : 'USD',

                        ]
                    );
                    $objStore->enable_storelink = 'on';
                    if (isset($theme_file)) {
                        $objStore->theme_dir        = $theme_file;
                    } else {
                        $objStore->theme_dir        = 'theme7';
                    }
                    $objStore->store_theme = $request->theme_color;
                    $objStore->content = 'Hi,
Welcome to {store_name},
Your order is confirmed & your order no. is {order_no}
Your order detail is:
Name : {customer_name}
Address : {billing_address} , {shipping_address}
~~~~~~~~~~~~~~~~
{item_variable}
~~~~~~~~~~~~~~~~
Qty Total : {qty_total}
Sub Total : {sub_total}
Discount Price : {discount_amount}
Shipping Price : {shipping_amount}
Tax : {item_tax}
Total : {item_total}
~~~~~~~~~~~~~~~~~~
To collect the order you need to show the receipt at the counter.
Thanks {store_name}';
                    $objStore->item_variable = '{sku} : {quantity} x {product_name} - {variant_name} + {item_tax} = {item_total}';
                    $objStore->save();
                    \Auth::user()->current_store = $objStore->id;
                    \Auth::user()->save();
                    UserStore::create(
                        [
                            'user_id' => \Auth::user()->id,
                            'store_id' => $objStore->id,
                            'permission' => 'Owner',
                        ]
                    );

                    return redirect()->back()->with('success', __('Successfully Created!'));
                } else {
                    return redirect()->back()->with('error', __('Your Store limit is over, Please upgrade plan'));
                }
            }
        }
    }

    public function show(Store $store)
    {
        //
    }

    public function edit($id)
    {
        if (\Auth::user()->type == 'super admin') {
            $user = User::find($id);
            $user_store = UserStore::where('user_id', $id)->first();
            $store = Store::where('id', $user_store->store_id)->first();

            return view('admin_store.edit', compact('store', 'user'));
        }
    }

    public function update(Request $request, $id)
    {

        if (\Auth::user()->type == 'super admin') {
            $store = Store::find($id);
            $user_store = UserStore::where('store_id', $id)->first();
            $user = User::where('id', $user_store->user_id)->first();
            $validator = \Validator::make(
                $request->all(),
                [
                    'name' => 'required|max:120',
                    'store_name' => 'required|max:120',
                ]
            );
            if ($validator->fails()) {
                $messages = $validator->getMessageBag();

                return redirect()->back()->with('error', $messages->first());
            }

            $store['name'] = $request->store_name;
            $store['email'] = $request->email;
            $store->update();

            $user['name'] = $request->name;
            $user['email'] = $request->email;
            $user->update();

            return redirect()->back()->with('Success', __('Successfully Updated!'));
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \App\Store $store
     *
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        if (\Auth::user()->type == 'super admin') {
            if (isset($id) && $id != 2) {
                $user = User::find($id);
                $stores = Store::where('created_by', $user->id)->get();
                foreach ($stores as $store) {
                    UserStore::where('store_id', $store->id)->delete();
                    Order::where('user_id', $store->id)->delete();
                    ProductCategorie::where('store_id', $store->id)->delete();
                    ProductCoupon::where('store_id', $store->id)->delete();
                    ProductTax::where('store_id', $store->id)->delete();
                    // StoreThemeSettings::where('store_id', $store->id)->delete();
                    Shipping::where('store_id', $store->id)->delete();
                    Location::where('store_id', $store->id)->delete();
                    $products = Product::where('store_id', $store->id)->get();
                    $pro_img = new ProductController();
                    foreach ($products as $pro) {
                        $pro_img->fileDelete($pro->id);
                        ProductVariantOption::where('product_id', $pro->id)->delete();
                    }
                    UserDetail::where('store_id', $store->id)->delete();
                    PlanOrder::where('store_id', $store->id)->delete();
                    Product::where('store_id', $store->id)->delete();
                    Customer::where('store_id', $store->id)->delete();
                    Settings::where('store_id', $store->id)->delete();
                    Role::where('store_id', $store->id)->delete();
                    $store->delete();
                }
                User::where('created_by', $user->id)->delete();

                $user->delete();

                return redirect()->back()->with(
                    'success',
                    __('Store Deleted Successfully.')
                );
            }
        } else {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }

    public function customDomain()
    {
        if (\Auth::user()->type == 'super admin') {
            $serverName = str_replace(
                [
                    'http://',
                    'https://',
                ],
                '',
                env('APP_URL')
            );
            $serverIp = gethostbyname($serverName);

            if ($serverIp == $_SERVER['SERVER_ADDR']) {
                $serverIp;
            } else {
                $serverIp = request()->server('SERVER_ADDR');
            }
            $users = User::where('created_by', '=', \Auth::user()->creatorId())->where('type', '=', 'owner')->get();
            $stores = Store::where('enable_domain', 'on')->get();

            return view('admin_store.custom_domain', compact('users', 'stores', 'serverIp'));
        } else {
            return redirect()->back()->with('error', __('permission Denied'));
        }
    }

    public function subDomain()
    {
        if (\Auth::user()->type == 'super admin') {
            $serverName = str_replace(
                [
                    'http://',
                    'https://',
                ],
                '',
                env('APP_URL')
            );
            $serverIp = gethostbyname($serverName);

            if ($serverIp != $serverName) {
                $serverIp;
            } else {
                $serverIp = request()->server('SERVER_ADDR');
            }
            $users = User::where('created_by', '=', \Auth::user()->creatorId())->where('type', '=', 'owner')->get();
            $stores = Store::where('enable_subdomain', 'on')->get();

            return view('admin_store.subdomain', compact('users', 'stores', 'serverIp'));
        } else {
            return redirect()->back()->with('error', __('permission Denied'));
        }
    }

    public function ownerstoredestroy($id)
    {
        $user = Auth::user();
        $store = Store::find($id);
        $user_stores = UserStore::where('user_id', $user->id)->count();

        if ($user_stores > 1) {
            UserStore::where('store_id', $store->id)->delete();
            Order::where('user_id', $store->id)->delete();
            ProductCategorie::where('store_id', $store->id)->delete();
            ProductCoupon::where('store_id', $store->id)->delete();
            ProductTax::where('store_id', $store->id)->delete();
            Shipping::where('store_id', $store->id)->delete();
            Location::where('store_id', $store->id)->delete();
            $products = Product::where('store_id', $store->id)->get();
            $pro_img = new ProductController();
            foreach ($products as $pro) {
                $pro_img->fileDelete($pro->id);
                ProductVariantOption::where('product_id', $pro->id)->delete();
            }
            UserDetail::where('store_id', $store->id)->delete();
            PlanOrder::where('store_id', $store->id)->delete();
            Product::where('store_id', $store->id)->delete();
            Customer::where('store_id', $store->id)->delete();
            Settings::where('store_id', $store->id)->delete();
            Role::where('store_id', $store->id)->delete();
            User::where('current_store', $store->id)->where('created_by', $user->id)->delete();

            $store->delete();
            $userstore = UserStore::where('user_id', $user->id)->first();

            $user->current_store = $userstore->id;
            $user->save();

            return redirect()->route('dashboard');
        } else {
            return redirect()->back()->with('error', __('You have only one store'));
        }
    }

    public function savestoresetting(Request $request, $id)
    {
        $validator = \Validator::make(
            $request->all(),
            [
                'name' => 'required|max:120',
                // 'image' => 'required',

            ]
        );
        if ($request->enable_domain == 'enable_domain' || $request->domain_switch == 'on') {
            $validator = \Validator::make(
                $request->all(),
                [
                    'domains' => 'required',
                ]
            );
        }
        if ($request->enable_domain == 'enable_subdomain') {
            $validator = \Validator::make(
                $request->all(),
                [
                    'subdomain' => 'required',
                ]
            );
        }
        if ($validator->fails()) {
            $messages = $validator->getMessageBag();
            return redirect()->back()->with('error', $messages->first());
        }

        if (!empty($request->logo)) {
            $filenameWithExt = $request->file('logo')->getClientOriginalName();
            $filename = pathinfo($filenameWithExt, PATHINFO_FILENAME);
            $extension = $request->file('logo')->getClientOriginalExtension();
            $fileNameToStore = $filename . '_' . time() . '.' . $extension;
            $dir        = 'uploads/store_logo/';
            $validation = [
                'mimes:' . 'png',
                'max:' . '20480',
            ];
            $path = Utility::upload_file($request, 'logo', $fileNameToStore, $dir, $validation);

            if ($path['flag'] == 1) {
                $url = $path['url'];
            } else {
                return redirect()->back()->with('error', __($path['msg']));
            }
        }
        if (!empty($request->invoice_logo)) {
            $filenameWithExt = $request->file('invoice_logo')->getClientOriginalName();
            $filename = pathinfo($filenameWithExt, PATHINFO_FILENAME);
            $extension = $request->file('invoice_logo')->getClientOriginalExtension();
            $fileNameToStoreInvoice = 'invoice_logo' . '_' . $id . '.' . $extension;
            $dir        = 'uploads/store_logo/';
            $validation = [
                'mimes:' . 'png',
                'max:' . '20480',
            ];

            $path = Utility::upload_file($request, 'invoice_logo', $fileNameToStoreInvoice, $dir, $validation);

            if ($path['flag'] == 1) {
                $url = $path['url'];
            } else {
                return redirect()->back()->with('error', __($path['msg']));
            }
        }

        if ($request->enable_domain == 'enable_domain' && $request->domain_switch == 'on') {

            if (!empty($request->domains)) {
                $validator = \Validator::make(
                    $request->all(),
                    [
                        'domains' => 'required',
                    ]
                );
            }
            // Remove the http://, www., and slash(/) from the URL
            if (isset($request->domains)) {
                $input = $request->domains;
            } else {
                return redirect()->back()->with('error', 'Domian Is Required');
            }
            // If URI is like, eg. www.way2tutorial.com/
            $input = trim($input, '/');
            // If not have http:// or https:// then prepend it
            if (!preg_match('#^http(s)?://#', $input)) {
                $input = 'http://' . $input;
            }
            $urlParts = parse_url($input);
            // Remove www.
            $domain_name = preg_replace('/^www\./', '', $urlParts['host']);
            // Output way2tutorial.com
        }
        if ($request->enable_domain == 'enable_subdomain') {
            // Remove the http://, www., and slash(/) from the URL
            $input = env('APP_URL');

            // If URI is like, eg. www.way2tutorial.com/
            $input = trim($input, '/');
            // If not have http:// or https:// then prepend it
            if (!preg_match('#^http(s)?://#', $input)) {
                $input = 'http://' . $input;
            }

            $urlParts = parse_url($input);

            // Remove www.
            $subdomain_name = preg_replace('/^www\./', '', $urlParts['host']);
            // Output way2tutorial.com
            $subdomain_name = $request->subdomain . '.' . $subdomain_name;
        }

        $store = Store::find($id);

        if ($store->name != $request->name) {
            $data = ['name' => $request->name];
            $slug = Store::create($data);
            $store['slug'] = $slug->slug;
        }
        $store['name'] = $request->name;
        $store['email'] = $request->email;
        if ($request->enable_domain == 'enable_domain') {
            $store['domains'] = isset($domain_name) ? $domain_name : '';
        }

        $store['enable_storelink'] = ($request->enable_domain == 'enable_storelink' || empty($request->enable_domain)) ? 'on' : 'off';
        $store['enable_domain'] = ($request->enable_domain == 'enable_domain') ? 'on' : 'off';
        $store['enable_subdomain'] = ($request->enable_domain == 'enable_subdomain') ? 'on' : 'off';

        if ($request->enable_domain == 'enable_subdomain') {
            $store['subdomain'] = $subdomain_name;
        }
        if ($request->enable_domain == 'enable_domain' && $request->domain_switch == 'on') {
            $store['domain_switch'] = $request->domain_switch;
            $custom_domain_request = CustomDomainRequest::where('user_id', \Auth::user()->creatorId())->where('store_id', $id)->first();
            if ($custom_domain_request) {
                if($custom_domain_request->custom_domain != $domain_name){
                    $custom_domain_request->status        = 0;
                }
                $custom_domain_request->custom_domain = $domain_name;
                $custom_domain_request->save();
            } else {
                $custom_domain_requests                 = new CustomDomainRequest();
                $custom_domain_requests->user_id        = \Auth::user()->creatorId();
                $custom_domain_requests->store_id       = $id;
                $custom_domain_requests->custom_domain  = $domain_name;
                $custom_domain_requests->status         = 0;
                $custom_domain_requests->save();
            }
        } else {
            $store['domain_switch'] = 'off';
            $custom_domain_request = CustomDomainRequest::where('user_id', \Auth::user()->creatorId())->where('store_id', $id)->first();
            if($custom_domain_request)
            {
                $custom_domain_request->delete();
            }
        }

        if (!empty($request->meta_image)) {
            // if (asset(Storage::exists('uploads/meta_image/' . $store->meta_image))) {
            //     asset(Storage::delete('uploads/meta_image/' . $store->meta_image));
            // }
            $image_size = $request->file('meta_image')->getSize();
            $result = Utility::updateStorageLimit(\Auth::user()->creatorId(), $image_size);
            if ($result == 1) {
                $filenameWithExt   = $request->file('meta_image')->getClientOriginalName();
                $filename          = pathinfo($filenameWithExt, PATHINFO_FILENAME);
                $extension         = $request->file('meta_image')->getClientOriginalExtension();
                $metaimage = $filename . '_' . time() . '.' . $extension;
                $settings = Utility::getStorageSetting();
                // $settings = Utility::settings();

                if ($settings['storage_setting'] == 'local') {
                    $dir        = 'uploads/meta_image/';
                } else {
                    $dir        = 'uploads/meta_image/';
                }

                $meta_image = str_replace(' ', '_', $metaimage);
                $path = Utility::upload_file($request, 'meta_image', $meta_image, $dir, []);

                if ($path['flag'] == 1) {
                    $url = $path['url'];
                } else {
                    return redirect()->back()->with('error', __($path['msg']));
                }
            }
        }


        $store['about'] = $request->about;
        $store['tagline'] = $request->tagline;
        if ($request->store_default_language == 'ar' || $request->store_default_language == 'he') {
            $arrEnv = [
                'SITE_RTL' => 'on',
            ];
        } else {
            $arrEnv = [
                'SITE_RTL' => 'off',
            ];
        }
        Utility::setEnvironmentValue($arrEnv);
        $store['lang'] = $request->store_default_language;
        $store['is_checkout_login_required'] = $request->is_checkout_login_required ?? 'off';
        $store['storejs'] = $request->storejs;
        $store['whatsapp'] = $request->whatsapp;
        $store['facebook'] = $request->facebook;
        $store['instagram'] = $request->instagram;
        $store['twitter'] = $request->twitter;
        $store['youtube'] = $request->youtube;
        $store['footer_note'] = $request->footer_note;
        $store['enable_shipping'] = $request->enable_shipping ?? 'off';
        $store['address'] = $request->address;
        $store['city'] = $request->city;
        $store['state'] = $request->state;
        $store['zipcode'] = $request->zipcode;
        $store['meta_keyword'] = $request->meta_keyword;
        $store['meta_image'] = !empty($request->meta_image) ? $meta_image : '';
        $store['meta_description'] = $request->metadesc;
        $store['country'] = $request->country;
        $store['decimal_number'] = $request->decimal_number;

        if (!empty($fileNameToStore)) {
            $store['logo'] = $fileNameToStore;
        }

        if (!empty($fileNameToStoreInvoice)) {
            $store['invoice_logo'] = $fileNameToStoreInvoice;
        }

        $store['created_by'] = \Auth::user()->creatorId();

        $store->update();

        return redirect()->back()->with('success', __('Store successfully Update.') . ((isset($result) && $result != 1) ? '<br> <span class="text-danger">' . $result . '</span>' : ''));
    }

    public function pwasetting(Request $request, $id)
    {
        $company_favicon = Utility::getValByName('company_favicon');
        $store = Store::find($id);
        $store['enable_pwa_store'] = $request->pwa_store ?? 'off';
        if ($request->pwa_store == 'on') {


            $validator = \Validator::make(
                $request->all(),
                [
                    'pwa_app_title' => 'required|max:100',
                    'pwa_app_name' => 'required|max:50',
                    'pwa_app_background_color' => 'required|max:15',
                    'pwa_app_theme_color' => 'required|max:15',
                ]
            );

            if ($validator->fails()) {
                $messages = $validator->getMessageBag();

                return redirect()->back()->with('error', $messages->first());
            }



            $logo1 = Utility::get_file('uploads/logo/');
            $company_favicon = Utility::getValByName('company_favicon');

            if ($store['enable_storelink'] == 'on') {
                $start_url = env('APP_URL') . '/store/' . $store['slug'];
            } else if ($store['enable_domain'] == 'on') {
                $start_url = 'https://' . $store['domains'] . '/';
            } else {
                $start_url = 'https://' . $store['subdomain'] . '/';
            }

            $mainfest = '{
            "lang": "' . $store['lang'] . '",
            "name": "' . $request->pwa_app_title . '",
            "short_name": "' . $request->pwa_app_name . '",
            "start_url": "' . $start_url . '",
            "display": "standalone",
            "background_color": "' . $request->pwa_app_background_color . '",
            "theme_color": "' . $request->pwa_app_theme_color . '",
            "orientation": "portrait",
            "categories": [
                "shopping"
            ],
            "icons": [
                {
                    "src": "' . $logo1 . (isset($company_favicon) && !empty($company_favicon) ? $company_favicon : 'favicon.png') . '",
                    "sizes": "128x128",
                    "type": "image/png",
                    "purpose": "any"
                },
                {
                    "src": "' . $logo1 . (isset($company_favicon) && !empty($company_favicon) ? $company_favicon : 'favicon.png') . '",
                    "sizes": "144x144",
                    "type": "image/png",
                    "purpose": "any"
                },
                {
                    "src": "' . $logo1 . (isset($company_favicon) && !empty($company_favicon) ? $company_favicon : 'favicon.png') . '",
                    "sizes": "152x152",
                    "type": "image/png",
                    "purpose": "any"
                },
                {
                    "src": "' . $logo1 . (isset($company_favicon) && !empty($company_favicon) ? $company_favicon : 'favicon.png') . '",
                    "sizes": "192x192",
                    "type": "image/png",
                    "purpose": "any"
                },
                {
                    "src": "' . $logo1 . (isset($company_favicon) && !empty($company_favicon) ? $company_favicon : 'favicon.png') . '",
                    "sizes": "256x256",
                    "type": "image/png",
                    "purpose": "any"
                },
                {
                    "src": "' . $logo1 . (isset($company_favicon) && !empty($company_favicon) ? $company_favicon : 'favicon.png') . '",
                    "sizes": "512x512",
                    "type": "image/png",
                    "purpose": "any"
                },
                {
                    "src": "' . $logo1 . (isset($company_favicon) && !empty($company_favicon) ? $company_favicon : 'favicon.png') . '",
                    "sizes": "1024x1024",
                    "type": "image/png",
                    "purpose": "any"
                }
            ]
        }';


            if (!file_exists('storage/uploads/customer_app/store_' . $id)) {
                mkdir(storage_path('uploads/customer_app/store_' . $id), 0777, true);
            }


            if (!file_exists('storage/uploads/customer_app/store_' . $id . '/manifest.json')) {

                fopen('storage/uploads/customer_app/store_' . $id . "/manifest.json", "w");
            }

            \File::put('storage/uploads/customer_app/store_' . $id . '/manifest.json', $mainfest);
        }
        $store->update();
        return redirect()->back()->with('success', __('Store successfully Update.'));
    }

    public function storeSlug($slug, $view = 'grid')
    {
        try {
            $store = Store::where('slug', $slug)->where('is_store_enabled', '1')->first();
            if (!empty($store)) {
                $owner = User::find($store->created_by);
                $plan  = Plan::find($owner->plan);
                $productsDisplay = Product::where('store_id', $store->id)->get();
                foreach ($productsDisplay as $key => $product) {
                    $key = $key+1;
                    if ($plan->max_products != -1 && $key > $plan->max_products) {
                        $product['product_display'] = 'off';
                        $product->save();
                    } else {
                        $product['product_display'] = 'on';
                        $product->save();
                    }
                }

                if (!empty(\Auth::guard('customers')->user())) {
                    $user = \Auth::guard('customers')->user();
                    $store_settings = Store::where('id', $user->store_id)->first();
                } else {
                    $store_settings = Store::where('slug', $slug)->first();
                }

                if (!Auth::check()) {
                    visitor()->visit($slug);

                    Utility::Analytics($slug);
                }
                \App::setLocale(isset($store->lang) ? $store->lang : 'en');

                $order = Order::where('user_id', $store->id)->orderBy('id', 'desc')->first();
                $userstore = UserStore::where('store_id', $store->id)->first();
                $settings = \DB::table('settings')->where('name', 'company_favicon')->where('created_by', $userstore->user_id)->first();

                $locations = Location::where('store_id', $store->id)->get()->pluck('name', 'id');
                $locations->prepend('Select Location', 0);
                $shippings = Shipping::where('store_id', $store->id)->get();

                if (empty($store)) {
                    return redirect()->back()->with('error', __('Store not available'));
                }
                session(['slug' => $slug]);
                $cart = session()->get($slug);

                $categories = ProductCategorie::where('store_id', $userstore->store_id)->get()->pluck('name', 'id');
                $categories->prepend('All', 0);


                $products = [];
                foreach ($categories as $id => $category) {
                    $product = Product::where('store_id', $store->id)->where('product_display', 'on');
                    if ($id != 0) {
                        $product->whereRaw('FIND_IN_SET("' . $id . '", product_categorie)');
                    }
                    $product = $product->get();
                    $products[$category] = $product;
                }

                $total_item = 0;
                if (isset($cart['products'])) {
                    foreach ($cart['products'] as $item) {
                        if (isset($cart) && !empty($cart['products'])) {
                            $total_item = count($cart['products']);
                        } else {
                            $total_item = 0;
                        }
                    }
                }
                if (!empty($cart['customer'])) {
                    $cust_details = $cart['customer'];
                } else {
                    $cust_details = '';
                }

                if (!empty($cart)) {
                    $pro_cart = $cart;
                } else {
                    $pro_cart = '';
                }

                $encode_product = json_encode($pro_cart);
                $pro_qty = [];
                $pro_name = [];
                if (!empty($order)) {
                    $order_id = '%23' . str_pad($order->id + 1, 4, "100", STR_PAD_LEFT);
                } else {
                    $order_id = '%23' . str_pad(0 + 1, 4, "100", STR_PAD_LEFT);
                }

                if (!empty($pro_cart['products'])) {
                    foreach ($pro_cart['products'] as $key => $item) {
                        if ($item['variant_id'] == 0) {
                            $pro_qty[] = $item['quantity'] . ' x ' . $item['product_name'];
                        } else {
                            $pro_qty[] = $item['quantity'] . ' x ' . $item['product_name'] . ' - ' . $item['variant_name'];
                        }
                    }
                }

                $tax_name = [];
                $tax_price = [];
                $i = 0;
                if (isset($cart['products'])) {
                    $carts = $cart['products'];
                    foreach ($carts as $product) {
                        if ($product['variant_id'] != 0) {
                            foreach ($product['tax'] as $key => $taxs) {

                                if (!in_array($taxs['tax_name'], $tax_name)) {
                                    $tax_name[] = $taxs['tax_name'];
                                    $price = $product['variant_price'] * $product['quantity'] * $taxs['tax'] / 100;
                                    $tax_price[] = $price;
                                } else {
                                    $price = $product['variant_price'] * $product['quantity'] * $taxs['tax'] / 100;
                                    $tax_price[array_search($taxs['tax_name'], $tax_name)] += $price;
                                }
                            }
                        } else {

                            foreach ($product['tax'] as $key => $taxs) {
                                if (!in_array($taxs['tax_name'], $tax_name)) {
                                    $tax_name[] = $taxs['tax_name'];
                                    $price = $product['price'] * $product['quantity'] * $taxs['tax'] / 100;
                                    $tax_price[] = $price;
                                } else {
                                    $price = $product['price'] * $product['quantity'] * $taxs['tax'] / 100;
                                    $tax_price[array_search($taxs['tax_name'], $tax_name)] += $price;
                                }
                            }
                        }
                        $i++;
                    }
                }

                $taxArr['tax'] = $tax_name;
                $taxArr['rate'] = $tax_price;


                $url = 'https://api.whatsapp.com/send?phone=' . $store->whatsapp_number . '&text=Hi%2C%0AWelcome+to+%2A' . $store->name . '%2A%2C%0AYour+order+is+confirmed+%26+your+order+no.+is+' . $order_id . '%0AYour+order+detail+is%3A%0AName%20%3A%0AAddress%20%3A%20address%201%20and%20address%202%0A%0A%7E%7E%7E%7E%7E%7E%7E%7E%7E%7E%7E%7E%7E%7E%7E%7E%7E%7E%7E%7E%7E%7E%7E%7E%0A+' . join("+%2C%0A", $pro_qty) . '%0AOrder%20total%20%3A%0A+%0A+%0A%7E%7E%7E%7E%7E%7E%7E%7E%7E%7E%7E%7E%7E%7E%7E%7E%7E%7E%7E%7E%7E%7E%7E%7E%0ATo+collect+the+order+you+need+to+show+the+receipt+at+the+counter.%0A%0AThanks+' . $store->name . '%0A%0A';

                $pro_qty = join("+%2C%0A", $pro_qty);
                if (\Auth::check()) {
                    $store_payments = Utility::getPaymentSetting();
                } else {
                    $store_payments = Utility::getPaymentSetting($store->id);
                }
                // Create the pixel script
                $pixels = PixelFields::where('store_id', $store->id)->get();
                $pixelScript = [];
                foreach ($pixels as $pixel) {

                    if (!$pixel->disabled) {
                        // $pixelScript[] = pixelSourceCode($pixel['platform'], $pixel['pixel_id']);
                    }
                }
                return view('storefront.' . $store->theme_dir . '.index', compact('products', 'store_payments', 'encode_product', 'order_id', 'order', 'cust_details', 'store', 'settings', 'categories', 'total_item', 'pro_cart', 'url', 'view', 'locations', 'shippings', 'taxArr', 'pro_qty', 'store_settings', 'pixelScript'));
            } else {
                return abort('404', 'Page not found');
                // return redirect('/');
            }
        } catch (\Throwable $th) {
            return redirect()->back();
        }
    }

    public function UserLocation($slug, $location_id)
    {
        $store = Store::where('slug', $slug)->where('is_store_enabled', '1')->first();
        if (empty($store)) {
            return redirect()->route('store.slug', $slug);
        }
        $shippings = Shipping::where('store_id', $store->id)->whereRaw('FIND_IN_SET("' . $location_id . '", location_id)')->get()->toArray();

        return response()->json(
            [
                'code' => 200,
                'status' => 'Success',
                'shipping' => $shippings,
            ]
        );
    }

    public function UserShipping(Request $request, $slug, $shipping_id)
    {

        $store = Store::where('slug', $slug)->where('is_store_enabled', '1')->first();
        if (empty($store)) {
            return redirect()->route('store.slug', $slug);
        }
        $shippings = Shipping::where('store_id', $store->id)->where('id', $shipping_id)->first();
        $shipping_price = Utility::priceFormat($shippings->price);
        $pro_total_price = str_replace(' ', '', str_replace(',', '', str_replace($store->currency, '', $request->pro_total_price)));
        $total_price = $shippings->price + $pro_total_price;
        if (!empty($request->coupon)) {
            $coupons = ProductCoupon::where('code', strtoupper($request->coupon))->first();
            if (!empty($coupons)) {
                if ($coupons->enable_flat == 'on') {
                    $discount_value = $coupons->flat_discount;
                } else {
                    $discount_value = ($pro_total_price / 100) * $coupons->discount;
                }
            } else {
                $discount_value = 0;
            }
            $total_price = $total_price - $discount_value;
        }

        return response()->json(
            [
                'code' => 200,
                'status' => 'Success',
                'price' => $shipping_price,
                'total_price' => Utility::priceFormat($total_price),
            ]
        );
    }

    public function addToCart(Request $request, $product_id, $slug, $variant_id = 0)
    {
        if ($request->ajax()) {
            $store = Store::where('slug', $slug)->where('is_store_enabled', '1')->get();
            if (empty($store)) {
                return response()->json(
                    [
                        'code' => 404,
                        'status' => 'Error',
                        'error' => __('Page not found'),
                    ]
                );
            }
            $variant = ProductVariantOption::find($variant_id);

            if (empty($store)) {
                return redirect()->back()->with('error', __('Store not available'));
            }

            $product = Product::find($product_id);
            $cart = session()->get($slug);

            $quantity = $product->quantity;
            if ($variant_id > 0) {
                $quantity = $variant->quantity;
            }

            if (!empty($product->is_cover)) {
                $pro_img = $product->is_cover;
            } else {
                $pro_img = 'default_img.png';
            }

            $productquantity = $product->quantity;
            $i = 0;

            //            if(!$product && $quantity == 0)
            if ($quantity == 0) {
                return response()->json(
                    [
                        'code' => 404,
                        'status' => 'Error',
                        'error' => __('This product is out of stock!'),
                    ]
                );
            }

            $productname = $product->name;
            $productprice = $product->price != 0 ? $product->price : 0;
            $originalquantity = (int) $productquantity;

            //product count tax
            $taxes = Utility::tax($product->product_tax);
            $itemTaxes = [];
            $producttax = 0;

            if (!empty($taxes)) {
                foreach ($taxes as $tax) {
                    if (!empty($tax)) {
                        $producttax = Utility::taxRate($tax->rate, $product->price, 1);
                        $itemTax['tax_name'] = $tax->name;
                        $itemTax['tax'] = $tax->rate;
                        $itemTaxes[] = $itemTax;
                    }
                }
            }

            $subtotal = Utility::priceFormat($productprice + $producttax);

            if ($variant_id > 0) {
                $variant_itemTaxes = [];
                $variant_name = $variant->name;
                $variant_price = $variant->price;
                $originalvariantquantity = (int) $variant->quantity;
                //variant count tax
                $variant_taxes = Utility::tax($product->product_tax);
                $variant_producttax = 0;

                if (!empty($variant_taxes)) {
                    foreach ($variant_taxes as $variant_tax) {
                        if (!empty($variant_tax)) {
                            $variant_producttax = Utility::taxRate($variant_tax->rate, $variant_price, 1);
                            $itemTax['tax_name'] = $variant_tax->name;
                            $itemTax['tax'] = $variant_tax->rate;
                            $variant_itemTaxes[] = $itemTax;
                        }
                    }
                }
                $variant_subtotal = Utility::priceFormat($variant_price * $variant->quantity);
            }

            $time = time();
            // if cart is empty then this the first product
            if (!$cart || !$cart['products']) {

                if ($variant_id > 0) {
                    $cart['products'][$time] = [
                        "product_id" => $product->id,
                        "product_name" => $productname,
                        "image" => Utility::get_file('uploads/is_cover_image/' . $pro_img),
                        "quantity" => 1,
                        "price" => $productprice,
                        "id" => $product_id,
                        "downloadable_prodcut" => $product->downloadable_prodcut,
                        "tax" => $variant_itemTaxes,
                        "subtotal" => $subtotal,
                        "originalquantity" => $originalquantity,
                        "variant_name" => $variant_name,
                        "variant_price" => $variant_price,
                        "variant_qty" => $variant->quantity,
                        "variant_subtotal" => $variant_subtotal,
                        "originalvariantquantity" => $originalvariantquantity,
                        'variant_id' => $variant_id,
                        'cover_image' => $product->is_cover,
                    ];
                    //    dd( $cart['products'][$time]);
                } else if ($variant_id <= 0) {
                    $cart['products'][$time] = [
                        "product_id" => $product->id,
                        "product_name" => $productname,
                        "image" => Utility::get_file('uploads/is_cover_image/' . $pro_img),
                        "quantity" => 1,
                        "price" => $productprice,
                        "id" => $product_id,
                        "downloadable_prodcut" => $product->downloadable_prodcut,
                        "tax" => $itemTaxes,
                        "subtotal" => $subtotal,
                        "originalquantity" => $originalquantity,
                        'variant_id' => 0,
                        'cover_image' => $product->is_cover,
                    ];
                }

                session()->put($slug, $cart);

                return response()->json(
                    [
                        'code' => 200,
                        'status' => 'Success',
                        'success' => $productname . __('added to cart successfully!'),
                        'cart' => $cart['products'],
                        'item_count' => count($cart['products']),
                    ]
                );
            }

            // if cart not empty then check if this product exist then increment quantity
            if ($variant_id > 0) {
                $key = false;
                foreach ($cart['products'] as $k => $value) {
                    if ($variant_id == $value['variant_id']) {
                        $key = $k;
                    }
                }

                if ($key !== false && isset($cart['products'][$key]['variant_id']) && $cart['products'][$key]['variant_id'] != 0) {
                    if (isset($cart['products'][$key])) {
                        $cart['products'][$key]['quantity'] = $cart['products'][$key]['quantity'] + 1;
                        $cart['products'][$key]['variant_subtotal'] = $cart['products'][$key]['variant_price'] * $cart['products'][$key]['quantity'];

                        if ($originalvariantquantity < $cart['products'][$key]['quantity']) {
                            return response()->json(
                                [
                                    'code' => 404,
                                    'status' => 'Error',
                                    'error' => __('This product is out of stock!'),
                                ]
                            );
                        }

                        session()->put($slug, $cart);

                        return response()->json(
                            [
                                'code' => 200,
                                'status' => 'Success',
                                'success' => $productname . __('added to cart successfully!'),
                                'cart' => $cart['products'],
                                'item_count' => count($cart['products']),
                            ]
                        );
                    }
                }
            } else if ($variant_id <= 0) {
                $key = false;

                foreach ($cart['products'] as $k => $value) {
                    if ($product_id == $value['product_id']) {
                        $key = $k;
                    }
                }

                if ($key !== false) {
                    if (isset($cart['products'][$key])) {
                        $cart['products'][$key]['quantity'] = $cart['products'][$key]['quantity'] + 1;
                        $cart['products'][$key]['subtotal'] = $cart['products'][$key]['price'] * $cart['products'][$key]['quantity'];
                        if ($originalquantity < $cart['products'][$key]['quantity']) {
                            return response()->json(
                                [
                                    'code' => 404,
                                    'status' => 'Error',
                                    'error' => __('This product is out of stock!'),
                                ]
                            );
                        }

                        session()->put($slug, $cart);

                        return response()->json(
                            [
                                'code' => 200,
                                'status' => 'Success',
                                'success' => $productname . __('added to cart successfully!'),
                                'cart' => $cart['products'],
                                'item_count' => count($cart['products']),
                            ]
                        );
                    }
                }
            }

            // if item not exist in cart then add to cart with quantity = 1
            if ($variant_id > 0) {
                $cart['products'][$time] = [
                    "product_id" => $product->id,
                    "product_name" => $productname,
                    "image" => Storage::url('uploads/is_cover_image/' . $pro_img),
                    "quantity" => 1,
                    "price" => $productprice,
                    "id" => $product_id,
                    "downloadable_prodcut" => $product->downloadable_prodcut,
                    "tax" => $variant_itemTaxes,
                    "subtotal" => $subtotal,
                    "originalquantity" => $originalquantity,
                    "variant_name" => $variant->name,
                    "variant_price" => $variant->price,
                    "variant_qty" => $variant->quantity,
                    "variant_subtotal" => $variant_subtotal,
                    "originalvariantquantity" => $originalvariantquantity,
                    'variant_id' => $variant_id,
                    'cover_image' => $product->is_cover,
                ];
            } else if ($variant_id <= 0) {
                $cart['products'][$time] = [
                    "product_id" => $product->id,
                    "product_name" => $productname,
                    "image" => Storage::url('uploads/is_cover_image/' . $pro_img),
                    "quantity" => 1,
                    "price" => $productprice,
                    "id" => $product_id,
                    "downloadable_prodcut" => $product->downloadable_prodcut,
                    "tax" => $itemTaxes,
                    "subtotal" => $subtotal,
                    "originalquantity" => $originalquantity,
                    'variant_id' => 0,
                    'cover_image' => $product->is_cover,
                ];
            }

            session()->put($slug, $cart);

            return response()->json(
                [
                    'code' => 200,
                    'status' => 'Success',
                    'success' => $productname . __('added to cart successfully!'),
                    'cart' => $cart['products'],
                    'item_count' => count($cart['products']),
                ]
            );
        }
    }

    // public function productqty(Request $request, $product_id, $slug, $key = 0)
    // {
    //     $cart = session()->get($slug);
    //     if ($cart['products'][$key]['variant_id'] > 0 && $cart['products'][$key]['originalvariantquantity'] < $request->product_qty) {
    //         return response()->json(
    //             [
    //                 'code' => 404,
    //                 'status' => 'Error',
    //                 'error' => __('You can only purchese max') . ' ' . $cart['products'][$key]['originalvariantquantity'] . ' ' . __('product!'),
    //             ]
    //         );
    //     } else if ($cart['products'][$key]['originalquantity'] < $request->product_qty && $cart['products'][$key]['variant_id'] == 0) {
    //         return response()->json(
    //             [
    //                 'code' => 404,
    //                 'status' => 'Error',
    //                 'error' => __('You can only purchese max') . ' ' . $cart['products'][$key]['originalquantity'] . ' ' . __('product!'),
    //             ]
    //         );
    //     }
    //     if (isset($cart['products'][$key])) {
    //         $cart['products'][$key]['quantity'] = $request->product_qty;
    //         $cart['products'][$key]['id'] = $product_id;

    //         $subtotal = $cart['products'][$key]["price"] * $cart['products'][$key]["quantity"];

    //         $protax = $cart['products'][$key]["tax"];
    //         if ($protax != 0) {
    //             $taxs = 0;
    //             foreach ($protax as $tax) {
    //                 $taxs += ($subtotal * $tax['tax']) / 100;
    //             }
    //         } else {
    //             $taxs = 0;
    //             $taxs += ($subtotal * 0) / 100;
    //         }
    //         $cart['products'][$key]["subtotal"] = $subtotal + $taxs;

    //         session()->put($slug, $cart);

    //         return response()->json(
    //             [
    //                 'code' => 200,
    //                 'status' => 'Success',
    //                 'success' => $cart['products'][$key]["product_name"] . __('added to cart successfully!'),
    //                 'product' => $cart['products'],
    //                 'carttotal' => $cart['products'],
    //             ]
    //         );
    //     }
    // }


    public function productqty(Request $request, $product_id, $slug, $key = 0)
    {

        $cart = session()->get($slug);
        if ($cart['products'][$key]['variant_id'] > 0 && $cart['products'][$key]['originalvariantquantity'] < $request->product_qty) {
            return response()->json(
                [
                    'code' => 404,
                    'status' => 'Error',
                    'error' => __('You can only purchese max') . ' ' . $cart['products'][$key]['originalvariantquantity'] . ' ' . __('product!'),
                ]
            );
        } else if ($cart['products'][$key]['originalquantity'] < $request->product_qty && $cart['products'][$key]['variant_id'] == 0) {
            return response()->json(
                [
                    'code' => 404,
                    'status' => 'Error',
                    'error' => __('You can only purchese max') . ' ' . $cart['products'][$key]['originalquantity'] . ' ' . __('product!'),
                ]
            );
        }
        if (isset($cart['products'][$key])) {
            $cart['products'][$key]['quantity'] = $request->product_qty;
            $cart['products'][$key]['id'] = $product_id;
            foreach ($cart['products'] as $key => $value) {
                if ($cart['products'][$key]['variant_id'] != 0) {
                    $subtotal = $cart['products'][$key]["variant_price"] * $cart['products'][$key]["quantity"];
                    $protax = $cart['products'][$key]["tax"];
                    if ($protax != 0) {
                        $taxs = 0;
                        foreach ($protax as $tax) {
                            $taxs += ($subtotal * $tax['tax']) / 100;
                        }
                    } else {
                        $taxs = 0;
                        $taxs += ($subtotal * 0) / 100;
                    }
                    $cart['products'][$key]["total"] = $subtotal;

                    $cart['products'][$key]["variant_subtotal"] = $subtotal + $taxs;
                } else {
                    $subtotal = $cart['products'][$key]["price"] * $cart['products'][$key]["quantity"];
                    $protax = $cart['products'][$key]["tax"];
                    if ($protax != 0) {
                        $taxs = 0;
                        foreach ($protax as $tax) {
                            $taxs += ($subtotal * $tax['tax']) / 100;
                        }
                    } else {
                        $taxs = 0;
                        $taxs += ($subtotal * 0) / 100;
                    }

                    $cart['products'][$key]["total"] = $subtotal;

                    $cart['products'][$key]["subtotal"] = $subtotal + $taxs;
                }
                $cart['products'][$key]["total_tax"] = $protax;
            }
            session()->put($slug, $cart);
            return response()->json(
                [
                    'code' => 200,
                    'status' => 'Success',
                    'success' => $cart['products'][$key]["product_name"] . __('added to cart successfully!'),
                    'product' => $cart['products'],
                    'carttotal' => $cart['products'],
                ]
            );
        }
    }


    public function delete_cart_item($slug, $id, $variant_id = 0)
    {
        $cart = session()->get($slug);

        foreach ($cart['products'] as $key => $product) {
            if (($variant_id > 0 && $cart['products'][$key]['variant_id'] == $variant_id)) {
                unset($cart['products'][$key]);
            } else if ($cart['products'][$key]['product_id'] == $id && $variant_id == 0) {
                unset($cart['products'][$key]);
            }
        }

        $cart['products'] = array_values($cart['products']);

        session()->put($slug, $cart);

        return redirect()->back()->with('success', __('Item successfully Deleted.'));
    }

    public function complete($slug, $order_id)
    {
        $order = Order::where('id', Crypt::decrypt($order_id))->first();
        $store = Store::where('slug', $slug)->where('is_store_enabled', '1')->first();
        if (empty($store)) {
            return redirect()->back()->with('error', __('Store not available'));
        }

        $order_email = $order->email;
        $owner = User::find($store->created_by);

        $owner_email = $owner->email;
        $order_ids = $order->id;

        // if (isset($store->mail_driver) && !empty($store->mail_driver)) {
            $dArr = [
                'order_name' => $order->name,
            ];
            $resp1 = Utility::sendEmailTemplate('Order Created For Owner', $owner_email, $dArr, $store, $order_id);
            $resp = Utility::sendEmailTemplate('Order Created', $order_email, $dArr, $store, $order_id);
        // }

        return view('storefront.' . $store->theme_dir . '.complete', compact('slug', 'store', 'order_id', 'order'));
    }

    public function userorder($slug, $order_id)
    {
        $id = Crypt::decrypt($order_id);
        //$id = $order_id;
        $store = Store::where('slug', $slug)->where('is_store_enabled', '1')->first();
        if (empty($store)) {
            return redirect()->back()->with('error', __('Store not available'));
        }
        $order = Order::where('id', $id)->first();

        if (!empty($order->coupon_json)) {
            $coupon = json_decode($order->coupon_json);
        }
        if (!empty($order->discount_price)) {
            $discount_price = $order->discount_price;
        } else {
            $discount_price = '';
        }

        if (!empty($order->shipping_data)) {
            $shipping_data = json_decode($order->shipping_data);
            $location_data = Location::where('id', $shipping_data->location_id)->first();
        } else {
            $shipping_data = '';
            $location_data = '';
        }

        $user_details = UserDetail::where('id', $order->user_address_id)->first();
        $order_products = !empty($order->product) ? json_decode($order->product) : [];

        $sub_total = 0;

        if (!empty($order_products)) {
            $grand_total = 0;
            $discount_value = 0;
            $final_taxs = 0;

            foreach ($order_products->products as $product) {
                if (isset($product->variant_id) && $product->variant_id != 0) {
                    $total_taxs = 0;
                    if (!empty($product->tax)) {
                        foreach ($product->tax as $tax) {
                            $sub_tax = ($product->variant_price * $product->quantity * $tax->tax) / 100;
                            $total_taxs += $sub_tax;
                            $final_taxs += $sub_tax;
                        }
                    } else {
                        $total_taxs = 0;
                    }

                    $totalprice = $product->variant_price * $product->quantity + $total_taxs;
                    $subtotal1 = $product->variant_price * $product->quantity;

                    $sub_total += $subtotal1;
                    $grand_total += $totalprice;
                } else {
                    if (!empty($product->tax)) {
                        $total_taxs = 0;
                        foreach ($product->tax as $tax) {
                            $sub_tax = ($product->price * $product->quantity * $tax->tax) / 100;
                            $total_taxs += $sub_tax;
                            $final_taxs += $sub_tax;
                        }
                    } else {
                        $total_taxs = 0;
                    }

                    $totalprice = $product->price * $product->quantity + $final_taxs;
                    $subtotal1 = $product->price * $product->quantity;
                    $sub_total += $subtotal1;
                    $grand_total += $totalprice;
                }
            }
        }

        if (!empty($coupon)) {
            if ($coupon->enable_flat == 'on') {
                $discount_value = $coupon->flat_discount;
            } else {
                $discount_value = ($grand_total / 100) * $coupon->discount;
            }
        }

        return view('storefront.' . $store->theme_dir . '.userorder', compact('slug', 'store', 'order', 'grand_total', 'order_products', 'sub_total', 'total_taxs', 'user_details', 'shipping_data', 'location_data', 'discount_price', 'discount_value', 'final_taxs'));
    }

    public function whatsapp(Request $request, $slug)
    {
        $store = Store::where('slug', $slug)->first();
        if ((!empty(Auth::guard('customers')->user()) && $store->is_checkout_login_required == 'on') || $store->is_checkout_login_required == 'off') {
            $store = Store::where('slug', $slug)->first();

            $shipping = Shipping::where('store_id', $store->id)->first();
            if (!empty($shipping) && $store->enable_shipping == 'on') {
                if ($request->shipping_price == '0.00') {
                    return response()->json(
                        [
                            'status' => 'error',
                            'success' => __('Please select shipping.'),
                        ]
                    );
                }
            }
            if (empty($store)) {
                return response()->json(
                    [
                        'status' => 'error',
                        'success' => __('Store not available.'),
                    ]
                );
            }
            $validator = \Validator::make(
                $request->all(),
                [
                    'name' => 'required|max:120',
                    'phone' => 'required',
                    'billing_address' => 'required',
                ]
            );
            if ($validator->fails()) {
                return response()->json(
                    [
                        'status' => 'error',
                        'success' => __('All field is required.'),
                    ]
                );
            }


            $userdetail = new UserDetail();

            $userdetail['store_id'] = $store->id;
            $userdetail['name'] = $request->name;
            $userdetail['email'] = $request->email;
            $userdetail['phone'] = $request->phone;

            $userdetail['custom_field_title_1'] = $request->custom_field_title_1;
            $userdetail['custom_field_title_2'] = $request->custom_field_title_2;
            $userdetail['custom_field_title_3'] = $request->custom_field_title_3;
            $userdetail['custom_field_title_4'] = $request->custom_field_title_4;

            $userdetail['billing_address'] = $request->billing_address;
            $userdetail['shipping_address'] = !empty($request->shipping_address) ? $request->shipping_address : '-';
            $userdetail['special_instruct'] = $request->special_instruct;
            $userdetail->save();
            $userdetail->id;

            $customer = [
                "id" => $userdetail->id,
                "name" => $request->name,
                "email" => $request->email,
                "phone" => $request->phone,
                "custom_field_title_1" => $request->custom_field_title_1,
                "custom_field_title_2" => $request->custom_field_title_2,
                "custom_field_title_3" => $request->custom_field_title_3,
                "custom_field_title_4" => $request->custom_field_title_4,
                "billing_address" => $request->billing_address,
                "shipping_address" => $request->shipping_address,
                "special_instruct" => $request->special_instruct,
            ];

            $order_id = $request['order_id'];
            $cart = session()->get($slug);
            $products = $cart;

            $cust_details = $customer;
            if (empty($cart)) {
                return response()->json(
                    [
                        'status' => 'error',
                        'success' => __('Please add to product into cart.'),
                    ]
                );
            }
            if (!empty($request->coupon_id)) {
                $coupon = ProductCoupon::where('id', $request->coupon_id)->first();
            } else {
                $coupon = '';
            }

            $product_name = [];
            $product_id = [];
            $tax_name = [];
            $totalprice = 0;
            foreach ($products['products'] as $key => $product) {
                if ($product['variant_id'] == 0) {
                    $new_qty = $product['originalquantity'] - $product['quantity'];
                    $product_edit = Product::find($product['product_id']);
                    $product_edit->quantity = $new_qty;
                    $product_edit->save();

                    $tax_price = 0;
                    if (!empty($product['tax'])) {
                        foreach ($product['tax'] as $key => $taxs) {
                            $tax_price += $product['price'] * $product['quantity'] * $taxs['tax'] / 100;
                        }
                    }
                    $totalprice += $product['price'] * $product['quantity'];
                    $product_name[] = $product['product_name'];
                    $product_id[] = $product['id'];
                } elseif ($product['variant_id'] != 0) {
                    $new_qty = $product['originalvariantquantity'] - $product['quantity'];
                    $product_variant = ProductVariantOption::find($product['variant_id']);
                    $product_variant->quantity = $new_qty;
                    $product_variant->save();

                    $tax_price = 0;
                    if (!empty($product['tax'])) {
                        foreach ($product['tax'] as $key => $taxs) {
                            $tax_price += $product['variant_price'] * $product['quantity'] * $taxs['tax'] / 100;
                        }
                    }
                    $totalprice += $product['variant_price'] * $product['quantity'];
                    $product_name[] = $product['product_name'] . ' - ' . $product['variant_name'];
                    $product_id[] = $product['id'];
                }
            }

            $price = $totalprice + $tax_price;
            if (isset($cart['coupon'])) {
                if ($cart['coupon']['coupon']['enable_flat'] == 'off') {
                    $discount_value = ($price / 100) * $cart['coupon']['coupon']['discount'];
                    $price          = $price - $discount_value;
                } else {
                    $discount_value = $cart['coupon']['coupon']['flat_discount'];
                    $price          = $price - $discount_value;
                }
            }

            if (!empty($request->shipping_id)) {
                $shipping = Shipping::find($request->shipping_id);
                if (!empty($shipping)) {
                    $totalprice = $price + $shipping->price;
                    $shipping_name = $shipping->name;
                    $shipping_price = $shipping->price;
                    $shipping_data = json_encode(
                        [
                            'shipping_name' => $shipping_name,
                            'shipping_price' => $shipping_price,
                            'location_id' => $shipping->location_id,
                        ]
                    );
                }
            } else {
                $shipping_data = '';
            }

            if ($product) {
                if (Utility::CustomerAuthCheck($store->slug)) {
                    $customer = Auth::guard('customers')->user()->id;
                } else {
                    $customer = 0;
                }

                $customer = Auth::guard('customers')->user();
                $order = new Order();
                $order->order_id = $request->order_id;
                $order->name = $cust_details['name'];
                $order->email = $cust_details['email'];
                $order->card_number = '';
                $order->card_exp_month = '';
                $order->card_exp_year = '';
                $order->status = 'pending';
                $order->phone = $request->phone;
                $order->user_address_id = $cust_details['id'];
                $order->shipping_data = !empty($shipping_data) ? $shipping_data : '';
                $order->product_id = implode(',', $product_id);
                $order->price = $price;
                $order->coupon = $request->coupon_id;
                $order->coupon_json = json_encode($coupon);
                $order->discount_price = !empty($request->dicount_price) ? $request->dicount_price : '0';
                $order->coupon = $request->coupon_id;
                $order->product = json_encode($products);
                $order->price_currency = $store->currency_code;
                $order->txn_id = '';
                $order->payment_type = __('Whatsapp');
                $order->payment_status = 'approved';
                $order->receipt = '';
                $order->user_id = $store['id'];
                $order->customer_id = isset($customer->id) ? $customer->id : 0;
                $order->save();

                //webhook
                $module = 'New Order';
                $webhook =  Utility::webhook($module, $store->id);
                if ($webhook) {
                    $parameter = json_encode($product);
                    //
                    // 1 parameter is  URL , 2 parameter is data , 3 parameter is method
                    $status = Utility::WebhookCall($webhook['url'], $parameter, $webhook['method']);
                    // dd($status);
                    if ($status != true) {
                        $msg  = 'Webhook call failed.';
                    }
                }

                if ((!empty(Auth::guard('customers')->user()) && $store->is_checkout_login_required == 'on') || (!empty(Auth::guard('customers')->user()) && $store->is_checkout_login_required == 'off')) {
                    foreach ($products['products'] as $k_pro => $product_id) {
                        $purchased_product = new PurchasedProducts();
                        $purchased_product->product_id = $product_id['product_id'];
                        $purchased_product->customer_id = isset($customer->id) ? $customer->id : 0;
                        $purchased_product->order_id = $order->id;
                        $purchased_product->save();
                    }
                }
                $msg = response()->json(
                    [
                        'status' => 'success',
                        'success' => __('Your Order Successfully Added') . ((isset($msg)) ? '<br> <span class="text-danger">' . $msg . '</span>' : ''),
                        'order_id' => Crypt::encrypt($order->id),
                    ]
                );

                $order_email = $order->email;
                $owner = User::find($store->created_by);
                $owner_email = $owner->email;
                $order_id = Crypt::encrypt($order->id);
                // if (isset($store->mail_driver) && !empty($store->mail_driver)) {
                    $dArr = [
                        'order_name' => $order->name,
                    ];
                    $resp = Utility::sendEmailTemplate('Order Created', $order_email, $dArr, $store, $order_id);
                    $resp1 = Utility::sendEmailTemplate('Order Created For Owner', $owner_email, $dArr, $store, $order_id);
                // }
                if (!Utility::CustomerAuthCheck($store->slug)) {
                    $customer = new \stdClass();
                    $customer->phone_number = $request->phone;
                }
                if (isset($store->is_twilio_enabled) && $store->is_twilio_enabled == "on") {
                    Utility::order_create_owner($order, $owner, $store);
                    Utility::order_create_customer($order, $customer, $store);
                }
                return $msg;
            } else {
                return response()->json(
                    [
                        'status' => 'error',
                        'success' => __('Failed'),
                    ]
                );
            }
        } else {

            return response()->json(
                [
                    'status' => 'error',
                    'success' => __('You need to login'),
                ]
            );
        }
    }

    public function telegram(Request $request, $slug)
    {
        $store = Store::where('slug', $slug)->first();
        if ((!empty(Auth::guard('customers')->user()) && $store->is_checkout_login_required == 'on') || $store->is_checkout_login_required == 'off') {
            $store = Store::where('slug', $slug)->first();

            $shipping = Shipping::where('store_id', $store->id)->first();
            if (!empty($shipping) && $store->enable_shipping == 'on') {
                if ($request->shipping_price == '0.00') {
                    return response()->json(
                        [
                            'status' => 'error',
                            'success' => __('Please select shipping.'),
                        ]
                    );
                }
            }
            if (empty($store)) {
                return response()->json(
                    [
                        'status' => 'error',
                        'success' => __('Store not available.'),
                    ]
                );
            }
            $validator = \Validator::make(
                $request->all(),
                [
                    'name' => 'required|max:120',
                    'phone' => 'required',
                    'billing_address' => 'required',
                ]
            );

            if ($validator->fails()) {
                return response()->json(
                    [
                        'status' => 'error',
                        'success' => __('All field is required.'),
                    ]
                );
            }

            $userdetail = new UserDetail();
            $userdetail['store_id'] = $store->id;
            $userdetail['name'] = $request->name;
            $userdetail['email'] = $request->email;
            $userdetail['phone'] = $request->phone;

            $userdetail['custom_field_title_1'] = $request->custom_field_title_1;
            $userdetail['custom_field_title_2'] = $request->custom_field_title_2;
            $userdetail['custom_field_title_3'] = $request->custom_field_title_3;
            $userdetail['custom_field_title_4'] = $request->custom_field_title_4;

            $userdetail['billing_address'] = $request->billing_address;
            $userdetail['shipping_address'] = !empty($request->shipping_address) ? $request->shipping_address : '-';
            $userdetail['special_instruct'] = $request->special_instruct;
            $userdetail->save();
            $userdetail->id;

            $customer = [
                "id" => $userdetail->id,
                "name" => $request->name,
                "email" => $request->email,
                "phone" => $request->phone,
                "custom_field_title_1" => $request->custom_field_title_1,
                "custom_field_title_2" => $request->custom_field_title_2,
                "custom_field_title_3" => $request->custom_field_title_3,
                "custom_field_title_4" => $request->custom_field_title_4,
                "billing_address" => $request->billing_address,
                "shipping_address" => $request->shipping_address,
                "special_instruct" => $request->special_instruct,
            ];

            $products = $request['product'];
            $order_id = $request['order_id'];
            $cart = session()->get($slug);
            $cust_details = $customer;
            if (empty($cart)) {
                return response()->json(
                    [
                        'status' => 'error',
                        'success' => __('Please add to product into cart.'),
                    ]
                );
            }
            if (!empty($request->coupon_id)) {
                $coupon = ProductCoupon::where('id', $request->coupon_id)->first();
            } else {
                $coupon = '';
            }

            $product_name = [];
            $product_id = [];
            $tax_name = [];
            $totalprice = 0;
            foreach ($products['products'] as $key => $product) {
                if ($product['variant_id'] == 0) {
                    $new_qty = $product['originalquantity'] - $product['quantity'];
                    $product_edit = Product::find($product['product_id']);
                    $product_edit->quantity = $new_qty;
                    $product_edit->save();

                    $tax_price = 0;
                    if (!empty($product['tax'])) {
                        foreach ($product['tax'] as $key => $taxs) {
                            $tax_price += $product['price'] * $product['quantity'] * $taxs['tax'] / 100;
                        }
                    }
                    $totalprice += $product['price'] * $product['quantity'];
                    $product_name[] = $product['product_name'];
                    $product_id[] = $product['id'];
                } elseif ($product['variant_id'] != 0) {
                    $new_qty = $product['originalvariantquantity'] - $product['quantity'];
                    $product_variant = ProductVariantOption::find($product['variant_id']);
                    $product_variant->quantity = $new_qty;
                    $product_variant->save();

                    $tax_price = 0;
                    if (!empty($product['tax'])) {
                        foreach ($product['tax'] as $key => $taxs) {
                            $tax_price += $product['variant_price'] * $product['quantity'] * $taxs['tax'] / 100;
                        }
                    }
                    $totalprice += $product['variant_price'] * $product['quantity'];
                    $product_name[] = $product['product_name'] . ' - ' . $product['variant_name'];
                    $product_id[] = $product['id'];
                }
            }
            $price = $totalprice + $tax_price;
            if (isset($cart['coupon'])) {
                if ($cart['coupon']['coupon']['enable_flat'] == 'off') {
                    $discount_value = ($price / 100) * $cart['coupon']['coupon']['discount'];
                    $price          = $price - $discount_value;
                } else {
                    $discount_value = $cart['coupon']['coupon']['flat_discount'];
                    $price          = $price - $discount_value;
                }
            }
            if (!empty($request->shipping_id)) {
                $shipping = Shipping::find($request->shipping_id);
                if (!empty($shipping)) {
                    $totalprice = $price + $shipping->price;
                    $shipping_name = $shipping->name;
                    $shipping_price = $shipping->price;
                    $shipping_data = json_encode(
                        [
                            'shipping_name' => $shipping_name,
                            'shipping_price' => $shipping_price,
                            'location_id' => $shipping->location_id,
                        ]
                    );
                }
            } else {
                $shipping_data = '';
            }

            if ($product) {
                if (Utility::CustomerAuthCheck($store->slug)) {
                    $customer = Auth::guard('customers')->user()->id;
                } else {
                    $customer = 0;
                }

                $customer = Auth::guard('customers')->user();
                $order = new Order();
                $order->order_id = '#' . time();
                $order->name = $cust_details['name'];
                $order->email = $cust_details['email'];
                $order->card_number = '';
                $order->card_exp_month = '';
                $order->card_exp_year = '';
                $order->status = 'pending';
                $order->phone = $request->phone;
                $order->user_address_id = $cust_details['id'];
                $order->shipping_data = !empty($shipping_data) ? $shipping_data : '';
                $order->product_id = implode(',', $product_id);
                $order->price = $price;
                $order->coupon = $request->coupon_id;
                $order->coupon_json = json_encode($coupon);
                $order->discount_price = !empty($request->dicount_price) ? $request->dicount_price : '0';
                $order->coupon = $request->coupon_id;
                $order->product = json_encode($products);
                $order->price_currency = $store->currency_code;
                $order->txn_id = '';
                $order->payment_type = __('Telegram');
                $order->payment_status = 'approved';
                $order->receipt = '';
                $order->user_id = $store['id'];
                $order->customer_id = isset($customer->id) ? $customer->id : 0;
                $order->save();

                //webhook
                $module = 'New Order';
                // $store = \Auth::user()->current_store;
                $webhook =  Utility::webhook($module, $store->id);
                if ($webhook) {
                    $parameter = json_encode($product);
                    //
                    // 1 parameter is  URL , 2 parameter is data , 3 parameter is method
                    $status = Utility::WebhookCall($webhook['url'], $parameter, $webhook['method']);
                    // dd($status);
                    if ($status != true) {
                        $msg  = 'Webhook call failed.';
                    }
                }

                if ((!empty(Auth::guard('customers')->user()) && $store->is_checkout_login_required == 'on') || (!empty(Auth::guard('customers')->user()) && $store->is_checkout_login_required == 'off')) {
                    foreach ($products['products'] as $k_pro => $product_id) {
                        $purchased_product = new PurchasedProducts();
                        $purchased_product->product_id = $product_id['product_id'];
                        $purchased_product->customer_id = isset($customer->id) ? $customer->id : 0;
                        $purchased_product->order_id = $order->id;
                        $purchased_product->save();
                    }
                }

                $msg = response()->json(
                    [
                        'status' => 'success',
                        'success' => __('Your Order Successfully Added') . ((isset($msg)) ? '<br> <span class="text-danger">' . $msg . '</span>' : ''),
                        'order_id' => Crypt::encrypt($order->id),
                    ]
                );

                $order_email = $order->email;
                $owner = User::find($store->created_by);
                $owner_email = $owner->email;
                $order_id = Crypt::encrypt($order->id);
                // if (isset($store->mail_driver) && !empty($store->mail_driver)) {
                    $dArr = [
                        'order_name' => $order->name,
                    ];
                    $resp = Utility::sendEmailTemplate('Order Created', $order_email, $dArr, $store, $order_id);
                    $resp1 = Utility::sendEmailTemplate('Order Created For Owner', $owner_email, $dArr, $store, $order_id);
                // }
                if (!Utility::CustomerAuthCheck($store->slug)) {
                    $customer = new \stdClass();
                    $customer->phone_number = $request->phone;
                }
                if (isset($store->is_twilio_enabled) && $store->is_twilio_enabled == "on") {
                    Utility::order_create_owner($order, $owner, $store);
                    Utility::order_create_customer($order, $customer, $store);
                }
                return $msg;
            } else {
                return response()->json(
                    [
                        'status' => 'error',
                        'success' => __('Failed'),
                    ]
                );
            }
        } else {

            return response()->json(
                [
                    'status' => 'error',
                    'success' => __('You need to login'),
                ]
            );
        }
    }

    public function cod(Request $request, $slug)
    {
        $store = Store::where('slug', $slug)->first();
        if ((!empty(Auth::guard('customers')->user()) && $store->is_checkout_login_required == 'on') || $store->is_checkout_login_required == 'off') {
            $store = Store::where('slug', $slug)->first();

            $shipping = Shipping::where('store_id', $store->id)->first();
            if (!empty($shipping) && $store->enable_shipping == 'on') {
                if ($request->shipping_price == '0.00') {
                    return response()->json(
                        [
                            'status' => 'error',
                            'success' => __('Please select shipping.'),
                        ]
                    );
                }
            }
            if (empty($store)) {
                return response()->json(
                    [
                        'status' => 'error',
                        'success' => __('Store not available.'),
                    ]
                );
            }
            $validator = \Validator::make(
                $request->all(),
                [
                    'name' => 'required|max:120',
                    'phone' => 'required',
                    'billing_address' => 'required',
                ]
            );

            if ($validator->fails()) {
                return response()->json(
                    [
                        'status' => 'error',
                        'success' => __('All field is required.'),
                    ]
                );
            }

            $userdetail = new UserDetail();
            $userdetail['store_id'] = $store->id;
            $userdetail['name'] = $request->name;
            $userdetail['email'] = $request->email;
            $userdetail['phone'] = $request->phone;

            $userdetail['custom_field_title_1'] = $request->custom_field_title_1;
            $userdetail['custom_field_title_2'] = $request->custom_field_title_2;
            $userdetail['custom_field_title_3'] = $request->custom_field_title_3;
            $userdetail['custom_field_title_4'] = $request->custom_field_title_4;

            $userdetail['billing_address'] = $request->billing_address;
            $userdetail['shipping_address'] = !empty($request->shipping_address) ? $request->shipping_address : '-';
            $userdetail['special_instruct'] = $request->special_instruct;
            $userdetail->save();
            $userdetail->id;

            $customer = [
                "id" => $userdetail->id,
                "name" => $request->name,
                "email" => $request->email,
                "phone" => $request->phone,
                "custom_field_title_1" => $request->custom_field_title_1,
                "custom_field_title_2" => $request->custom_field_title_2,
                "custom_field_title_3" => $request->custom_field_title_3,
                "custom_field_title_4" => $request->custom_field_title_4,
                "billing_address" => $request->billing_address,
                "shipping_address" => $request->shipping_address,
                "special_instruct" => $request->special_instruct,
            ];

            $order_id = $request['order_id'];
            $cart = session()->get($slug);
            $products = $cart;
            $cust_details = $customer;
            if (empty($cart)) {
                return response()->json(
                    [
                        'status' => 'error',
                        'success' => __('Please add to product into cart.'),
                    ]
                );
            }

            if (!empty($request->coupon_id)) {

                $coupon = ProductCoupon::where('id', $request->coupon_id)->first();
            } else {
                $coupon = '';
            }

            if (!empty($request->coupon_id) || $request->coupon_id == 'undefined') {

                $coupons = 'null';
            } else {
                $coupons = $request->coupon_id;
            }

            $product_name = [];
            $product_id = [];
            $tax_name = [];
            $totalprice = 0;
            foreach ($products['products'] as $key => $product) {
                if ($product['variant_id'] == 0) {
                    $new_qty = $product['originalquantity'] - $product['quantity'];
                    $product_edit = Product::find($product['product_id']);
                    $product_edit->quantity = $new_qty;
                    $product_edit->save();

                    $tax_price = 0;
                    if (!empty($product['tax'])) {
                        foreach ($product['tax'] as $key => $taxs) {
                            $tax_price += $product['price'] * $product['quantity'] * $taxs['tax'] / 100;
                        }
                    }
                    $totalprice += $product['price'] * $product['quantity'];
                    $product_name[] = $product['product_name'];
                    $product_id[] = $product['id'];
                } elseif ($product['variant_id'] != 0) {
                    $new_qty = $product['originalvariantquantity'] - $product['quantity'];
                    $product_variant = ProductVariantOption::find($product['variant_id']);
                    $product_variant->quantity = $new_qty;
                    $product_variant->save();

                    $tax_price = 0;
                    if (!empty($product['tax'])) {
                        foreach ($product['tax'] as $key => $taxs) {
                            $tax_price += $product['variant_price'] * $product['quantity'] * $taxs['tax'] / 100;
                        }
                    }
                    $totalprice += $product['variant_price'] * $product['quantity'];
                    $product_name[] = $product['product_name'] . ' - ' . $product['variant_name'];
                    $product_id[] = $product['id'];
                }
            }
            $price = $totalprice + $tax_price;
            if (isset($cart['coupon'])) {
                if ($cart['coupon']['coupon']['enable_flat'] == 'off') {
                    $discount_value = ($price / 100) * $cart['coupon']['coupon']['discount'];
                    $price          = $price - $discount_value;
                } else {
                    $discount_value = $cart['coupon']['coupon']['flat_discount'];
                    $price          = $price - $discount_value;
                }
            }
            if (!empty($request->shipping_id)) {
                $shipping = Shipping::find($request->shipping_id);
                if (!empty($shipping)) {
                    $totalprice = $price + $shipping->price;
                    $shipping_name = $shipping->name;
                    $shipping_price = $shipping->price;
                    $shipping_data = json_encode(
                        [
                            'shipping_name' => $shipping_name,
                            'shipping_price' => $shipping_price,
                            'location_id' => $shipping->location_id,
                        ]
                    );
                }
            } else {
                $shipping_data = '';
            }

            if ($product) {
                if (Utility::CustomerAuthCheck($store->slug)) {
                    $customer = Auth::guard('customers')->user()->id;
                } else {
                    $customer = 0;
                }


                $user          = \Auth::user();
                $customer = Auth::guard('customers')->user();
                $order = new Order();
                $order->order_id = '#' . time();
                $order->name = $cust_details['name'];
                $order->email = $cust_details['email'];
                $order->card_number = '';
                $order->card_exp_month = '';
                $order->card_exp_year = '';
                $order->status = 'pending';
                $order->phone = $request->phone;
                $order->user_address_id = $cust_details['id'];
                $order->shipping_data = !empty($shipping_data) ? $shipping_data : '';
                $order->coupon = $coupons;
                $order->coupon_json = json_encode($coupon);
                $order->discount_price = $request->dicount_price;
                $order->product_id = implode(',', $product_id);
                $order->price = $price;
                $order->product = json_encode($products);
                $order->price_currency = $store->currency_code;
                $order->txn_id = '';
                $order->payment_type = __('COD');
                $order->payment_status = 'approved';
                $order->receipt = '';
                $order->user_id = $store['id'];
                $order->customer_id = isset($customer->id) ? $customer->id : 0;
                $order->save();

                //webhook
                $module = 'New Order';
                $webhook =  Utility::webhook($module, $store->id);
                if ($webhook) {
                    $parameter = json_encode($product);
                    //
                    // 1 parameter is  URL , 2 parameter is data , 3 parameter is method
                    $status = Utility::WebhookCall($webhook['url'], $parameter, $webhook['method']);
                    // dd($status);
                    if ($status != true) {
                        $msg  = 'Webhook call failed.';
                    }
                }

                if ((!empty(Auth::guard('customers')->user()) && $store->is_checkout_login_required == 'on')) {
                    foreach ($products as $product_id) {
                        $purchased_products = new PurchasedProducts();
                        $purchased_products->customer_id = isset($customer->id) ? $customer->id : 0;
                        $purchased_products->order_id = $order->id;
                        $purchased_products->save();
                    }
                }

                $msg = response()->json(
                    [
                        'status' => 'success',
                        'success' => __('Your Order Successfully Added') . ((isset($msg)) ? '<br> <span class="text-danger">' . $msg . '</span>' : ''),
                        'order_id' => Crypt::encrypt($order->id),
                    ]
                );

                $order_email = $order->email;
                $owner = User::find($store->created_by);
                $owner_email = $owner->email;
                $order_id = Crypt::encrypt($order->id);


                // if (isset($store->mail_driver) && !empty($store->mail_driver)) {
                    $dArr = [
                        'order_name' => $order->name,
                    ];
                    $resp = Utility::sendEmailTemplate('Order Created', $order_email, $dArr, $store, $order_id);
                    $resp1 = Utility::sendEmailTemplate('Order Created For Owner', $owner_email, $dArr, $store, $order_id);
                // }
                if (isset($store->is_twilio_enabled) && $store->is_twilio_enabled == "on") {
                    Utility::order_create_owner($order, $owner, $store);
                    Utility::order_create_customer($order, $customer, $store);
                }

                return $msg;
            } else {
                return response()->json(
                    [
                        'status' => 'error',
                        'success' => __('Failed'),
                    ]
                );
            }
        } else {

            return response()->json(
                [
                    'status' => 'error',
                    'success' => __('You need to login'),
                ]
            );
        }
    }

    public function bank_transfer(Request $request, $slug)
    {
        $store = Store::where('slug', $slug)->first();
        if ((!empty(Auth::guard('customers')->user()) && $store->is_checkout_login_required == 'on') || $store->is_checkout_login_required == 'off') {
            $store = Store::where('slug', $slug)->first();

            $shipping = Shipping::where('store_id', $store->id)->first();
            if (!empty($shipping) && $store->enable_shipping == 'on') {
                if ($request->shipping_price == '0.00') {
                    return response()->json(
                        [
                            'status' => 'error',
                            'success' => __('Please select shipping.'),
                        ]
                    );
                }
            }
            if (empty($store)) {
                return response()->json(
                    [
                        'status' => 'error',
                        'success' => __('Store not available.'),
                    ]
                );
            }
            $validator = \Validator::make(
                $request->all(),
                [
                    'name' => 'required|max:120',
                    'phone' => 'required',
                    'billing_address' => 'required',
                ]
            );

            if ($validator->fails()) {
                return response()->json(
                    [
                        'status' => 'error',
                        'success' => __('All field is required.'),
                    ]
                );
            }
            $validator = \Validator::make(
                $request->all(),
                [
                    'bank_transfer_invoice' => 'required',
                ]
            );
            if ($validator->fails()) {
                $messages = $validator->getMessageBag();
                return response()->json(
                    [
                        'status' => 'Error',
                        'success' => $messages->first(),
                    ]
                );
            }

            $filenameWithExt = $request->file('bank_transfer_invoice')->getClientOriginalName();
            $filename = pathinfo($filenameWithExt, PATHINFO_FILENAME);
            $extension = $request->file('bank_transfer_invoice')->getClientOriginalExtension();
            $fileNameToStores = $filename . '_' . time() . '.' . $extension;

            $settings = Utility::getStorageSetting();
            // $settings = Utility::settings();
            if ($settings['storage_setting'] == 'local') {
                $dir  = 'uploads/bank_invoice/';
            } else {
                $dir  = 'uploads/bank_invoice/';
            }
            $path = Utility::upload_file($request, 'bank_transfer_invoice', $fileNameToStores, $dir, []);
            if ($path['flag'] == 1) {
                $url = $path['url'];
            } else {
                // return redirect()->back()->with('error', __($path['msg']));
                return response()->json(
                    [
                        'status' => 'error',
                        'success' => __($path['msg']),
                    ]
                );
            }

            $userdetail = new UserDetail();
            $userdetail['store_id'] = $store->id;
            $userdetail['name'] = $request->name;
            $userdetail['email'] = $request->email;
            $userdetail['phone'] = $request->phone;

            $userdetail['custom_field_title_1'] = $request->custom_field_title_1;
            $userdetail['custom_field_title_2'] = $request->custom_field_title_2;
            $userdetail['custom_field_title_3'] = $request->custom_field_title_3;
            $userdetail['custom_field_title_4'] = $request->custom_field_title_4;

            $userdetail['billing_address'] = $request->billing_address;
            $userdetail['shipping_address'] = !empty($request->shipping_address) ? $request->shipping_address : '-';
            $userdetail['special_instruct'] = $request->special_instruct;
            $userdetail->save();
            $userdetail->id;

            $customer = [
                "id" => $userdetail->id,
                "name" => $request->name,
                "email" => $request->email,
                "phone" => $request->phone,
                "custom_field_title_1" => $request->custom_field_title_1,
                "custom_field_title_2" => $request->custom_field_title_2,
                "custom_field_title_3" => $request->custom_field_title_3,
                "custom_field_title_4" => $request->custom_field_title_4,
                "billing_address" => $request->billing_address,
                "shipping_address" => $request->shipping_address,
                "special_instruct" => $request->special_instruct,
            ];

            $store = Store::where('slug', $slug)->first();
            $products = $request['product'];
            $order_id = $request['order_id'];
            $cart = session()->get($slug);
            $cust_details = $customer;

            if (!empty($request->coupon_id)) {
                $coupon = ProductCoupon::where('id', $request->coupon_id)->first();
            } else {
                $coupon = '';
            }

            $product_name = [];
            $product_id = [];
            $tax_name = [];
            $totalprice = 0;
            $products = (!empty($products)) ? json_decode($products, true) : [];

            foreach ($products as $key => $cart_product) {
                foreach ($cart_product as $key => $product) {
                    if (isset($product['variant_id']) ||  $product['variant_id'] == 0) {
                        $new_qty = $product['originalquantity'] - $product['quantity'];
                        $product_edit = Product::find($product['product_id']);
                        $product_edit->quantity = $new_qty;
                        $product_edit->save();

                        $tax_price = 0;
                        if (!empty($product['tax'])) {
                            foreach ($product['tax'] as $key => $taxs) {
                                $tax_price += $product['price'] * $product['quantity'] * $taxs['tax'] / 100;
                            }
                        }
                        $totalprice += $product['price'] * $product['quantity'];
                        $product_name[] = $product['product_name'];
                        $product_id[] = $product['id'];
                    } elseif ($product['variant_id'] != 0) {
                        $new_qty = $product['originalvariantquantity'] - $product['quantity'];
                        $product_variant = ProductVariantOption::find($product['variant_id']);
                        $product_variant->quantity = $new_qty;
                        $product_variant->save();

                        $tax_price = 0;
                        if (!empty($product['tax'])) {
                            foreach ($product['tax'] as $key => $taxs) {
                                $tax_price += $product['variant_price'] * $product['quantity'] * $taxs['tax'] / 100;
                            }
                        }
                        $totalprice += $product['variant_price'] * $product['quantity'];
                        $product_name[] = $product['product_name'];
                        $product_id[] = $product['id'];
                    }
                }
            }
            $price = $totalprice + $tax_price;
            if (isset($cart['coupon'])) {
                if ($cart['coupon']['coupon']['enable_flat'] == 'off') {
                    $discount_value = ($price / 100) * $cart['coupon']['coupon']['discount'];
                    $price = $price - $discount_value;
                } else {
                    $discount_value = $cart['coupon']['coupon']['flat_discount'];
                    $price = $price - $discount_value;
                }
            }
            if (isset($cart['shipping']) && isset($cart['shipping']['shipping_id']) && !empty($cart['shipping'])) {
                $shipping = Shipping::find($cart['shipping']['shipping_id']);
                if (!empty($shipping)) {
                    $totalprice = $price + $shipping->price;
                    $shipping_name = $shipping->name;
                    $shipping_price = $shipping->price;
                    $shipping_data = json_encode(
                        [
                            'shipping_name' => $shipping_name,
                            'shipping_price' => $shipping_price,
                            'location_id' => $cart['shipping']['location_id'],
                        ]
                    );
                }
            } else {
                $shipping_data = '';
            }

            if ($product) {

                if (Utility::CustomerAuthCheck($store->slug)) {
                    $customer = Auth::guard('customers')->user()->id;
                } else {
                    $customer = 0;
                }
                $customer = Auth::guard('customers')->user();
                $order = new Order();
                $order->order_id = '#' . time();
                $order->name = $cust_details['name'];
                $order->email = $cust_details['email'];
                $order->card_number = '';
                $order->card_exp_month = '';
                $order->card_exp_year = '';
                $order->status = 'pending';
                $order->phone = $request->phone;
                $order->user_address_id = $cust_details['id'];
                $order->shipping_data = !empty($shipping_data) ? $shipping_data : '';
                $order->coupon = $request->coupon_id;
                $order->coupon_json = json_encode($coupon);
                $order->discount_price = $request->dicount_price;
                $order->product_id = implode(',', $product_id);
                $order->price = $price;
                $order->product = json_encode($products);
                $order->price_currency = $store->currency_code;
                $order->txn_id = '';
                $order->payment_type = __('Bank Transfer');
                $order->payment_status = 'approved';
                $order->receipt = '';
                $order->user_id = $store['id'];
                $order->customer_id = isset($customer->id) ? $customer->id : 0;
                $order->save();

                //webhook
                $module = 'New Order';
                // $store = \Auth::user()->current_store;
                // $store = Store::where('id', \Auth::user()->current_store)->first();
                $webhook =  Utility::webhook($module, $store);
                if ($webhook) {
                    $parameter = json_encode($product);
                    //
                    // 1 parameter is  URL , 2 parameter is data , 3 parameter is method
                    $status = Utility::WebhookCall($webhook['url'], $parameter, $webhook['method']);
                    // dd($status);
                    if ($status != true) {
                        $msg  = 'Webhook call failed.';
                    }
                }

                if ((!empty(Auth::guard('customers')->user()) && $store->is_checkout_login_required == 'on')) {
                    foreach ($products as $product) {
                        foreach ($product as $product_id) {
                            $purchased_product = new PurchasedProducts();
                            $purchased_product->product_id = $product_id['product_id'];
                            $purchased_product->customer_id = isset($customer->id) ? $customer->id : 0;
                            $purchased_product->order_id = $order->id;
                            $purchased_product->save();
                        }
                    }
                }
                $msg = response()->json(
                    [
                        'status' => 'success',
                        'success' => __('Your Order Successfully Added') . ((isset($msg)) ? '<br> <span class="text-danger">' . $msg . '</span>' : ''),
                        'order_id' => Crypt::encrypt($order->id),
                    ]
                );


                $order_email = $order->email;
                $owner = User::find($store->created_by);

                $owner_email = $owner->email;
                $order_id = Crypt::encrypt($order->id);
                try {

                    // if (isset($store->mail_driver) && !empty($store->mail_driver)) {
                        $dArr = [
                            'order_name' => $order->name,
                        ];

                        // $order = Crypt::encrypt($order->id);

                        $resp = Utility::sendEmailTemplate('Order Created', $order_email, $dArr, $store, $order_id);

                        $resp1 = Utility::sendEmailTemplate('Order Created For Owner', $owner_email, $dArr, $store, $order_id);
                    // }

                    if (isset($store->is_twilio_enabled) && $store->is_twilio_enabled == "on") {
                        Utility::order_create_owner($order, $owner, $store);
                        Utility::order_create_customer($order, $customer, $store);
                    }

                    return $msg;
                } catch (\Exception $e) {
                    return $msg;
                }
            } else {

                return response()->json(
                    [
                        'status' => 'error',
                        'success' => __('Failed'),
                    ]
                );
            }
        } else {

            return response()->json(
                [
                    'status' => 'error',
                    'success' => __('You need to login'),
                ]
            );
        }
    }

    public function grid()
    {
        if (\Auth::user()->type == 'super admin') {
            $users = User::with('currentPlan')->where('created_by', '=', \Auth::user()->creatorId())->where('type', '=', 'owner')->get();
            $stores = Store::get();

            return view('user.grid', compact('users', 'stores'));
        } else {
            return redirect()->back()->with('error', __('permission Denied'));
        }
    }

    public function upgradePlan($user_id)
    {
        if (\Auth::user()->type == 'super admin') {
            $user = User::find($user_id);

            $plans = Plan::where('is_active',1)->get();

            return view('user.plan', compact('user', 'plans'));
        }
    }

    public function activePlan($user_id, $plan_id)
    {
        if (\Auth::user()->type == 'super admin') {

            $user = User::find($user_id);
            $assignPlan = $user->assignPlan($plan_id);
            $plan = Plan::find($plan_id);
            if ($assignPlan['is_success'] == true && !empty($plan)) {
                $orderID = strtoupper(str_replace('.', '', uniqid('', true)));
                PlanOrder::create(
                    [
                        'order_id' => $orderID,
                        'name' => null,
                        'card_number' => null,
                        'card_exp_month' => null,
                        'card_exp_year' => null,
                        'plan_name' => $plan->name,
                        'plan_id' => $plan->id,
                        'price' => $plan->price,
                        'price_currency' => Utility::getValByName('site_currency'),
                        'txn_id' => '',
                        'payment_status' => 'succeeded',
                        'receipt' => null,
                        'payment_type' => __('Manually'),
                        'user_id' => $user->id,
                        'store_id' => $user->current_store,
                    ]
                );

                return redirect()->back()->with('success', __('Plan successfully upgraded.'));
            } else {
                return redirect()->back()->with('error', __('Plan fail to upgrade.'));
            }
        }
    }

    public function storedit($id)
    {
        if (\Auth::user()->type == 'super admin') {
            $user = User::find($id);
            $user_store = UserStore::where('user_id', $id)->first();
            $store = Store::where('id', $user_store->store_id)->first();

            return view('admin_store.edit', compact('store', 'user'));
        } else {
            return redirect()->back()->with('error', __('permission Denied'));
        }
    }

    public function storeupdate(Request $request, $id)
    {
        $user = User::find($id);
        $validator = \Validator::make(
            $request->all(),
            [
                'username' => 'required|max:120',
                'name' => 'required|max:120',
            ]
        );
        if ($validator->fails()) {
            $messages = $validator->getMessageBag();

            return redirect()->back()->with('error', $messages->first());
        }

        $user['username'] = $request->username;
        $user['name'] = $request->name;
        $user['title'] = $request->title;
        $user['phone'] = $request->phone;
        $user['gender'] = $request->gender;
        $user['is_active'] = ($request->is_active == 'on') ? 1 : 0;
        $user['user_roles'] = $request->user_roles;
        $user->update();

        Stream::create(
            [
                'user_id' => \Auth::user()->id,
                'created_by' => \Auth::user()->creatorId(),
                'log_type' => 'updated',
                'remark' => json_encode(
                    [
                        'owner_name' => \Auth::user()->username,
                        'title' => 'user',
                        'stream_comment' => '',
                        'user_name' => $request->name,
                    ]
                ),
            ]
        );
        return redirect()->back()->with('success', __('User Successfully Updated'));
    }

    public function storedestroy($id)
    {
        if (\Auth::user()->type == 'super admin') {
            $user = User::find($id);
            $userstore = UserStore::where('user_id', $user->id)->first();
            $store = Store::where('id', $userstore->store_id)->first();

            $user->delete();
            $userstore->delete();
            $store->delete();

            return redirect()->back()->with('success', __('User Store Successfully Deleted'));
        } else {
            return redirect()->back()->with('error', __('permission Denied'));
        }
    }

    public function changeCurrantStore($storeID)
    {
        if (\Auth::user()->can('Manage Change Store')) {
            $objStore = Store::find($storeID);
            if ($objStore->is_active) {
                $objUser = Auth::user();
                $objUser->current_store = $storeID;
                $objUser->update();

                return redirect()->route('dashboard')->with('success', __('Store Change Successfully!'));
            } else {
                return redirect()->back()->with('error', __('Store is locked'));
            }
        } else {
            return redirect()->back()->with('error', __('permission Denied'));
        }
    }

    public function storeVariant($slug, $id)
    {
        $store = Store::where('slug', $slug)->first();
        $products = Product::where('id', $id)->first();
        $variant_name = json_decode($products->variants_json);
        $product_variant_names = $variant_name;

        return view('storefront.' . $store->theme_dir . '.store_variant', compact('store', 'products', 'product_variant_names'));
    }

    public function changeTheme(Request $request, $slug)
    {
        if (\Auth::user()->can('Edit Themes')) {
            if ($request->themefile == "theme1" || $request->themefile == "theme2" || $request->themefile == "theme3" || $request->themefile == "theme4" || $request->themefile == "theme5" || $request->themefile == "theme6") {
                $theme_file = "theme_1_to_6";
            } else {
                $theme_file = $request->themefile;
            }
            $validator = \Validator::make(
                $request->all(),
                [
                    'theme_color' => 'required',
                ]
            );
            if ($validator->fails()) {
                $messages = $validator->getMessageBag();

                return redirect()->back()->with('error', $messages->first());
            }
            $store = Store::find($slug);

            $store['store_theme'] = $request->theme_color;
            $store['theme_dir'] = $theme_file;
            $store->save();

            return redirect()->back()->with('success', __('Theme Successfully Updated.'));
        } else {
            return redirect()->back()->with('error', __('Permission Denied.'));
        }
    }

    public function filterproductview(Request $request)
    {
        $store = Store::where('slug', $request->slug)->first();
        $userstore = UserStore::where('store_id', $store->id)->first();

        $categories = ProductCategorie::where('store_id', $userstore->store_id)->get()->pluck('name', 'id');
        $categories->prepend('All', 0);

        $products = [];
        foreach ($categories as $id => $category) {
            if ($request->types == 'myproducts') {
                $orders = Order::where('customer_id', Auth::guard('customers')->user()->id)->orderBy('id', 'DESC')->get()->pluck('product_id')->toArray();

                $pr = implode(",", $orders);
                $product_arr = explode(",", $pr);

                $product = Product::whereIn('id', $product_arr);
                $flag = 'my_orders';
                $orders = Order::where('customer_id', Auth::guard('customers')->user()->id)->orderBy('id', 'DESC')->get();
            } else {
                $product = Product::where('store_id', $store->id)->where('product_display', 'on');
                $flag = 'all_products';
                $orders = [];
            }

            if ($id != 0) {
                $product->whereRaw('FIND_IN_SET("' . $id . '", product_categorie)');
            }
            if ($request->types == 'hightolow') {
                $product->orderBy('price', 'desc');
            } else {
                $product->orderBy('price', 'asc');
            }
            $product = $product->get();
            $product->load('categories');

            $arrPriceSort = [];
            // foreach ($product as $v) {
            //     $variant_product = ProductVariantOption::where('product_id', $v->id)->first();

            //     if (!empty($variant_product)) {
            //         $arrPriceSort[$variant_product->price . '-' . $variant_product->id] = $v;
            //     } else {
            //         $arrPriceSort[$v->price . '-' . $v->id] = $v;
            //     }
            // }

            $productIds = collect($product)->pluck('id');
            $variantProducts = ProductVariantOption::whereIn('product_id', $productIds)->get();
            $variantProductMap = [];
            foreach ($variantProducts as $variantProduct) {
                $variantProductMap[$variantProduct->product_id] = $variantProduct;
            }
            foreach ($product as $v) {
                $variantProduct = isset($variantProductMap[$v->id]) ? $variantProductMap[$v->id] : null;

                if (!empty($variantProduct)) {
                    $arrPriceSort[$variantProduct->price . '-' . $variantProduct->id] = $v;
                } else {
                    $arrPriceSort[$v->price . '-' . $v->id] = $v;
                }
            }

            if ($request->types == 'hightolow') {
                krsort($arrPriceSort, SORT_NUMERIC);
            } else {
                ksort($arrPriceSort, SORT_NUMERIC);
            }

            $products[$category] = $arrPriceSort;
        }

        $returnHTML = view('storefront.' . $store->theme_dir . '/' . $request->view, compact('products', 'store', 'flag', 'orders'))->render();

        return response()->json(
            [
                'success' => true,
                'html' => $returnHTML,
            ]
        );
    }

    public function productView($slug, $id)
    {

        $store_setting = Store::where('slug', $slug)->first();

        $cart = session()->get($slug);

        $store = Store::where('slug', $slug)->where('is_store_enabled', '1')->first();
        if (empty($store)) {
            return redirect()->back()->with('error', __('Store not available'));
        }
        $products = Product::where('id', $id)->first();

        $products_image = Product_images::where('product_id', $products->id)->get();

        $is_cover = $products->is_cover;

        if ($products_image->isEmpty()) {
            $is_cover;
        }
        $variant_item = 0;
        $total_item = 0;
        if (isset($cart['products'])) {
            foreach ($cart['products'] as $item) {
                if (isset($cart) && !empty($cart['products'])) {
                    if (isset($item['variants'])) {
                        $variant_item += count($item['variants']);
                    } else {
                        $product_item = count($cart['products']);
                        $total_item = $variant_item + $product_item;
                    }
                } else {
                    $total_item = 0;
                }
            }
        }
        // dd($products);
        $variant_name = json_decode($products->variants_json);
        $product_variant_names = $variant_name;

        return view('storefront.' . $store->theme_dir . '.view', compact('products', 'store', 'products_image', 'total_item', 'store_setting', 'product_variant_names', 'is_cover'));
    }

    public function removeSession($slug)
    {
        session()->forget($slug);
    }

    public function customMassage(Request $request, $slug)
    {
        $validator = \Validator::make(
            $request->all(),
            [
                'content' => 'required',
            ]
        );

        if ($validator->fails()) {
            $messages = $validator->getMessageBag();

            return redirect()->back()->with('error', $messages->first());
        }

        $store = Store::where('slug', $slug)->first();
        $store->content = $request['content'];
        $store->item_variable = $request['item_variable'];
        $store->update();

        return redirect()->back()->with('success', __('Massage successfully updated.'));
    }

    public function getWhatsappUrl(Request $request, $slug)
    {

        $store = Store::where('slug', $slug)->first();
        $cart = session()->get($slug);

        if (!empty($cart)) {
            $products = $cart['products'];
        } else {
            return response()->json(
                [
                    'status' => 'error',
                    'msg' => __('Please add to product into cart'),
                ]
            );
        }

        if ($store->enable_shipping == 'on') {
            if (!empty($request->shipping_id)) {
                $shipping_details = Shipping::where('store_id', $store->id)->where('id', $request->shipping_id)->first();
                $shipping_price = $shipping_details->price;
            }
        } else {
            $shipping_price = 0;
        }

        // For Url
        $pro_qty = [];
        $pro_name = [];
        $order_id = '#' . time();

        $lists = [];
        $total_tax = 0;

        foreach ($products as $item) {
            $pro_data = Product::where('id', $item['id'])->first();

            if ($item['variant_id'] == 0) {
                $pro_qty[] = $item['quantity'] . ' x ' . $item['product_name'];
                $total_tax = 0;
                foreach ($item['tax'] as $tax) {
                    $sub_tax = ($item['price'] * $item['quantity'] * $tax['tax']) / 100;
                    $total_tax += $sub_tax;
                }
                $lists[] = array(
                    'sku' => $pro_data->SKU,
                    'quantity' => $item['quantity'],
                    'product_name' => $item['product_name'],
                    'item_tax' => $total_tax,
                    'item_total' => $item['price'] * $item['quantity'],
                );
            } elseif ($item['variant_id'] != 0) {
                $pro_data = Product::where('id', $item['id'])->first();
                $pro_qty[] = $item['quantity'] . ' x ' . $item['product_name'] . ' - ' . $item['variant_name'];
                foreach ($item['tax'] as $tax) {
                    $sub_tax = ($item['variant_price'] * $item['quantity'] * $tax['tax']) / 100;
                    $total_tax += $sub_tax;
                }

                $lists[] = [
                    'sku' => $pro_data->SKU,
                    'quantity' => $item['quantity'],
                    'product_name' => $item['product_name'],
                    'variant_name' => $item['variant_name'],
                    'item_tax' => $total_tax,
                    'item_total' => $item['variant_price'] * $item['quantity'],
                ];
            }
        }

        $item_variable = '';
        $qty_total = 0;
        $sub_total = 0;
        $total_tax = 0;

        foreach ($lists as $l) {
            $arrList = [
                'sku' => $l['sku'],
                'quantity' => $l['quantity'],
                'product_name' => $l['product_name'],
                'item_tax' => $l['item_tax'],
                'item_total' => Utility::priceFormat($l['item_total']),
            ];

            if (isset($l['variant_name']) && !empty($l['variant_name'])) {
                $arrList['variant_name'] = $l['variant_name'];
            }

            $resp = Utility::replaceVariable($store->item_variable, $arrList);
            $resp = str_replace('-  ', '', $resp);
            $item_variable .= $resp . PHP_EOL;

            $qty_total = $qty_total + $l['quantity'];
            $sub_total += $l['item_total'];

            $total_tax += $l['item_tax'];
        }

        $total_price = Utility::priceFormat(floatval($sub_total) + (int) $request->shipping_price + floatval($total_tax));
        $arr = [
            'store_name' => $store->name,
            'order_no' => $request->order_id,
            'customer_name' => $request->name,
            'phone' => $request->phone,
            'billing_address' => $request->billing_address,
            'shipping_address' => !empty($request->shipping_address) ? $request->shipping_address : '-',
            'special_instruct' => !empty($request->special_instruct) ? $request->special_instruct : '-',
            'item_variable' => $item_variable,
            'qty_total' => $qty_total,
            'sub_total' => Utility::priceFormat($sub_total),
            'shipping_amount' => Utility::priceFormat(!empty($shipping_price) ? $shipping_price : '0'),
            'item_tax' => Utility::priceFormat($total_tax),
            'item_total' => $request->total_price,
        ];


        if (isset($request->coupon_id) && !empty($request->coupon_id)) {
            $arr['discount_amount'] = !empty($request->dicount_price) ? $request->dicount_price : '0';
        }

        if (isset($request->finalprice) && !empty($request->finalprice)) {
            $arr['final_total'] = Utility::priceFormat($request->total_price);
        }

        $resp = Utility::replaceVariable($store->content, $arr);
        if ($request->type == 'telegram') {
            $msg = $resp;

            // Set your Bot ID and Chat ID.
            $telegrambot = $store->telegrambot;
            $telegramchatid = $store->telegramchatid;

            // Function call with your own text or variable
            $url = 'https://api.telegram.org/bot' . $telegrambot . '/sendMessage';
            $data = array(
                'chat_id' => $telegramchatid,
                'text' => $msg,
            );
            $options = array(
                'http' => array(
                    'method' => 'POST',
                    'header' => "Content-Type:application/x-www-form-urlencoded\r\n",
                    'content' => http_build_query($data),
                ),
            );
            $context = stream_context_create($options);
            $result = file_get_contents($url, false, $context);
            $url = $url;
        } else {
            $url = 'https://api.whatsapp.com/send?phone=' . $store->whatsapp_number . '&text=' . urlencode($resp);
            // $url = 'https://api.whatsapp.com/send?phone=' . $store->whatsapp_number . '&text=' . urlencode($resp);
        }
        $new_order_id = str_replace('#', '', $request->order_id);

        return response()->json(
            [
                'status' => 'success',
                'order_id' => Crypt::encrypt($new_order_id),
                'url' => $url,
            ]
        );
    }

    public function downloadable_prodcut(Request $request, $slug)
    {
        $store = Store::where('slug', $slug)->where('is_store_enabled', '1')->first();
        if (empty($store)) {
            return response()->json(
                [
                    'status' => __('error'),
                    'message' => __('Page Not Found.'),
                ]
            );
        }
        $order = Order::where('id', $request->order_id)->where('user_id', $store->id)->first();
        if ($order->status == 'pending') {
            return response()->json(
                [
                    'status' => __('error'),
                    'message' => __('Your product is not delivered.'),
                ]
            );
        }
        if ($order->status == 'Cancel Order') {
            return response()->json(
                [
                    'status' => __('error'),
                    'message' => __('Your Order is Cancelled.'),
                ]
            );
        }
        if ($order->status == 'delivered') {
            // if (isset($store->mail_driver) && !empty($store->mail_driver)) {
            
                try {
                    if (isset($store->mail_driver) && !empty($store->mail_driver)){
                        config(
                            [
                                'mail.driver'       => $store->mail_driver,
                                'mail.host'         => $store->mail_host,
                                'mail.port'         => $store->mail_port,
                                'mail.encryption'   => $store->mail_encryption,
                                'mail.username'     => $store->mail_username,
                                'mail.password'     => $store->mail_password,
                                'mail.from.address' => $store->mail_from_address,
                                'mail.from.name'    => $store->mail_from_name,
                            ]
                        );
                    } else {
                        $settings = Utility::settingsById(1);
                        config(
                            [
                                'mail.driver'       => isset($settings['mail_driver']) ? $settings['mail_driver'] : '',
                                'mail.host'         => isset($settings['mail_host']) ? $settings['mail_host'] : '',
                                'mail.port'         => isset($settings['mail_port']) ? $settings['mail_port'] : '',
                                'mail.encryption'   => isset($settings['mail_encryption']) ? $settings['mail_encryption'] : '',
                                'mail.username'     => isset($settings['mail_username']) ? $settings['mail_username'] : '',
                                'mail.password'     => isset($settings['mail_password']) ? $settings['mail_password'] : '',
                                'mail.from.address' => isset($settings['mail_from_address']) ? $settings['mail_from_address'] : '',
                                'mail.from.name'    => isset($settings['mail_from_name']) ? $settings['mail_from_name'] : '',
                            ]
                        );
                    }

                    Mail::to(
                        [
                            $order['email'],
                        ]
                    )->send(new ProdcutMail($order, $request['download_product'], $store));

                    return response()->json(
                        [
                            'status' => __('success'),
                            'msg' => __('Please check your email'),
                            'message' => __('successfully send'),
                        ]
                    );
                } catch (\Exception $e) {
                    return response()->json(
                        [
                            'status' => __('error'),
                            'msg' => __('Please contact your shop owner'),
                            'message' => __('E-Mail has been not sent due to SMTP configuration'),
                        ]
                    );
                }
            // } else {
            //     return response()->json(
            //         [
            //             'status' => __('error'),
            //             'msg' => __('Please contact your shop owner'),
            //             'message' => __('E-Mail has been not sent due to SMTP configuration'),
            //         ]
            //     );
            // }
        }
    }

    public function userCreate($slug)
    {
        $store = Store::where('slug', $slug)->where('is_store_enabled', '1')->first();
        if (empty($store)) {
            return redirect()->back()->with('error', __('Store not available'));
        }
        $cart = session()->get($slug);
        $total_item = 0;
        if (isset($cart['products'])) {
            if (isset($cart) && !empty($cart['products'])) {
                $total_item = count($cart['products']);
            } else {
                $total_item = 0;
            }
        }

        if (empty($store)) {
            return redirect()->back()->with('error', __('Store not available'));
        }

        return view('storefront.' . $store->theme_dir . '.user.create', compact('total_item', 'slug', 'store'));
    }

    protected function userStore($slug, Request $request)
    {
        $store = Store::where('slug', $slug)->where('is_store_enabled', '1')->first();
        if (empty($store)) {
            return redirect()->back()->with('error', __('Store not available'));
        }
        $view = 'grid';
        if (empty($store)) {
            return redirect()->back()->with('error', __('Store not available'));
        }
        $validate = Validator::make(
            $request->all(),
            [
                'name' => [
                    'required',
                    'string',
                    'max:255',
                ],
                'phone_number' => [
                    'required',
                    'max:255',
                ],
                'email' => [
                    'required',
                    'string',
                    'email',
                    'max:255',
                ],
                'password' => [
                    'required',
                    'string',
                    'min:8',
                    'confirmed',
                ],
            ]
        );
        $vali = Customer::where('email', $request->email)->where('store_id', $store->id)->where('phone_number', $request->phone_number)->count();
        if ($validate->fails()) {
            $message = $validate->getMessageBag();

            return redirect()->back()->with('error', $message->first());
        } elseif ($vali > 0) {
            return redirect()->back()->with('error', __('Email already exists'));
        }

        $customer = new Customer();
        $customer->name = $request->name;
        $customer->email = $request->email;
        $customer->phone_number = $request->phone_number;
        $customer->password = Hash::make($request->password);
        $customer->lang = !empty($settings['default_language']) ? $settings['default_language'] : 'en';
        $customer->avatar = 'avatar.png';
        $customer->store_id = $store->id;
        $customer->save();

        return redirect()->route('store.slug', $slug)->with('success', __('Account Created Successfully.'));

        //return redirect()->back()->with('success', __('Account Created Successfully.'));
    }

    public function userPayment($slug)
    {

        $store = Store::where('slug', $slug)->where('is_store_enabled', '1')->first();
        if (empty($store)) {
            return redirect()->route('store.slug', $slug);
        }
        $order = Order::where('user_id', $store->id)->orderBy('id', 'desc')->first();

        if (\Auth::check()) {
            $store_payments = Utility::getPaymentSetting();
        } else {
            $store_payments = Utility::getPaymentSetting($store->id);
        }

        if (empty($store)) {
            return redirect()->back()->with('error', __('Store not available'));
        }
        $cart = session()->get($slug);
        if (!empty($cart)) {
            $products = $cart['products'];
        } else {
            return redirect()->back()->with('error', __('Please add to product into cart'));
        }

        if (!empty($cart['customer'])) {
            $cust_details = $cart['customer'];
        } else {
            return redirect()->back()->with('error', __('Please add your information'));
        }
        $shippings = Shipping::where('store_id', $store->id)->get();

        if ($store->enable_shipping == 'on' && count($shippings) > 0) {
            if (!empty($cart['shipping'])) {
                $shipping = $cart['shipping'];
                $shipping_details = Shipping::where('store_id', $store->id)->where('id', $shipping['shipping_id'])->first();
                $shipping_price = floor($shipping_details->price);
            } else {
                return redirect()->back()->with('error', __('Please Select Shipping Location'));
            }
        } else {
            $shipping_price = 0;
        }

        if (!empty($cart['coupon'])) {
            $discount_price = $cart['coupon']['discount_price'];
            $coupon_price = str_replace('-' . $store->currency, '', $cart['coupon']['discount_price']);
            $coupon_id = $cart['coupon']['data_id'];
        } else {
            $discount_price = Utility::priceFormat(0);
            $coupon_price = 0;
            $coupon_id = 0;
        }

        $store = Store::where('slug', $slug)->first();
        $tax_name = [];
        $tax_price = [];
        $i = 0;
        if (!empty($products)) {
            if (!empty($cust_details)) {
                foreach ($products as $product) {
                    if ($product['variant_id'] != 0) {
                        foreach ($product['tax'] as $key => $taxs) {

                            if (!in_array($taxs['tax_name'], $tax_name)) {
                                $tax_name[] = $taxs['tax_name'];
                                $price = $product['variant_price'] * $product['quantity'] * $taxs['tax'] / 100;
                                $tax_price[] = $price;
                            } else {
                                $price = $product['variant_price'] * $product['quantity'] * $taxs['tax'] / 100;
                                $tax_price[array_search($taxs['tax_name'], $tax_name)] += $price;
                            }
                        }
                    } else {

                        foreach ($product['tax'] as $key => $taxs) {
                            if (!in_array($taxs['tax_name'], $tax_name)) {
                                $tax_name[] = $taxs['tax_name'];
                                $price = $product['price'] * $product['quantity'] * $taxs['tax'] / 100;
                                $tax_price[] = $price;
                            } else {
                                $price = $product['price'] * $product['quantity'] * $taxs['tax'] / 100;
                                $tax_price[array_search($taxs['tax_name'], $tax_name)] += $price;
                            }
                        }
                    }
                    $i++;
                }
                $encode_product = json_encode($products);
                $total_item = $i;
                $taxArr['tax'] = $tax_name;
                $taxArr['rate'] = $tax_price;

                return view('storefront.' . $store->theme_dir . '.payment', compact('coupon_id', 'discount_price', 'coupon_price', 'products', 'order', 'cust_details', 'store', 'taxArr', 'total_item', 'encode_product', 'shipping_price', 'store_payments', 'cart'));
            } else {
                return redirect()->back()->with('error', __('Please fill your details.'));
            }
        } else {
            return redirect()->back()->with('error', __('Please add to product into cart.'));
        }
    }
    public function storesession(Request $request, $slug)
    {
        $store = Store::where('slug', $slug)->where('is_store_enabled', '1')->first();
        if ((!empty(Auth::guard('customers')->user()) && $store->is_checkout_login_required == 'on') || $store->is_checkout_login_required == 'off') {
            $store = Store::where('slug', $slug)->where('is_store_enabled', '1')->first();
            if (empty($store)) {

                return response()->json(
                    [
                        'status' => 'error',
                        'success' => __('Store not available.'),
                    ]
                );
            }
            $shipping = Shipping::where('store_id', $store->id)->first();
            if (!empty($shipping) && $store->enable_shipping == 'on') {
                if ($request->shipping_price == '0.00') {
                    return response()->json(
                        [
                            'status' => 'error',
                            'success' => __('Please select shipping.'),
                        ]
                    );
                }
            }
            $validator = \Validator::make(
                $request->all(),
                [
                    'name' => 'required|max:120',
                    'phone' => 'required',
                    'billing_address' => 'required',
                ]
            );
            if ($validator->fails()) {
                return response()->json(
                    [
                        'status' => 'error',
                        'success' => __('All field is required.'),
                    ]
                );
            }
            $cart = session()->get($slug);

            $product = $cart['products'];
            $all_products = $cart;
            if ($product) {

                $response_data = [
                    'coupon_id' => $request->coupon_id,
                    'dicount_price' => $request->dicount_price,
                    'shipping_price' => $request->shipping_price,
                    'shipping_name' => $request->shipping_name,
                    'total_price' => $request->total_price,
                    'order_id' => $request->order_id,
                    'name' => $request->name,
                    'email' => $request->email,
                    'phone' => $request->phone,
                    'custom_field_title_1' => $request->custom_field_title_1,
                    'custom_field_title_2' => $request->custom_field_title_2,
                    'custom_field_title_3' => $request->custom_field_title_3,
                    'custom_field_title_4' => $request->custom_field_title_4,
                    'billing_address' => $request->billing_address,
                    'special_instruct' => $request->special_instruct,
                    'shipping_address' => $request->shipping_address,
                    'shipping_id' => $request->shipping_id,
                    'all_products' => $all_products,
                ];
                $cart['response_data'] = $response_data;
                session()->put($slug, $cart);
                return response()->json(
                    [
                        'status' => 'success',
                        'success' => __('Session successfully added'),
                    ]
                );
            } else {
                return response()->json(
                    [
                        'status' => 'error',
                        'success' => __('Failed'),
                    ]
                );
            }
        } else {

            return response()->json(
                [
                    'status' => 'error',
                    'success' => __('You need to login'),
                ]
            );
        }
    }

    public function paymentwallstoresession(Request $request, $slug)
    {
        $store = Store::where('slug', $slug)->where('is_store_enabled', '1')->first();
        if ((!empty(Auth::guard('customers')->user()) && $store->is_checkout_login_required == 'on') || $store->is_checkout_login_required == 'off') {
            $store = Store::where('slug', $slug)->first();
            if (empty($store)) {
                return redirect()->back()->with('error', __('Store not available.'));
            }
            $shipping = Shipping::where('store_id', $store->id)->first();
            if (!empty($shipping) && $store->enable_shipping == 'on') {
                if ($request->shipping_price == '0.00') {
                    return redirect()->back()->with('error', __('Please select shipping.'));
                }
            }
            $validator = \Validator::make(
                $request->all(),
                [
                    'name' => 'required|max:120',
                    'phone' => 'required',
                    'billing_address' => 'required',
                ]
            );
            if ($validator->fails()) {
                return redirect()->back()->with('error', __('All field is required.'));
            }
            $cart = session()->get($slug);
            $product = $cart['products'];
            $all_products = $cart;
            if ($product) {

                $response_data = [
                    'coupon_id' => $request->coupon_id,
                    'dicount_price' => $request->dicount_price,
                    'shipping_price' => $request->shipping_price,
                    'shipping_name' => $request->shipping_name,
                    'total_price' => $request->total_price,
                    'order_id' => $request->order_id,
                    'name' => $request->name,
                    'email' => $request->email,
                    'phone' => $request->phone,
                    'custom_field_title_1' => $request->custom_field_title_1,
                    'custom_field_title_2' => $request->custom_field_title_2,
                    'custom_field_title_3' => $request->custom_field_title_3,
                    'custom_field_title_4' => $request->custom_field_title_4,
                    'billing_address' => $request->billing_address,
                    'special_instruct' => $request->special_instruct,
                    'shipping_address' => $request->shipping_address,
                    'shipping_id' => $request->shipping_id,
                    'all_products' => $all_products,
                ];
                $cart['response_data'] = $response_data;
                session()->put($slug, $cart);
                return redirect()->route('paymentwall.index', $slug)->with('success', __('Session successfully added'));
            } else {
                return redirect()->back()->with('error', __('Failed'));
            }
        } else {
            return redirect()->back()->with('error', __('You need to login'));
        }
    }

    public function userPassword($id)
    {
        if (\Auth::user()->can('Reset Password')) {
            $user = User::where('id', \Crypt::decrypt($id))->first();

            return view('admin_store.reset', compact('user'));
        } else {
            return redirect()->back()->with('error', __('Permission Denied'));
        }
    }
    public function userPasswordReset(Request $request, $id)
    {
        if (\Auth::user()->can('Reset Password')) {
            $validator = \Validator::make(
                $request->all(),
                [
                    'password' => 'required|confirmed|same:password_confirmation',
                ]
            );
            if ($validator->fails()) {
                $messages = $validator->getMessageBag();
                return redirect()->back()->with('error', $messages->first());
            }
            $user = User::where('id', $id)->first();
            if (isset($request->login_enable) && $request->login_enable == true) {
                $user->forceFill([
                    'password'  => Hash::make($request->password),
                    'is_active' => 1,
                    'is_enable_login' => 1,
                ])->save();
                return redirect()->back()->with(
                    'success',
                    'Owner login enable successfully.'
                );
            } else {
                $user->forceFill([
                    'password' => Hash::make($request->password),
                ])->save();
                return redirect()->back()->with(
                    'success',
                    'Owner Password successfully updated.'
                );
            }
        } else {
            return redirect()->back()->with('error', __('Permission Denied'));
        }
    }
    public function orderview($product_id, $customer_id, $slug)
    {
        // dd($product_id);
        // $order_id = PurchasedProducts::where('product_id', $product_id)->where('customer_id', $customer_id)->get()->pluck('order_id');

        $orders = Order::where('id', $product_id)->get();
        // dd($orders);
        $store = Store::where('slug', $slug)->where('is_store_enabled', '1')->first();
        if (empty($store)) {
            return abort('404', 'Page not found');
        }
        $all_orders = [];
        foreach ($orders as $key => $order) {

            $order_data = [];
            $order_data = $order;
            if (!empty($order->coupon_json)) {
                $coupon = json_decode($order->coupon_json);
            }
            if (!empty($order->discount_price)) {
                $discount_price = $order->discount_price;
            } else {
                $discount_price = '';
            }

            if (!empty($order->shipping_data)) {
                $shipping_data = json_decode($order->shipping_data);
                $location_data = Location::where('id', $shipping_data->location_id)->first();
            } else {
                $shipping_data = '';
                $location_data = '';
            }

            $user_details = UserDetail::where('id', $order->user_address_id)->first();
            $order_products = json_decode($order->product);

            $sub_total = 0;

            if (!empty($order_products)) {
                $grand_total = 0;
                $discount_value = 0;
                $final_taxs = 0;

                foreach ($order_products->products as $product) {
                    if (isset($product->variant_id) && $product->variant_id != 0) {
                        $total_taxs = 0;
                        if (!empty($product->tax)) {
                            foreach ($product->tax as $tax) {
                                $sub_tax = ($product->variant_price * $product->quantity * $tax->tax) / 100;
                                $total_taxs += $sub_tax;
                                $final_taxs += $sub_tax;
                            }
                        } else {
                            $total_taxs = 0;
                        }

                        $totalprice = $product->variant_price * $product->quantity + $total_taxs;
                        $subtotal1 = $product->variant_price * $product->quantity;

                        $sub_total += $subtotal1;
                        $grand_total += $totalprice;
                    } else {
                        if (!empty($product->tax)) {
                            $total_taxs = 0;
                            foreach ($product->tax as $tax) {
                                $sub_tax = ($product->price * $product->quantity * $tax->tax) / 100;
                                $total_taxs += $sub_tax;
                                $final_taxs += $sub_tax;
                            }
                        } else {
                            $total_taxs = 0;
                        }

                        $totalprice = $product->price * $product->quantity + $final_taxs;
                        $subtotal1 = $product->price * $product->quantity;
                        $sub_total += $subtotal1;
                        $grand_total += $totalprice;
                    }
                }
            }

            if (!empty($coupon)) {
                if ($coupon->enable_flat == 'on') {
                    $discount_value = $coupon->flat_discount;
                } else {
                    $discount_value = ($grand_total / 100) * $coupon->discount;
                }
            }
            $order_data->order_products = $order_products;
            $order_data->grand_total = $grand_total;
            $order_data->sub_total = $sub_total;
            $order_data->total_taxs = $total_taxs;
            $order_data->user_details = $user_details;
            $order_data->shipping_data = $shipping_data;
            $order_data->location_data = $location_data;
            $order_data->discount_price = $discount_price;
            $order_data->discount_value = $discount_value;
            $order_data->final_taxs = $final_taxs;

            $all_orders[$key] = $order_data;
        }
        return view('storefront.' . $store->theme_dir . '.orderview', compact('slug', 'store', 'all_orders', 'orders', 'shipping_data', 'location_data'));
    }
    public function storeenable($userid)
    {

        if (\Auth::user()->type == 'super admin') {
            $users = User::select(
                [
                    'users.*',
                    'stores.is_store_enabled as store_display',
                ]
            )->join('stores', 'stores.created_by', '=', 'users.id')->where('users.id', $userid)->where('users.type', '=', 'Owner')->groupBy('users.id')->first();

            return view('admin_store.storeenabled', compact('users'));
        } else {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }

    public function storeenableupdate(Request $request, $userid)
    {
        $users = User::select(
            [
                'users.*',
                'stores.is_store_enabled as store_display',
            ]
        )->join('stores', 'stores.created_by', '=', 'users.id')->where('users.id', $userid)->where('users.type', '=', 'Owner')->groupBy('users.id')->first();
        $stores = Store::where('created_by', $users->creatorId())->get();

        foreach ($stores as $key => $value) {
            if ($value->is_store_enabled == 1) {
                $value->is_store_enabled = 0;
                $value->update();
                $msg = "store disabled successfully...";
            } elseif ($value->is_store_enabled == 0) {
                $value->is_store_enabled = 1;
                $value->update();
                $msg = "store enable successfully...";
            }
        }

        return redirect()->back()->with('success', __($msg));
    }

    public function customerindex()
    {
        if (\Auth::user()->can('Manage Customers')) {
            if (\Auth::user()->type != 'super admin') {
                $user = \Auth::user()->current_store;
                $customers = Customer::where('store_id', \Auth::user()->current_store)->get();
                $store = Store::where('id', $user)->first();

                return view('customer.index', compact('customers', 'store'));
            } else {
                return redirect()->back()->with('error', __('Permission denied.'));
            }
        } else {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }

    public function exports()
    {
        $name = 'Customer' . date('Y-m-d i:h:s');
        $data = Excel::download(new CustomerExport(), $name . '.xlsx');

        return $data;
    }

    public function customershow($id)
    {
        if (\Auth::user()->can('Show Customers')) {
            if (\Auth::user()->type != 'super admin') {
                $user = \Auth::user();
                $customer = Customer::where('id', $id)->first();
                if (isset($customer) && $user->current_store == $customer->store_id) {
                    $orders = Order::where('customer_id', $id)->get();
                    $store = Store::where('id', $user->current_store)->first();
                    return view('orders.index', compact('orders', 'store'));
                } else {
                    return redirect()->back()->with('error', __('Permission denied.'));
                }
            } else {
                return redirect()->back()->with('error', __('Permission denied.'));
            }
        } else {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }


    public function StoreCart($slug, $product_id = null, $quantity = null, $variant_name = null)
    {
        if (isset($product_id) && isset($quantity)) {
            $store = Store::where('slug', $slug)->where('is_store_enabled', '1')->get();
            if (empty($store)) {
                return abort('404', 'Page not found');
            }
            $variant = ProductVariantOption::where('name', $variant_name)->where('product_id', $product_id)->first();
            $product = Product::find($product_id);

            if (!empty($product->is_cover)) {
                $pro_img = $product->is_cover;
            } else {
                $pro_img = 'default.png';
            }

            $productquantity = $product->quantity;

            if ($quantity == 0) {
                return response()->json(
                    [
                        'code' => 404,
                        'status' => 'Error',
                        'error' => __('This product is out of stock!'),
                    ]
                );
            }
            $productname = $product->name;
            $productprice = $product->price != 0 ? $product->price : 0;
            $originalquantity = (int) $productquantity;
            $taxes = Utility::tax($product->product_tax);
            $itemTaxes = [];
            $producttax = 0;

            if (!empty($taxes)) {
                foreach ($taxes as $tax) {
                    if (!empty($tax)) {
                        $producttax = Utility::taxRate($tax->rate, $product->price, $quantity);
                        $itemTax['tax_name'] = $tax->name;
                        $itemTax['tax'] = $tax->rate;
                        $itemTaxes[] = $itemTax;
                    }
                }
            }

            $subtotal = Utility::priceFormat($productprice + $producttax);

            if (isset($variant_name)) {
                $variant_itemTaxes = [];
                $variant_name = $variant->name;
                $variant_price = $variant->price;
                $originalvariantquantity = $variant->quantity;
                //variant count tax
                $variant_taxes = Utility::tax($product->product_tax);
                $variant_producttax = 0;

                if (!empty($variant_taxes)) {
                    foreach ($variant_taxes as $variant_tax) {
                        if (!empty($variant_tax)) {
                            $variant_producttax = Utility::taxRate($variant_tax->rate, $variant_price, $quantity);
                            $itemTax['tax_name'] = $variant_tax->name;
                            $itemTax['tax'] = $variant_tax->rate;
                            $variant_itemTaxes[] = $itemTax;
                        }
                    }
                }
                $variant_subtotal = Utility::priceFormat($variant_price * $variant->quantity);
            }
            $time = time();
            if (isset($variant_name)) {
                $cart['products'][$time] = [
                    "product_id" => $product->id,
                    "product_name" => $productname,
                    "image" => Utility::get_file('uploads/is_cover_image/' . $pro_img),
                    "quantity" => $quantity,
                    "price" => $productprice,
                    "id" => $product_id,
                    "downloadable_prodcut" => $product->downloadable_prodcut,
                    "tax" => $variant_itemTaxes,
                    "subtotal" => $subtotal,
                    "originalquantity" => $originalquantity,
                    "variant_name" => $variant_name,
                    "variant_price" => $variant_price,
                    "variant_qty" => $variant->quantity,
                    "variant_subtotal" => $variant_subtotal,
                    "originalvariantquantity" => $originalvariantquantity,
                    'variant_id' => $variant->id,
                ];
            } else {
                $cart['products'][$time] = [
                    "product_id" => $product->id,
                    "product_name" => $productname,
                    "image" => Utility::get_file('uploads/is_cover_image/' . $pro_img),
                    "quantity" => $quantity,
                    "price" => $productprice,
                    "id" => $product_id,
                    "downloadable_prodcut" => $product->downloadable_prodcut,
                    "tax" => $itemTaxes,
                    "subtotal" => $subtotal,
                    "originalquantity" => $originalquantity,
                    'variant_id' => 0,
                    'cover_image' => $product->is_cover,
                ];
            }

            session()->put($slug, $cart);
            return redirect()->route('store.cart', $slug);
        }

        if (!empty(\Auth::guard('customers')->user())) {
            $user = \Auth::guard('customers')->user();
            $store_settings = Store::where('id', $user->store_id)->first();
        } else {
            $store_settings = Store::where('slug', $slug)->first();
        }

        $store = Store::where('slug', $slug)->where('is_store_enabled', '1')->first();
        if (empty($store)) {
            return redirect()->route('store.slug', $slug);
        }

        if (isset($store->lang)) {

            $lang = session()->get('lang');

            if (!isset($lang)) {
                session(['lang' => $store->lang]);
                $storelang = session()->get('lang');
                \App::setLocale(isset($storelang) ? $storelang : 'en');
            } else {
                session(['lang' => $lang]);
                $storelang = session()->get('lang');
                \App::setLocale(isset($storelang) ? $storelang : 'en');
            }
        }
        $cart = session()->get($slug);

        if (!empty($cart)) {
            $products = $cart;
        } else {
            $products = '';
        }
        $total_item = 0;
        if (isset($cart['products'])) {
            if (isset($cart) && !empty($cart['products'])) {
                $total_item = count($cart['products']);
            } else {
                $total_item = 0;
            }
        }

        return view('storefront.' . $store->theme_dir . '.cart', compact('products', 'total_item', 'store', 'store_settings'));
    }

    public function storelinks($id){
        $users       = User::find($id);
        $stores   = Store::where('email', $users->email)->get();
        $storesNames   = Store::where('email', $users->email)->get()->pluck('name','id');
        if ($stores) {
            foreach ($stores as $store) {
                if ($store['enable_storelink'] == 'on') {
                    $app_url = trim(env('APP_URL'), '/');
                    $store['store_url'] = $app_url . '/store/' . $store['slug'];
                } else if ($store['enable_domain'] == 'on') {
                    $store['store_url'] = 'https://' . $store['domains'] . '/';
                } else {
                    $store['store_url'] = 'https://' . $store['subdomain'] . '/';
                }
            }

            return view('admin_store.storelinks', compact('users', 'stores','storesNames'));
        }

    }

    public function LoginManage($id)
    {
        if(Auth::user()->can('Reset Password'))
        {
            $oId        = \Crypt::decrypt($id);
            $user = User::find($oId);
            if($user->is_active == 1)
            {
                $user->is_active = 0;
                $user->is_enable_login = 0;
                $user->save();
                return redirect()->back()->with('success', 'Owner login disable successfully.');
            }
            else
            {
                $user->is_active = 1;
                $user->is_enable_login = 1;
                $user->save();
                return redirect()->back()->with('success', 'Owner login enable successfully.');
            }

        }
        else
        {
            return redirect()->back()->with('error', 'Permission denied.');
        }
    }
}
