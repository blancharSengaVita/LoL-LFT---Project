<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;
use \Illuminate\Database\Eloquent\Relations\HasMany;

class User extends Authenticatable
{
    use HasFactory, Notifiable, HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function playerExperience(): HasMany
    {
        return $this
            ->hasMany(PlayerExperience::class);
    }

    public function displayedInformation(): HasMany
    {
        return $this
            ->hasMany(DisplayedInformation::class);
    }

    public function award(): HasMany
    {
        return $this
            ->hasMany(Award::class);
    }

    public function skill(): HasMany
    {
        return $this
            ->hasMany(Skill::class);
    }

    public function onboardingMission(): HasMany
    {
        return $this
            ->hasMany(OnboardingMission::class);
    }

    public function language(): HasMany
    {
        return $this
            ->hasMany(Language::class);
    }

    public function displayedInformationsOnce(): HasMany
    {
        return $this
            ->hasMany(DisplayedInformationsOnce::class);
    }

    public function players(): HasMany
    {
        return $this->hasMany(TeamMember::class,'team_id');
    }

//    public function teams(): HasMany
//    {
//        return $this->hasMany(TeamMember::class, 'team_id');
//    }

//    public function players(): BelongsToMany
//    {
//        return $this->belongsToMany(User::class, 'team_player', 'player_id', 'user_id');
//    }
//
//    public function teams(): BelongsToMany
//    {
//        return $this->belongsToMany(User::class, 'team_player', 'player_id', 'player_id');
//    }
}
