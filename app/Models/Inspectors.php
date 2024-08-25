<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use JetBrains\PhpStorm\Language;
use Laravel\Fortify\TwoFactorAuthenticatable;
use Laravel\Jetstream\HasProfilePhoto;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;
use Lab404\Impersonate\Models\Impersonate;


class Inspectors extends Authenticatable implements MustVerifyEmail
{
    use HasRoles;
    use Notifiable;
    use HasApiTokens;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
     protected $fillable = [
                        
                  'username',     
        'name',
        'email',
        'phone',
        'pin',
            'id_number',
        
'is_pin_created',
       
        'created_by',
        'is_active',
        'is_disable',
        'is_enable_login',
    
    ];
    protected $table = 'inspectors';


  
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function creatorId()
    {
        if($this->type == 'Owner' || $this->type == 'super admin')
        {
            return $this->id;
        }
        else
        {
            return $this->created_by;
        }
    }

    private static $getLang = null;
    public function priceFormat($price)
    {
        $settings = Utility::settings();

        return (($settings['currency_symbol_position'] == "pre") ? $settings['site_currency_symbol'] : '') . number_format($price, 2) . (($settings['currency_symbol_position'] == "post") ? $settings['site_currency_symbol'] : '');
    }

    public function currentLanguages()
    {
        if (is_null(self::$getLang)) {
            $currantLang = $this->lang;
            if(!isset($currantLang)){
                $currantLang = 'en';
            }
            $language = Languages::where('code',$currantLang)->get()->first();
            self::$getLang = $language;
        }
        return self::$getLang;
    }

    public function countCompany()
    {
        return User::where('type', '=', 'Owner')->where('created_by', '=', $this->creatorId())->count();
    }

    public function countPaidCompany()
    {
        return User::where('type', '=', 'Owner')->whereNotIn(
            'plan', [
                      0,
                      1,
                  ]
        )->where('created_by', '=', \Auth::user()->id)->count();
    }

    public function assignPlan($planID)
    {
        $plan = Plan::find($planID);
        if($plan)
        {
            $this->plan = $plan->id;
            if($this->trial_expire_date != null);
            {
                $this->trial_expire_date = null;
            }
            if($plan->duration == 'Month')
            {
                $this->plan_expire_date = Carbon::now()->addMonths(1)->isoFormat('YYYY-MM-DD');
            }
            elseif($plan->duration == 'Year')
            {
                $this->plan_expire_date = Carbon::now()->addYears(1)->isoFormat('YYYY-MM-DD');
            }
            else{
                $this->plan_expire_date = null;
            }
            $this->save();

            $users    = User::where('created_by', '=', \Auth::user()->creatorId())->where('type', '!=', 'super admin')->get();
            $stores   = Company::where('created_by', '=', \Auth::user()->creatorId())->get();


            if($plan->max_stores == -1)
            {
                foreach($stores as $store)
                {
                    $store->is_active = 1;
                    $store->save();
                }
            }
            else
            {
                $storeCount = 0;
                foreach($stores as $store)
                {
                    $storeCount++;
                    if($storeCount <= $plan->max_stores)
                    {
                        $store->is_active = 1;
                        $store->save();
                    }
                    else
                    {
                        $store->is_active = 0;
                        $store->save();
                    }
                }
            }

         

            return ['is_success' => true];
        }
        else
        {
            return [
                'is_success' => false,
                'error' => 'Plan is deleted!',
            ];
        }
    }

    public function countProducts()
    {
        return Product::where('created_by', '=', $this->creatorId())->count();
    }

    public function countStores($id)
    {
        return Company::where('created_by', $id)->count();
    }

    public function countStore()
    {
        return Company::where('created_by', '=', $this->creatorId())->count();
    }

    private static $currentPlan = null;

    public function currentPlan()
    {
        if(is_null(self::$currentPlan)){
            self::$currentPlan = $this->hasOne('App\Models\Plan', 'id', 'plan');
        }
        return self::$currentPlan;
    }

    private static $currentCompany = null;

    public function currentCompany()
    {
        if(is_null(self::$currentCompany)){
            self::$currentCompany = $this->hasOne('App\Models\Company', 'id', 'current_company');
        }
        return self::$currentCompany;
    }

    public function companies()
    {
        return $this->belongsToMany('App\Models\Company', 'user_companies', 'user_id', 'company_id')->withPivot('permission');
    }

    public function dateFormat($date)
    {
        $settings = Utility::settings();

        return date($settings['site_date_format'], strtotime($date));
    }

    public function countUsers()
    {
        return User::where('created_by', '=', $this->creatorId())->count();
    }

    public function countCompanyUsers($storeID)
    {
        return User::where('created_by', '=', $this->creatorId())->where('current_company', '=', $storeID)->count();
    }
    

}
