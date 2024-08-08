<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Education extends Model
{
    use HasFactory;

    protected $table = 'education';
    protected $fillable = [
        'user_id',
        'establishment',
        'diploma',
        'entry_date',
        'exit_date',
    ];



    public function user(): BelongsTo
    {
        return $this
            ->belongsTo(User::class);
    }
}
