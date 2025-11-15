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

    public function teamMember()
    {
        return $this->belongsTo(TeamMember::class, 'member_id');
    }

    public function team()
    {
        return $this->teamMember->team ?? null;
    }

    public function assignedUser()
    {
        return $this->teamMember->user ?? null;
    }

    public function scopeCompleted($query)
    {
        return $query->where('is_completed', true);
    }

    public function scopePending($query)
    {
        return $query->where('is_completed', false);
    }

    public function scopeOverdue($query)
    {
        return $query->where('is_completed', false)
            ->where('deadline', '<', now());
    }
}
