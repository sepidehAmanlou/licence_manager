<?php

namespace App\Models;

use App\Casts\JalaliToCarbon;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Morilog\Jalali\Jalalian;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class LicenseRequest extends Model
{   use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'user_id',
        'license_id',
        'business_postal_code',
        'business_address',
        'status',
        'requested_at',
        'approved_at',
        'rejected_at',
        'expires_at',
        'admin_note',
    ];

    protected $appends = [
    'created_at_jalali',
    'updated_at_jalali',
    'deleted_at_jalali',
    'requested_at_jalali',
    'approved_at_jalali',
    'rejected_at_jalali',
    'expires_at_jalali',
    ];

    protected $casts = [
    'requested_at' => JalaliToCarbon::class,
    'approved_at' => JalaliToCarbon::class,
    'rejected_at' => JalaliToCarbon::class,
   ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function license()
    {
        return $this->belongsTo(License::class);
    }
    
    protected static function booted()
   {
    static::created(function ($licenseRequest) {
        if (!$licenseRequest->request_code) {
            $licenseRequest->request_code = 'REQ-' . str_pad($licenseRequest->id, 6, '0', STR_PAD_LEFT);
            $licenseRequest->save();
        }
    });
   }

   public function getCreatedAtJalaliAttribute()
    {
        return $this->created_at ? Jalalian::fromCarbon($this->created_at)->format('Y/m/d H:i') : null;
    }

    public function getUpdatedAtJalaliAttribute()
    {
        return $this->updated_at ? Jalalian::fromCarbon($this->updated_at)->format('Y/m/d H:i') : null;
    }

    public function getDeletedAtJalaliAttribute()
    {
        return $this->deleted_at ? Jalalian::fromCarbon($this->deleted_at)->format('Y/m/d H:i') : null;
    }

    public function getRequestedAtJalaliAttribute()
    {
        return $this->requested_at ? Jalalian::fromCarbon($this->requested_at)->format('Y/m/d H:i') : null;
    }

    public function getApprovedAtJalaliAttribute()
    {
        return $this->approved_at ? Jalalian::fromCarbon($this->approved_at)->format('Y/m/d H:i') : null;
    }

    public function getRejectedAtJalaliAttribute()
    {
        return $this->rejected_at ? Jalalian::fromCarbon($this->rejected_at)->format('Y/m/d H:i') : null;
    }

    public function getExpiresAtJalaliAttribute()
    {
        return $this->expires_at ? Jalalian::fromCarbon(Carbon::parse($this->expires_at))->format('Y/m/d H:i') : null;
    }
 
}

