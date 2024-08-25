<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

use Spatie\Permission\Traits\HasRoles;


class User extends Authenticatable implements MustVerifyEmail
{
    use HasRoles;
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
     protected $fillable = [
        'name',
        'email',
        'email_verified_at',
        'password',
        'remember_token',
        'lang',
        'avatar',
        'current_company',
        'plan',
        'plan_expire_date',
        'storage_limit',
        'referral_code',
        'used_referral_code',
        'commission_amount',
        'mode',
        'plan_is_active',
        'type',
        'created_by',
        'is_active',
        'is_disable',
        'is_enable_login',
        'trial_plan',
        'trial_expire_date',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
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
