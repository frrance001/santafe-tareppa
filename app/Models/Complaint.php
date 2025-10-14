<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
class Complaint extends Model
{
    use HasFactory;

    protected $fillable = ['passenger_id','driver_id','message','status'];

    public function passenger()
    {
        return $this->belongsTo(User::class, 'passenger_id');
    }

    public function driver()
    {
        return $this->belongsTo(User::class, 'driver_id');
    }
}
