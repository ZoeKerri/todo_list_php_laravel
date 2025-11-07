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

    /**
     * Get the user that is a member of the team.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the team that the member belongs to.
     */
    public function team()
    {
        return $this->belongsTo(Team::class);
    }

    /**
     * Get the tasks assigned to this team member.
     */
    public function tasks()
    {
        return $this->hasMany(TeamTask::class, 'member_id');
    }

    /**
     * Check if the member is a leader.
     */
    public function isLeader(): bool
    {
        return $this->role === 'LEADER';
    }

    /**
     * Check if the member is a regular member.
     */
    public function isMember(): bool
    {
        return $this->role === 'MEMBER';
    }
}
