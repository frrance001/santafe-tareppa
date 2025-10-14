<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Rating extends Model
{
    use HasFactory;

    protected $fillable = ['rater_id','score','comment'];

    public function rateable()
    {
        return $this->morphTo();
    }

    public function rater()
    {
        return $this->belongsTo(User::class, 'rater_id');
    }
}
