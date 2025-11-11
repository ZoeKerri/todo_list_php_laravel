<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo; // <-- THÊM DÒNG NÀY
use Illuminate\Database\Eloquent\Relations\HasMany; // <-- THÊM DÒNG NÀY

class Category extends Model
{
    use HasFactory;

    // Đảm bảo tên bảng là 'categories'
    protected $table = 'categories';

    /**
     * Các trường được phép gán.
     * (Giữ nguyên như code của bạn)
     */
    protected $fillable = [
        'name',
        'color',
        'created_by', // <-- Model của bạn dùng cột này
        'updated_by', // <-- Model của bạn dùng cột này
    ];

    /**
     * Lấy user đã tạo category này.
     * (Thêm mối quan hệ này)
     */
    public function createdBy(): BelongsTo
    {
        // Liên kết Model này với User qua khóa ngoại 'created_by'
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Lấy user đã cập nhật category này.
     * (Thêm mối quan hệ này)
     */
    public function updatedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    /**
     * Get the personal tasks for the category.
     * (Giữ nguyên, chỉ thêm kiểu trả về :HasMany)
     */
    public function personalTasks(): HasMany
    {
        return $this->hasMany(PersonalTask::class);
    }

    /**
     * Get the team tasks for the category.
     * (Giữ nguyên, chỉ thêm kiểu trả về :HasMany)
     */
    public function teamTasks(): HasMany
    {
        return $this->hasMany(TeamTask::class);
    }
}