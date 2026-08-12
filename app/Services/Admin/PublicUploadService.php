<?php

namespace App\Services\Admin;

use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Str;

class PublicUploadService
{
    public function storeFromRequest(
        Request $request,
        string $inputName,
        string $directory = 'uploads',
        string $prefix = 'file_'
    ): ?string {
        if (! $request->hasFile($inputName)) {
            return null;
        }

        /** @var UploadedFile $file */
        $file = $request->file($inputName);

        return $this->storeUploadedFile($file, $directory, $prefix);
    }

    public function storeUploadedFile(
        UploadedFile $file,
        string $directory = 'uploads',
        string $prefix = 'file_'
    ): string {
        $targetDir = public_path(trim($directory, '/'));

        if (! is_dir($targetDir)) {
            mkdir($targetDir, 0755, true);
        }

        $filename = $prefix . time() . '_' . Str::random(6) . '.' . $file->getClientOriginalExtension();
        $file->move($targetDir, $filename);

        return trim($directory, '/') === 'uploads'
            ? $filename
            : trim($directory, '/') . '/' . $filename;
    }

    public function delete(?string $relativePath): void
    {
        if (! $relativePath) {
            return;
        }

        $fullPath = public_path(ltrim($relativePath, '/'));

        if (is_file($fullPath)) {
            unlink($fullPath);
        }
    }

    public function replaceFromRequest(
        Request $request,
        string $inputName,
        ?string $currentPath,
        string $directory = 'uploads',
        string $prefix = 'file_'
    ): ?string {
        if (! $request->hasFile($inputName)) {
            return $currentPath;
        }

        $this->delete($currentPath);

        return $this->storeFromRequest($request, $inputName, $directory, $prefix);
    }
}
