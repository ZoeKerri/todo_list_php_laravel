<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Team extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'code',
        'created_by',
        'updated_by',
    ];

    /**
     * Get the team members.
     */
    public function teamMembers()
    {
        return $this->hasMany(TeamMember::class);
    }

    /**
     * Get the tasks for the team (through team members).
     */
    public function tasks()
    {
        return $this->hasManyThrough(TeamTask::class, TeamMember::class, 'team_id', 'member_id');
    }

    /**
     * Get the leader of the team.
     */
    public function leader()
    {
        return $this->hasOne(TeamMember::class)->where('role', 'LEADER');
    }

    /**
     * Get QR code for the team (format: TODOLIST-{teamId}).
     */
    public function getCodeAttribute(): string
    {
        return "TODOLIST-{$this->id}";
    }
}
