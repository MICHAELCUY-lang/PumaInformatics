<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\GlobalMedia;

class MediaUploadController extends Controller
{
    /**
     * Handle Tiptap image uploads
     */
    public function store(Request $request)
    {
        $request->validate([
            'image' => [
                'required',
                'file',
                'image',
                'mimes:jpeg,png,webp,gif',
                'max:10240', // 10MB limit
            ],
        ]);

        // 1. Create a temporary headless entity to attach the media
        $globalMedia = GlobalMedia::create([
            'user_id' => $request->user()->id,
            'status' => 'temporary',
            'expires_at' => now()->addHours(24),
        ]);

        // 2. Attach and optimize the media immediately
        $media = $globalMedia->addMediaFromRequest('image')
            ->toMediaCollection('editor_uploads');

        // 3. Return the optimized 'editorial' WebP URL back to Tiptap
        return response()->json([
            'url' => $media->getUrl('editorial'),
            'uuid' => $globalMedia->uuid,
        ], 201);
    }
}
