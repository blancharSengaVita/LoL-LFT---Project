<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TeamMember extends Model
{
//    protected $table = 'team_player';
    protected $fillable = [
        'team_id',
        'player_id',
        'type',
        'archived',
        'username',
        'job',
        'nationality',
        'entry_date',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
