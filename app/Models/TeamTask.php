<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TeamTask extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'due_date',
        'priority',
        'status',
        'team_id',
        'assigned_to',
        'category_id',
        'created_by',
        'updated_by',
    ];

    protected $casts = [
        'due_date' => 'datetime',
    ];

    /**
     * Get the team that owns the task.
     */
    public function team()
    {
        return $this->belongsTo(Team::class);
    }

    /**
     * Get the user assigned to the task.
     */
    public function assignedTo()
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }

    /**
     * Get the category for the task.
     */
    public function category()
    {
        return $this->belongsTo(Category::class);
    }
}
