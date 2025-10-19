<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PersonalTask extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'due_date',
        'priority',
        'completed',
        'notification_time',
        'user_id',
        'category_id',
        'created_by',
        'updated_by',
    ];

    protected $casts = [
        'due_date' => 'datetime',
        'notification_time' => 'datetime',
        'completed' => 'boolean',
    ];

    /**
     * Get the user that owns the personal task.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the category for the personal task.
     */
    public function category()
    {
        return $this->belongsTo(Category::class);
    }
}
