<?php

namespace App\Http\Controllers\Api;

use App\DTOs\ApiResponse;
use App\DTOs\FileUploadDTO;
use App\Http\Controllers\Controller;
use App\Models\FileUpload;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class FileController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api');
    }

    public function upload(Request $request): JsonResponse
    {
        $user = Auth::user();
        
        $request->validate([
            'file' => 'required|file|max:10240',
        ]);

        if ($request->hasFile('file')) {
            $file = $request->file('file');
            
            $originalName = $file->getClientOriginalName();
            $extension = $file->getClientOriginalExtension();
            $fileName = Str::uuid() . '.' . $extension;
            
            $filePath = $file->storeAs('uploads', $fileName, 'public');
            
            $fileUpload = FileUpload::create([
                'file_name' => $fileName,
                'original_name' => $originalName,
                'file_path' => $filePath,
                'file_type' => $file->getMimeType(),
                'file_size' => $file->getSize(),
                'user_id' => $user->id,
                'created_by' => $user->email,
                'updated_by' => $user->email,
            ]);

            $fileDTO = FileUploadDTO::fromModel($fileUpload);

            return ApiResponse::success($fileDTO->toArray(), 'Upload file successful');
        }

        return ApiResponse::error('File upload failed');
    }

    public function index(): JsonResponse
    {
        $user = Auth::user();
        $files = FileUpload::where('user_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->get();

        $fileDTOs = $files->map(fn($file) => FileUploadDTO::fromModel($file));

        return ApiResponse::success($fileDTOs->map(fn($dto) => $dto->toArray()), 'Get user files successful');
    }

    public function destroy(FileUpload $fileUpload): JsonResponse
    {
        $user = Auth::user();
        
        if ($fileUpload->user_id !== $user->id) {
            return ApiResponse::forbidden('You are not authorized to delete this file');
        }

        if (Storage::disk('public')->exists($fileUpload->file_path)) {
            Storage::disk('public')->delete($fileUpload->file_path);
        }

        $fileUpload->delete();

        return ApiResponse::success(null, 'File deleted successfully');
    }
}
