<?php

namespace App\Http\Controllers\Api;

use App\DTOs\ApiResponse;
use App\DTOs\CategoryDTO;
use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\JsonResponse;

class CategoryController extends Controller
{
    /**
     * Display a listing of all categories.
     */
    public function index(): JsonResponse
    {
        $categories = Category::orderBy('name', 'asc')->get();
        $categoryDTOs = $categories->map(fn($category) => CategoryDTO::fromModel($category));

        return ApiResponse::success($categoryDTOs->map(fn($dto) => $dto->toArray()), 'Get all categories successful');
    }
}
