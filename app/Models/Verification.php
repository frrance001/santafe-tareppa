<?php
namespace App\Models;


use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;


class Verification extends Model
{
use HasFactory;


protected $fillable = [
'user_id',
'business_permit_path',
'police_clearance_path',
'barangay_clearance_path',
'status',
'notes',
];


protected $appends = [
'business_permit_url',
'police_clearance_url',
'barangay_clearance_url',
];


public function user()
{
return $this->belongsTo(User::class);
}


public function getBusinessPermitUrlAttribute()
{
return $this->business_permit_path ? Storage::disk('public')->url($this->business_permit_path) : null;
}


public function getPoliceClearanceUrlAttribute()
{
return $this->police_clearance_path ? Storage::disk('public')->url($this->police_clearance_path) : null;
}


public function getBarangayClearanceUrlAttribute()
{
return $this->barangay_clearance_path ? Storage::disk('public')->url($this->barangay_clearance_path) : null;
}
}