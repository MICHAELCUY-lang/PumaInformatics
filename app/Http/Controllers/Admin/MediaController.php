<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class MediaController extends Controller
{
    public function index(Request $request)
    {
        $query = Media::query()->with('model')->orderBy('created_at', 'desc');

        // Basic filtering
        if ($request->filled('type')) {
            $query->where('mime_type', 'like', $request->type . '%');
        }
        
        $media = $query->paginate(24);

        if ($request->wantsJson()) {
            $mediaItems = $media->map(function ($medium) {
                return [
                    'id' => $medium->id,
                    'url' => $medium->getUrl(),
                    'thumbnail' => $medium->getUrl('thumbnail') ?: $medium->getUrl(),
                    'name' => $medium->name,
                    'mime_type' => $medium->mime_type,
                ];
            });
            return response()->json([
                'data' => $mediaItems,
                'next_page_url' => $media->nextPageUrl()
            ]);
        }

        return view('admin.media.index', compact('media'));
    }

    public function destroy(Media $medium)
    {
        if ($medium->model instanceof \App\Models\GlobalMedia || $medium->model_type === \App\Models\GlobalMedia::class) {
            $medium->model->delete(); // This cascades and deletes the Spatie media row/file
            return redirect()->route('admin.media.index')->with('success', 'Media deleted successfully.');
        }

        return redirect()->route('admin.media.index')->withErrors(['error' => 'This media is actively bound to content (e.g. an Article). To delete it, you must remove it from the corresponding content page.']);
    }
}
