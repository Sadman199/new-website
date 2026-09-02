<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class AdminEditorImageController extends Controller
{
    public function store(Request $request): JsonResponse
    {
        $request->validate([
            'file' => ['required', 'image', 'mimes:jpg,jpeg,png,gif,webp', 'max:2048'],
        ]);

        $file = $request->file('file');
        $directory = public_path('uploads/editor');
        File::ensureDirectoryExists($directory);

        $name = 'editor_'.time().'_'.Str::random(8).'.'.$file->getClientOriginalExtension();
        $file->move($directory, $name);

        return response()->json([
            'url' => asset('uploads/editor/'.$name),
        ]);
    }
}
