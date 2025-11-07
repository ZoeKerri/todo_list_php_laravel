<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Enums\Priority;

class TeamTask extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'deadline',
        'priority',
        'is_completed',
        'member_id',
        'created_by',
        'updated_by',
    ];

    protected $casts = [
        'deadline' => 'datetime',
        'is_completed' => 'boolean',
        'priority' => Priority::class,
    ];

    /**
     * Get the team member assigned to the task.
     */
    public function teamMember()
    {
        return $this->belongsTo(TeamMember::class, 'member_id');
    }

    /**
     * Get the team that owns the task (through team member).
     */
    public function team()
    {
        return $this->teamMember->team ?? null;
    }

    /**
     * Get the user assigned to the task (through team member).
     */
    public function assignedUser()
    {
        return $this->teamMember->user ?? null;
    }

    /**
     * Scope to filter completed tasks.
     */
    public function scopeCompleted($query)
    {
        return $query->where('is_completed', true);
    }

    /**
     * Scope to filter pending tasks.
     */
    public function scopePending($query)
    {
        return $query->where('is_completed', false);
    }

    /**
     * Scope to filter overdue tasks.
     */
    public function scopeOverdue($query)
    {
        return $query->where('is_completed', false)
            ->where('deadline', '<', now());
    }
}
