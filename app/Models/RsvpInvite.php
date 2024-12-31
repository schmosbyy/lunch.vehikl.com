<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class RsvpInvite extends Model
{
    protected $fillable = [
        'inviter_id',
        'invitee_github_id',
        'invitee_github_username',
        'lunch_date',
        'is_accepted',
    ];

    protected $casts = [
        'lunch_date' => 'date',
        'is_accepted' => 'boolean',
    ];

    public function inviter(): BelongsTo
    {
        return $this->belongsTo(User::class, 'inviter_id');
    }
} 