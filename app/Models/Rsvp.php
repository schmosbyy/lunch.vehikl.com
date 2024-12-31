<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Rsvp extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'lunch_date',
        'status',
    ];

    protected $casts = [
        'lunch_date' => 'date:Y-m-d',
    ];

    // Define valid status values
    const STATUS_ATTENDING = 'attending';
    const STATUS_NOT_ATTENDING = 'not_attending';
    const STATUS_OUT_OF_TOWN = 'out_of_town';

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
