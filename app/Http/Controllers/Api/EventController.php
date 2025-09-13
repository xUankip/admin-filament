<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\Feedback;
use Illuminate\Http\Request;

class EventController extends Controller
{
    public function index(Request $request)
    {
        $query = Event::query()
            ->with(['department', 'category', 'organizer'])
            ->when(! $request->filled('status'), fn ($q) => $q->whereIn('status', ['approved', 'published']))
            ->when($request->filled('q'), fn ($q) => $q->where('title', 'like', '%'.$request->string('q').'%'))
            ->when($request->filled('category_id'), fn ($q) => $q->where('category_id', $request->integer('category_id')))
            ->when($request->filled('department_id'), fn ($q) => $q->where('department_id', $request->integer('department_id')))
            ->when($request->filled('from'), fn ($q) => $q->where('start_at', '>=', $request->date('from')))
            ->when($request->filled('to'), fn ($q) => $q->where('end_at', '<=', $request->date('to')))
            ->when($request->boolean('eligible'), function ($q) use ($request) {
                // eligible = còn chỗ, thời gian chưa kết thúc
                $q->where('seats_left', '>', 0)->where('end_at', '>=', now());
            });

        $sort = $request->string('sort');
        if ($sort === 'popularity') {
            $query->orderByDesc('popularity_score');
        } elseif ($sort === 'newest') {
            $query->orderByDesc('start_at');
        } else {
            $query->orderByDesc('start_at');
        }

        return $query->paginate(15);
    }

    public function show(Event $event)
    {
        $event->load(['department', 'category', 'organizer']);
        $feedback = Feedback::where('event_id', $event->id)
            ->where('flagged', false)
            ->latest()
            ->limit(20)
            ->get();

        return response()->json([
            'event' => $event,
            'feedback' => $feedback,
        ]);
    }

    public function suggest(Request $request)
    {
        $q = trim((string) $request->string('q'));
        if ($q === '') {
            return response()->json([]);
        }

        $results = Event::query()
            ->when(! $request->filled('status'), fn ($q) => $q->whereIn('status', ['approved', 'published']))
            ->where('title', 'like', '%'.$q.'%')
            ->orderByDesc('popularity_score')
            ->limit(10)
            ->get(['id', 'title', 'slug', 'start_at']);

        return response()->json($results);
    }
}



