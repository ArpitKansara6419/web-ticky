<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
// use Laravel\Sanctum\HasApiTokens;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Laravel\Passport\HasApiTokens;

class Engineer extends Authenticatable
{
    use HasApiTokens, Notifiable;

    protected $fillable = [
        'first_name',
        'last_name',
        'engineer_code',
        'username',
        'email',
        'contact',
        'contact_iso',
        'referral_code',
        'password',
        'about',
        'gender',
        'activation_code',
        'is_active',
        'is_system',
        'region_code',
        'default_language',
        'region_code_flag',
        'email_update',
        'alternate_country_code',
        'alternative_contact',
        'alternate_contact_iso',
        'addr_apartment',
        'addr_street',
        'addr_address_line_1',
        'addr_address_line_2',
        'addr_zipcode',
        'addr_city',
        'addr_country',
        'job_type',
        'job_title',
        'job_start_date',
        'country_code',
        'birthdate',
        'nationality',
        'referral',
        'profile_image',
        'otp',
        'api_token',
        'device_token',
        'phone_verified_at',
        'admin_verification',
        'phone_verification',
        'email_verification',
        'gdpr_consent',
        'is_deleted',
        'monthly_payout',
        'timezone',
    ];

    protected $casts = [
        'monthly_payout' => 'array',
    ];

    public $appends = [
        'full_address_timezone'
    ];

    public function enggDoc()
    {
        return $this->hasMany(EngineerDocument::class, 'user_id');
    }

    public function enggTravel()
    {
        return $this->hasOne(EngineerTravelDetail::class, 'user_id');
    }

    public function enggLang()
    {
        return $this->hasMany(EngineerLanguageSkill::class, 'user_id');
    }

    public function enggCharge()
    {
        return $this->hasOne(EngineerCharge::class, 'engineer_id');
    }

    public function enggPay()
    {
        return $this->hasOne(EngineerPaymentDetail::class, 'user_id');
    }

    public function enggEdu()
    {
        return $this->hasMany(EngineerEducation::class, 'user_id');
    }

    public function enggTicket()
    {
        return $this->hasMany(Ticket::class, 'engineer_id');
    }

    public function enggRightToWork()
    {
        return $this->hasMany(RightToWork::class, 'user_id');
    }

    public function enggTechCerty()
    {
        return $this->hasMany(TechnicalCertification::class, 'user_id');
    }

    public function enggSkills()
    {
        return $this->hasMany(EngineerSkill::class, 'user_id');
    }

    public function enggExtraPay()
    {
        return $this->hasOne(EngineerExtraPay::class, 'engineer_id');
    }

    public function enggPaymentDetail()
    {
        return $this->hasOne(EngineerPaymentDetail::class, 'user_id');
    }

    public function getFullAddressTimezoneAttribute()
    {
        $address = $this->addr_city;

        if($this->addr_city && $this->addr_country)
        {
            $address .= ', ';
        }

        $address .= ' '.$this->addr_country;

        $address .= ' '.$this->timezone;

        $gm = fetchTimezone($this->timezone);
        $gmt = isset($gm['gmtOffsetName']) ? " (".$gm['gmtOffsetName'].")" : '';

        if($gmt){
            $address .= $gmt;
        }

        return $address;
    }
}
