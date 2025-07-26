<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Morilog\Jalali\Jalalian;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class License extends Model
{   use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'code',
        'title',
        'description',
        'issuer_organization_code',
        'issue_duration_days',
        'valid_duration_days',
        'issue_fee',
        'status',
    ];
    
        protected $appends = [
        'created_at_jalali',
        'updated_at_jalali',
        'deleted_at_jalali',
    ];

    public function licenseRequests()
    {
        return $this->hasMany(LicenseRequest::class,'license_id');
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
}

