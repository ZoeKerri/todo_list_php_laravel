<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'color',
        'created_by',
        'updated_by',
    ];

    /**
     * Get the personal tasks for the category.
     */
    public function personalTasks()
    {
        return $this->hasMany(PersonalTask::class);
    }

    /**
     * Get the team tasks for the category.
     */
    public function teamTasks()
    {
        return $this->hasMany(TeamTask::class);
    }
}
