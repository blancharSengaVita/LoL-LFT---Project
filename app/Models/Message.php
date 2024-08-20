<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Message extends Model
{
    use HasFactory;

    protected $fillable = [
        'message',
        'user_id',
    ];

    public function user(): BelongsTo
    {
        return $this
            ->belongsTo(User::class);
    }

//    public function from(): BelongsTo
//    {
//        return $this
//            ->belongsTo(User::class, 'from');
//    }
}
