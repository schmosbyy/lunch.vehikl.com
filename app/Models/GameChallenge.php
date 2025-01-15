<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class GameChallenge extends Model
{
    protected $fillable = [
        'challenger_id',
        'challenged_id',
        'rsvp_id',
        'game_type',
        'status',
        'accepted_at'
    ];

    protected $casts = [
        'status' => 'string',
    ];

    public function challenger(): BelongsTo
    {
        return $this->belongsTo(User::class, 'challenger_id');
    }

    public function challenged(): BelongsTo
    {
        return $this->belongsTo(User::class, 'challenged_id');
    }

    public function rsvp(): BelongsTo
    {
        return $this->belongsTo(Rsvp::class);
    }
}
