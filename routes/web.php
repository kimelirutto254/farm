<?php

use App\Http\Controllers\InspectorController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\StoreController;
use App\Http\Controllers\LanguageController;
use App\Http\Controllers\PlanRequestController;
use App\Http\Controllers\SettingController;
use App\Http\Controllers\PlanController;
use App\Http\Controllers\CouponController;
use App\Http\Controllers\EmailTemplateController;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\PermissionController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\WebhookController;
use App\Http\Controllers\FarmerController;
use App\Http\Controllers\InspectionController;
use App\Http\Controllers\SupplierController;
use App\Http\Controllers\DucumentUploadController;
use App\Http\Controllers\ConfigurationController;





/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
 */

require __DIR__ . '/auth.php';


Route::get('/login/{lang?}', [AuthenticatedSessionController::class, 'showLoginForm'])->name('login');
Route::get('/register/{ref_id?}/{lang?}', [RegisteredUserController::class, 'showRegistrationForm'])->name('register');
Route::get('/password/forgot/{lang?}', [AuthenticatedSessionController::class, 'showLinkRequestForm'])->name('change.langPass');

Route::get('/', [DashboardController::class, 'index'])->middleware('XSS')->name('dashboard');

Route::group(['middleware' => ['verified']], function () {

    Route::get('/dashboard', [DashboardController::class, 'index'])->middleware(['auth', 'XSS'])->name('dashboard');

    Route::middleware(['auth'])->group(function () {

        Route::resource('stores', StoreController::class);
        Route::post('store-setting/{id}', [StoreController::class, 'savestoresetting'])->name('settings.store');
    });

    Route::middleware(['auth', 'XSS'])->group(function () {
        Route::get('change-language/{lang}', [LanguageController::class, 'changeLanquage'])->name('change.language')->middleware(['auth', 'XSS']);
        Route::get('manage-language/{languages}', [LanguageController::class, 'manageLanguage'])->name('manage.language')->middleware(['auth', 'XSS']);
        Route::post('store-language-data/{lang}', [LanguageController::class, 'storeLanguageData'])->name('store.language.data')->middleware(['auth', 'XSS']);
        Route::post('disable-language', [LanguageController::class, 'disableLang'])->name('disablelanguage')->middleware(['auth', 'XSS']);
        Route::get('create-language', [LanguageController::class, 'createLanguage'])->name('create.language')->middleware(['auth', 'XSS']);
        Route::post('store-language', [LanguageController::class, 'storeLanguage'])->name('store.language')->middleware(['auth', 'XSS']);
        Route::delete('/lang/{lang}', [LanguageController::class, 'destroyLang'])->name('lang.destroy')->middleware(['auth', 'XSS']);
    });
    Route::resource('media-upload', DucumentUploadController::class)->middleware(
        [
            'auth',
            'XSS',
        ]
    );
    Route::middleware(['auth', 'XSS'])->group(function () {
        Route::get('store-grid/grid', [StoreController::class, 'grid'])->name('store.grid');
        Route::get('store-customDomain/customDomain', [StoreController::class, 'customDomain'])->name('store.customDomain');
        Route::get('store-subDomain/subDomain', [StoreController::class, 'subDomain'])->name('store.subDomain');
        Route::get('store-plan/{id}/plan', [StoreController::class, 'upgradePlan'])->name('plan.upgrade');
        Route::get('store-plan-active/{id}/plan/{pid}', [StoreController::class, 'activePlan'])->name('plan.active');
        Route::DELETE('store-delete/{id}', [StoreController::class, 'storedestroy'])->name('user.destroy');
        Route::DELETE('ownerstore-delete/{id}', [StoreController::class, 'ownerstoredestroy'])->name('ownerstore.destroy');
        Route::get('store-edit/{id}', [StoreController::class, 'storedit'])->name('user.edit');
        Route::Put('store-update/{id}', [StoreController::class, 'storeupdate'])->name('user.update');
    });

    Route::get('plan_request', [PlanRequestController::class, 'index'])->name('plan_request.index')->middleware(['auth', 'XSS']);
    Route::get('request_frequency/{id}', [PlanRequestController::class, 'requestView'])->name('request.view')->middleware(['auth', 'XSS']);
    Route::get('request_send/{id}', [PlanRequestController::class, 'userRequest'])->name('send.request')->middleware(['auth', 'XSS']);
    Route::get('request_response/{id}/{response}', [PlanRequestController::class, 'acceptRequest'])->name('response.request')->middleware(['auth', 'XSS']);
    Route::get('request_cancel/{id}', [PlanRequestController::class, 'cancelRequest'])->name('request.cancel')->middleware(['auth', 'XSS']);


    Route::get('/store-change/{id}', [StoreController::class, 'changeCurrantStore'])->name('change_store')->middleware(['auth', 'XSS']);

    Route::get('/change/mode', [DashboardController::class, 'changeMode'])->name('change.mode');
    Route::get('profile', [DashboardController::class, 'profile'])->name('profile')->middleware(['auth', 'XSS']);


    Route::put('change-password', [DashboardController::class, 'updatePassword'])->name('update.password');
    Route::put('edit-profile', [DashboardController::class, 'editprofile'])->name('update.account')->middleware(['auth', 'XSS']);


    Route::middleware(['auth', 'XSS'])->group(function () {
        Route::post('business-setting', [SettingController::class, 'saveBusinessSettings'])->name('business.setting');
        Route::post('company-setting', [SettingController::class, 'saveCompanySettings'])->name('company.setting');
        Route::post('email-setting', [SettingController::class, 'saveEmailSettings'])->name('email.setting');
        Route::post('system-setting', [SettingController::class, 'saveSystemSettings'])->name('system.setting');
        Route::post('pusher-setting', [SettingController::class, 'savePusherSettings'])->name('pusher.setting');
        Route::post('/change-pin/{inspector_id}', [InspectorController::class, 'changePin'])->name('pin.change');


        Route::post('test-mail', [SettingController::class, 'testMail'])->name('test.mail')->middleware(['auth', 'XSS']);
        Route::get('test-mail', [SettingController::class, 'testMail'])->name('test.mail')->middleware(['auth', 'XSS']);
        Route::post('test-mail/send', [SettingController::class, 'testSendMail'])->name('test.send.mail')->middleware(['auth', 'XSS']);

        Route::get('settings', [SettingController::class, 'index'])->name('settings');
    });

    Route::post('payment-setting', [SettingController::class, 'savePaymentSettings'])->name('payment.setting')->middleware(['auth']);




    Route::get('/config-cache', function () {
        Artisan::call('cache:clear');
        Artisan::call('route:clear');
        Artisan::call('view:clear');
        Artisan::call('optimize:clear');
        return redirect()->back()->with('success', 'Clear Cache successfully.');
    });

    Route::post('cookie-setting', [SettingController::class, 'saveCookieSettings'])->name('cookie.setting');
    Route::any('/cookie-consent', [SettingController::class, 'CookieConsent'])->name('cookie-consent');

   

    


    Route::resource('webhook', WebhookController::class)->middleware(['auth', 'XSS',]);

    Route::get('/plans', [PlanController::class, 'index'])->middleware('XSS', 'auth')->name('plans.index');

    Route::get('/plans/create', [PlanController::class, 'create'])->middleware('XSS', 'auth')->name('plans.create');

    Route::post('/plans', [PlanController::class, 'store'])->middleware('XSS', 'auth')->name('plans.store');

    Route::get('/plans/edit/{id}', [PlanController::class, 'edit'])->middleware('XSS', 'auth')->name('plans.edit');

    Route::put('/plans/{id}', [PlanController::class, 'update'])->middleware('XSS', 'auth')->name('plans.update');
    
    Route::delete('plans/delete/{id}',[PlanController::class,'destroy'])->name('plans.destroy')->middleware(['auth', 'XSS']);

    Route::post('/user-plans/', [PlanController::class, 'userPlan'])->middleware('XSS', 'auth')->name('update.user.plan');

    Route::get('plan-trial/{id}', [PlanController::class,'planTrial'])->name('plan.trial')->middleware(['auth', 'XSS']);


    Route::post('pwa-settings/{id}', [StoreController::class, 'pwasetting'])->name('setting.pwa')->middleware(['auth', 'XSS']);
    Route::get('/company-resource/edit/display/{id}', [StoreController::class, 'storeenable'])->middleware('XSS', 'auth')->name('store-resource.edit.display');
    Route::put('/company-resource/display/{id}', [StoreController::class, 'storeenableupdate'])->middleware('XSS', 'auth')->name('store-resource.display');

    Route::middleware(['auth', 'XSS'])->group(function () {

        Route::resource('company-resource', StoreController::class);
    });



    Route::get('/apply-coupon', [CouponController::class, 'applyCoupon'])->middleware('XSS', 'auth')->name('apply.coupon');

    Route::resource('coupons', CouponController::class)->middleware(['auth', 'XSS']);

    Route::post('prepare-payment', [PlanController::class, 'preparePayment'])->middleware('XSS', 'auth')->name('prepare.payment');


    Route::post('/payment/{code}', [PlanController::class, 'payment'])->middleware('XSS', 'auth')->name('payment');

    Route::get(
        'qr-code',
        function () {
            return QrCode::generate();
        }
    );


    Route::get('plan/prepare-amount', [PlanController::class, 'planPrepareAmount'])->name('plan.prepare.amount');


    Route::get(
        '/get_landing_page_section/{name}',
        function ($name) {
            $plans = DB::table('plans')->get();
            return view('custom_landing_page.' . $name, compact('plans'));
        }
    );

    Route::get('email_template_lang/{lang?}', [EmailTemplateController::class, 'emailTemplate'])->name('email_template')->middleware('auth', 'XSS');
    Route::get('email_template_lang/{id}/{lang?}', [EmailTemplateController::class, 'manageEmailLang'])->name('manage.email.language')->middleware('auth', 'XSS');
    Route::put('email_template_lang/{id}/', [EmailTemplateController::class, 'updateEmailSettings'])->name('updateEmail.settings')->middleware('auth');
    Route::put('email_template_store/{pid}', [EmailTemplateController::class, 'storeEmailLang'])->name('store.email.language')->middleware('auth', 'XSS');
    Route::put('email_template_status/{id}', [EmailTemplateController::class, 'updateStatus'])->name('status.email.language')->middleware('auth', 'XSS');
    Route::put('email_template_status/{id}', [EmailTemplateController::class, 'updateStatus'])->name('email_template.update')->middleware('auth', 'XSS');

    Route::get('export/customer', [StoreController::class, 'exports'])->name('customer.exports');
    Route::get('export/store', [StoreController::class, 'export'])->name('store.export');
    Route::get('export/coupons', [CouponController::class, 'export'])->name('coupons.export');
    Route::get('export/plan_requests', [PlanRequestController::class, 'export'])->name('planrequests.export');


    /*==================================Recaptcha====================================================*/
    Route::post('/recaptcha-settings', [SettingController::class, 'recaptchaSettingStore'])->name('recaptcha.settings.store')->middleware('auth', 'XSS');

    /*==============================================================================================================================*/

    Route::any('user-reset-password/{id}', [StoreController::class, 'userPassword'])->name('user.reset');
    Route::post('user-reset-password/{id}', [StoreController::class, 'userPasswordReset'])->name('user.password.update');
    Route::get('user-login/{id}', [StoreController::class, 'LoginManage'])->name('users.login');

    Route::get('/customer', [StoreController::class, 'customerindex'])->name('customer.index')->middleware('XSS');
    Route::get('/customer/view/{id}', [StoreController::class, 'customershow'])->name('customer.show')->middleware('XSS');

    Route::post('storage-settings', [SettingController::class, 'storageSettingStore'])->name('storage.setting.store')->middleware('auth', 'XSS');
});



Route::middleware(['auth', 'XSS'])->group(function () {
    Route::get('pixels', [SettingController::class, 'index'])->name('pixel.index');
    Route::get('pixel/create', [SettingController::class, 'pixel_create'])->name('pixel.create');
    Route::post('pixel', [SettingController::class, 'pixel_store'])->name('pixel.store');
    Route::delete('pixel-delete/{id}', [SettingController::class, 'pixeldestroy'])->name('pixel.destroy');
});

Route::get('store/remove-session/{slug}', [StoreController::class, 'removeSession'])->name('remove.session');

Route::get('store/{slug?}/{view?}', [StoreController::class, 'storeSlug'])->name('store.slug')->middleware('domain-check');
Route::get('store-variant/{slug?}/product/{id}', [StoreController::class, 'storeVariant'])->name('store-variant.variant');
Route::post('user-product_qty/{slug?}/product/{id}/{variant_id?}', [StoreController::class, 'productqty'])->name('user-product_qty.product_qty');
Route::post('user-location/{slug}/location/{id}', [StoreController::class, 'UserLocation'])->name('user.location');
Route::post('user-shipping/{slug}/shipping/{id}', [StoreController::class, 'UserShipping'])->name('user.shipping');
Route::delete('delete_cart_item/{slug?}/product/{id}/{variant_id?}', [StoreController::class, 'delete_cart_item'])->name('delete.cart_item');

Route::get('store/{slug?}/product/{id}', [StoreController::class, 'productView'])->name('store.product.product_view');
Route::get('store-complete/{slug?}/{id}', [StoreController::class, 'complete'])->name('store-complete.complete');

Route::get('/{slug?}/order/{id}', [StoreController::class, 'userorder'])->name('user.order');

Route::post('{slug?}/whatsapp', [StoreController::class, 'whatsapp'])->name('user.whatsapp');
Route::post('{slug?}/telegram', [StoreController::class, 'telegram'])->name('user.telegram');

Route::get('change-language-store/{slug?}/{lang}', [LanguageController::class, 'changeLanquageStore'])->middleware('XSS')->name('change.languagestore');

Route::post('store/{slug?}', [StoreController::class, 'changeTheme'])->name('store.changetheme');


Route::post('/store/custom-msg/{slug}', [StoreController::class, 'customMassage'])->name('customMassage');
Route::post('store/get-massage/{slug}', [StoreController::class, 'getWhatsappUrl'])->name('get.whatsappurl');

Route::post('store/{slug}/downloadable_prodcut', [StoreController::class, 'downloadable_prodcut'])->name('user.downloadable_prodcut');

Route::get('{slug}/user-create', [StoreController::class, 'userCreate'])->name('store.usercreate');
Route::post('{slug}/user-create', [StoreController::class, 'userStore'])->name('store.userstore');

Route::get('{slug}/customer-home', [StoreController::class, 'customerHome'])->name('customer.home')->middleware('customerauth');


Route::post('{slug?}/cod', [StoreController::class, 'cod'])->name('user.cod');
Route::post('{slug?}/bank_transfer', [StoreController::class, 'bank_transfer'])->name('user.bank_transfer');

Route::post('{slug}/paystack/store-slug/', [StoreController::class, 'storesession'])->name('paystack.session.store');

Route::get('store/product/{order_id}/{customer_id}/{slug}', [StoreController::class, 'orderview'])->name('store.product.product_order_view');

Route::get('/store-resource/edit/display/{id}', [StoreController::class, 'storeenable'])->middleware('XSS', 'auth')->name('store-resource.edit.display');
Route::put('/store-resource/display/{id}', [StoreController::class, 'storeenableupdate'])->middleware('XSS', 'auth')->name('store-resource.display');

Route::resource('roles', RoleController::class)->middleware(['auth', 'XSS']);
Route::resource('users', UserController::class)->middleware(['auth', 'XSS']);
Route::get('users/reset/{id}', [UserController::class, 'reset'])->name('users.reset')->middleware(['auth', 'XSS']);
Route::post('users/reset/{id}', [UserController::class, 'updatePassword'])->name('users.resetpassword')->middleware(['auth', 'XSS']);
Route::get('owner-user-login/{id}', [UserController::class, 'UserLoginManage'])->name('owner.users.login');
//
Route::resource('inspectors', InspectorController::class)->middleware(['auth', 'XSS']);
Route::get('inspectors/show/{id}', [InspectorController::class, 'show'])->name('inspector.show')->middleware(['auth', 'XSS']);
Route::get('inspectors/delete/{id}', [InspectorController::class, 'delete'])->name('inspector.delete')->middleware(['auth', 'XSS']);
Route::get('inspectors/edit/{id}', [InspectorController::class, 'edit'])->name('inspector.edit')->middleware(['auth', 'XSS']);


Route::get('inspectors/reset/{id}', [InspectorController::class, 'reset'])->name('inspectors.reset')->middleware(['auth', 'XSS']);
Route::post('inspectors/reset/{id}', [InspectorController::class, 'updatePin'])->name('inspectors.resetpassword')->middleware(['auth', 'XSS']);
Route::get('owner-inspector-login/{id}', [InspectorController::class, 'UserLoginManage'])->name('owner.inspectors.login');
// web.php
Route::get('inspectors-import', [InspectorController::class, 'importForm'])->name('inspectors.importForm');
Route::post('inspectors-import', [InspectorController::class, 'import'])->name('inspectors.import');

//
Route::resource('permissions', PermissionController::class)->middleware(['auth', 'XSS',]);


// store links (Admin Side)
Route::get('/store-links/{id}', [StoreController::class, 'storelinks'])->middleware('XSS', 'auth')->name('store.links');

// Company Login (Admin Side)
Route::get('users/{id}/login-with-owner', [UserController::class, 'LoginWithOwner'])->middleware('XSS', 'auth')->name('login.with.owner');
Route::get('login-with-owner/exit', [UserController::class, 'ExitOwner'])->middleware('XSS', 'auth')->name('exit.owner');

// Admin Hub
Route::get('owner-info/{id}', [UserController::class, 'OwnerInfo'])->name('owner.info');
Route::post('user-unable', [UserController::class, 'UserUnable'])->name('user.unable');

// plan enable/disable
Route::post('plan/active', [PlanController::class, 'planActive'])->name('plan.enable')->middleware(['auth', 'XSS']);

// Refund
Route::get('/refund/{id}/{user_id}', [PlanController::class, 'refund'])->name('order.refund');


//
Route::post('farmer/json', [FarmerController::class, 'json'])->name('farmer.json')->middleware(
    [
        'auth',
        'XSS',
    ]
);
Route::get('export/suppliers', [SupplierController::class, 'export'])->name('suppliers.export');
Route::get('import/suppliers', [SupplierController::class, 'import'])->name('suppliers.file.import');

Route::get('export/farmers', [FarmerController::class, 'export'])->name('farmers.export');
Route::get('export/inspected-farmers', [FarmerController::class, 'export_inspected'])->name('farmers.export_inspected');
Route::get('export/un-inspected-farmers', [FarmerController::class, 'export_uninspected'])->name('farmers.export_uninspected');

Route::get('export/compliant-farmers', [FarmerController::class, 'export_compliant'])->name('farmers.export_compliant');
Route::get('export/non-compliant-farmers', [FarmerController::class, 'export_non_compliant'])->name('farmers.export_non_compliant');





Route::get('import/farmer/file', [FarmerController::class, 'import'])->name('farmers.file.import');
Route::get('import/farmers', [FarmerController::class, 'importFile'])->name('farmers.import');
Route::post('employee/getdepartment', [FarmerController::class, 'getdepartment'])->name('employee.getdepartment')->middleware(
    [
        'auth',
        'XSS',
    ]
);
//chapters

Route::get('/checklist',  [ConfigurationController::class, 'index'])->name('checklist.index')->middleware(['auth', 'XSS']);
Route::get('/checklist/create', [ConfigurationController::class, 'create'])->name('chapters.create')->middleware(['auth', 'XSS']);
Route::post('/checklist/store', [ConfigurationController::class, 'store'])->name('chapters.store')->middleware(['auth', 'XSS']);
Route::get('/checklist/{id}/edit', [ConfigurationController::class, 'edit'])->name('chapters.edit')->middleware(['auth', 'XSS']);
Route::post('/checklist/{id}}/edit', [ConfigurationController::class, 'update'])->name('chapters.update')->middleware(['auth', 'XSS']);
Route::delete('/checklist/destroy/{id}', [ConfigurationController::class, 'destroy'])->name('chapters.destroy')->middleware(['auth', 'XSS']);



Route::get('farmer-profile', [FarmerController::class, 'profile'])->name('farmer.profile')->middleware(
    [
        'auth',
        'XSS',
    ]
);
Route::get('show-farmer-profile/{id}', [FarmerController::class, 'profileShow'])->name('show.farmer.profile')->middleware(
    [
        'auth',
        'XSS',
    ]
);
Route::get('lastlogin', [FarmerController::class, 'lastLogin'])->name('lastlogin')->middleware(
    [
        'auth',
        'XSS',
    ]
);

// user log
Route::get('userlogsView/{id}', [FarmerController::class, 'view'])->name('userlog.view')->middleware(['auth', 'XSS']);
Route::get('farmers', [FarmerController::class, 'index'])->name('farmers.index')->middleware(['auth', 'XSS']);
Route::get('farmers/inspected-farmers', [FarmerController::class, 'inspected'])->name('farmers.inspected')->middleware(['auth', 'XSS']);
Route::get('farmers/uninspected-farmers', [FarmerController::class, 'uninspected'])->name('farmers.uninspected')->middleware(['auth', 'XSS']);
Route::get('farmers/compliant-farmers', [FarmerController::class, 'compliant'])->name('farmers.compliant')->middleware(['auth', 'XSS']);
Route::get('farmers/non-compliant-farmers', [FarmerController::class, 'noncompliant'])->name('farmers.noncompliant')->middleware(['auth', 'XSS']);


Route::get('export/suppliers', [SupplierController::class, 'export'])->name('suppliers.export');
Route::get('export/inspected-suppliers', [SupplierController::class, 'export_inspected'])->name('suppliers.export_inspected');
Route::get('export/uninspected-suppliers', [SupplierController::class, 'export_uninspected'])->name('suppliers.export_uninspected');
Route::get('export/compliant-suppliers', [SupplierController::class, 'export_compliant'])->name('suppliers.export_compliant');



Route::get('suppliers', [SupplierController::class, 'index'])->name('suppliers.index')->middleware(['auth', 'XSS']);
Route::get('create/supplier-category', [SupplierController::class, 'create_category'])->name('suppliers.create_category')->middleware(['auth', 'XSS']);
Route::post('save-supplier-category', [SupplierController::class, 'store_category'])->name('suppliers.store_category')->middleware(
    [
        'auth',
        'XSS',
    ]
);
Route::get('/supplier-category/edit/{id}', [SupplierController::class, 'edit_supplier_category'])->name('suppliers.edit_supplier_category')->middleware(['auth', 'XSS']);

Route::post('/supplier-category/edit/{id}', [SupplierController::class, 'update_supplier_category'])->name('suppliers.update_supplier_category')->middleware(['auth', 'XSS']);
Route::post('/supplier/update/{id}', [SupplierController::class, 'update'])->name('suppliers.update')->middleware(['auth', 'XSS']);

Route::delete('/supplier-category/destroy/{id}', [SupplierController::class, 'destroy_supplier_category'])->name('suppliers.destroy_supplier_category')->middleware(['auth', 'XSS']);


Route::post('save-supplier', [SupplierController::class, 'store'])->name('suppliers.store');
Route::post('delete-supplier/{id}', [SupplierController::class, 'destroy'])->name('suppliers.delete');

Route::get('/supplier/edit/{id}', [SupplierController::class, 'edit'])->name('suppliers.edit')->middleware(['auth', 'XSS']);

Route::get('suppliers/inspected-suppliers', [SupplierController::class, 'inspected'])->name('suppliers.inspected')->middleware(['auth', 'XSS']);
Route::get('suppliers/uninspected-suppliers', [SupplierController::class, 'uninspected'])->name('suppliers.uninspected')->middleware(['auth', 'XSS']);
Route::get('suppliers/compliant-suppliers', [SupplierController::class, 'compliant'])->name('suppliers.compliant')->middleware(['auth', 'XSS']);
Route::get('suppliers/non-compliant-suppliers', [SupplierController::class, 'noncompliant'])->name('suppliers.noncompliant')->middleware(['auth', 'XSS']);
Route::get('suppliers/categories', [SupplierController::class, 'categories'])->name('suppliers.categories')->middleware(['auth', 'XSS']);


Route::get('create-supplier', [SupplierController::class, 'create'])->name('suppliers.create')->middleware(['auth', 'XSS']);





Route::get('farmers/sanctioned-farmers', [FarmerController::class, 'sanctioned'])->name('farmers.sanctioned')->middleware(['auth', 'XSS']);





Route::delete('lastlogin/{id}', [FarmerController::class, 'logindestroy'])->name('employee.logindestroy')->middleware(
    [
        'auth',
        'XSS',
    ]
);

Route::resource('farmer', FarmerController::class)->middleware(
    [
        'auth',
        'XSS',
    ]
);
//inspection
 //inspections
 Route::get('/no-farm-polygon/{id}', [InspectionController::class, 'noFarmPolygon'])->name('no-farm-polygon');
Route::get('/no-center-coordinate/{id}', [InspectionController::class, 'noCenterCoordinate'])->name('no-center-coordinate');
Route::get('/continuous-improvement/{id}', [InspectionController::class, 'continuousImprovement'])->name('continuous-improvement');
Route::get('/approve/{id}', [InspectionController::class, 'approve'])->name('approve');
Route::get('/reject/{id}', [InspectionController::class, 'reject'])->name('reject');
Route::get('/edit-checklist/{id}', [InspectionController::class, 'editChecklist'])->name('edit-checklist');
Route::get('/download-report/{id}', [InspectionController::class, 'downloadReport'])->name('download-report');


 Route::get('/inspections/show/{id}', [InspectionController::class, 'show'])->name('inspections.show')->middleware(['auth', 'XSS']);
 Route::get('/farmers/filter', [InspectionController::class, 'filter'])->name('inspections.filter');

 Route::get('/inspections', [InspectionController::class, 'index'])->name('inspections.index')->middleware(['auth', 'XSS']);
 Route::post('/approve-inspections/{id}f', [InspectionController::class, 'approve'])->name('inspections.approve')->middleware(['auth', 'XSS']);
 Route::post('/reject-inspections/{id}', [InspectionController::class, 'reject'])->name('inspections.reject')->middleware(['auth', 'XSS']);
 Route::post('/edit-inspections/{id}', [InspectionController::class, 'edit'])->name('inspections.edit')->middleware(['auth', 'XSS']);


 Route::get('/inspections/not-inspected', [InspectionController::class, 'notinspected'])->name('inspections.notinspected')->middleware(['auth', 'XSS']);

