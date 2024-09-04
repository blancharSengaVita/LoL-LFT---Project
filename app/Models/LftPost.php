<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class LftPost extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'job',
        'ambiance',
        'goal',
        'description',
        'published',
    ];

    public function user(): BelongsTo
    {
        return $this
            ->belongsTo(User::class);
    }
}
