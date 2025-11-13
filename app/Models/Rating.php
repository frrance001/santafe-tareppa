<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Rating extends Model
{
    use HasFactory;

    // Include polymorphic columns in fillable
    protected $fillable = [
        'rater_id',
        'score',
        'comment',
        'rateable_id',
        'rateable_type',
    ];

    /**
     * Polymorphic relation to the model being rated.
     */
    public function rateable()
    {
        return $this->morphTo();
    }

    /**
     * The user who submitted the rating.
     */
    public function rater()
    {
        return $this->belongsTo(User::class, 'rater_id');
    }
}
