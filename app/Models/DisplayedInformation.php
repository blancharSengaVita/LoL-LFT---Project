<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DisplayedInformation extends Model
{
    use HasFactory;
    protected $table = 'displayed_informations';

    public function user(): BelongsTo
    {
        return $this
            ->belongsTo(User::class);
    }
}
