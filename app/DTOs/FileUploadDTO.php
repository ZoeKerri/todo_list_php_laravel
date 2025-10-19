<?php

namespace App\DTOs;

use App\Models\FileUpload;

class FileUploadDTO
{
    public function __construct(
        public string $url,
        public string $fileName,
        public int $fileSize
    ) {}

    public function toArray(): array
    {
        return [
            'url' => $this->url,
            'fileName' => $this->fileName,
            'fileSize' => $this->fileSize,
        ];
    }

    public static function fromModel(FileUpload $fileUpload): self
    {
        return new self(
            url: asset('storage/' . $fileUpload->file_path),
            fileName: $fileUpload->original_name,
            fileSize: $fileUpload->file_size
        );
    }
}
