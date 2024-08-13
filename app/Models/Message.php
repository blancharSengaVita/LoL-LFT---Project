<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Message extends Model
{
    use HasFactory;

    public function to(): BelongsTo
    {
        return $this
            ->belongsTo(User::class, 'to');
    }

    public function from(): BelongsTo
    {
        return $this
            ->belongsTo(User::class, 'from');
    }
}
