<?php

namespace App\Models;

use App\Casts\JalaliToCarbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Morilog\Jalali\Jalalian;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class User extends Model
{   use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'national_code',
        'first_name',
        'last_name',
        'father_name',
        'birth_date',
        'gender',
        'mobile',
        'postal_code',
        'address',
    ];

    protected $appends = [
    'created_at_jalali',
    'updated_at_jalali',
    'deleted_at_jalali',
    'birth_date_jalali',
    ];
    
    protected $casts = ['birth_date' => JalaliToCarbon::class,];

    public function licenseRequests()
    {
        return $this->hasMany(LicenseRequest::class,'user_id');
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

public function getBirthDateJalaliAttribute()
{
    return $this->birth_date ? Jalalian::fromCarbon($this->birth_date)->format('Y/m/d') : null;
}

    
}

