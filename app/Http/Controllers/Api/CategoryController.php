<?php

namespace App\Http\Controllers\Api;

use App\DTOs\ApiResponse;
use App\DTOs\CategoryDTO;
use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class CategoryController extends Controller
{
    public function index(): JsonResponse
    {
        $userId = Auth::id();
        $categories = Category::where('created_by', $userId)
            ->orWhereNull('created_by')
            ->orderBy('name', 'asc')
            ->get();

        $categoryDTOs = $categories->map(fn($category) => CategoryDTO::fromModel($category));

        return ApiResponse::success($categoryDTOs->map(fn($dto) => $dto->toArray()), 'Get all categories successful');
    }

    public function store(Request $request): JsonResponse
    {
        $userId = Auth::id();
        $validated = $request->validate([
            'name' => [
                'required',
                'string',
                'max:255',
                Rule::unique('categories', 'name')
                    ->where('created_by', $userId)
            ],
            'color' => 'nullable|string',
        ]);

        try {
            $category = Category::create([
                'name' => $validated['name'],
                'color' => $validated['color'] ?? null,
                'created_by' => $userId,
                'updated_by' => $userId,
            ]);
            return ApiResponse::success(
                CategoryDTO::fromModel($category)->toArray(),
                'Category created successfully',
                201
            );

        } catch (\Exception $e) {
            return ApiResponse::error($e->getMessage());
        }
    }

    public function destroy(Category $category): JsonResponse
    {
        if ($category->created_by !== Auth::id()) {
            return ApiResponse::forbidden('You do not have permission to delete this category.');
        }
        try {
            $category->delete();
            return ApiResponse::success(null, 'Category deleted successfully');

        } catch (\Exception $e) {
            return ApiResponse::error($e->getMessage());
        }
    }
}