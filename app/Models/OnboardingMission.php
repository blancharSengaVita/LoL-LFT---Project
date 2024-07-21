<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class OnboardingMission extends Model
{
    use HasFactory;

    protected $table = 'onboarding_missions';

    protected $fillable = [
        'title',
        'description',
        'button',
    ];

    public function user(): BelongsTo
    {
        return $this
            ->belongsTo(User::class);
    }
}
