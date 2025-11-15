<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Enums\Role;

class TeamMember extends Model
{
    use HasFactory;

    protected $fillable = [
        'role',
        'user_id',
        'team_id',
        'created_by',
        'updated_by',
    ];

    protected $casts = [
        'role' => Role::class,
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function team()
    {
        return $this->belongsTo(Team::class);
    }

    public function tasks()
    {
        return $this->hasMany(TeamTask::class, 'member_id');
    }

    public function isLeader(): bool
    {
        return $this->role === 'LEADER';
    }

    public function isMember(): bool
    {
        return $this->role === 'MEMBER';
    }
}
