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

    public function teamMembers()
    {
        return $this->hasMany(TeamMember::class);
    }

    public function tasks()
    {
        return $this->hasManyThrough(TeamTask::class, TeamMember::class, 'team_id', 'member_id');
    }

    public function leader()
    {
        return $this->hasOne(TeamMember::class)->where('role', 'LEADER');
    }

    public function getCodeAttribute(): string
    {
        return "TODOLIST-{$this->id}";
    }
}
