<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Favorite;
use App\Models\Media;
use Illuminate\Http\Request;

class MediaController extends Controller
{
    public function index(Request $request)
    {
        $query = Media::query()->with('uploader')
            ->when($request->filled('event_id'), fn ($q) => $q->where('event_id', $request->integer('event_id')))
            ->when($request->filled('type'), fn ($q) => $q->where('type', $request->string('type')))
            ->when($request->filled('status'), fn ($q) => $q->where('status', $request->string('status')))
            ->latest();

        return $query->paginate(20);
    }

    public function toggleFavorite(Request $request, Media $media)
    {
        $fav = Favorite::where('user_id', $request->user()->id)->where('media_id', $media->id)->first();
        if ($fav) {
            $fav->delete();
            return response()->json(['favorited' => false]);
        }
        Favorite::create(['user_id' => $request->user()->id, 'media_id' => $media->id]);
        return response()->json(['favorited' => true]);
    }
}


