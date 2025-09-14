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
            ->when($request->filled('q'), fn ($q) => $q->where('title', 'like', '%'.$request->string('q').'%'))
            ->when($request->filled('category_id'), fn ($q) => $q->where('category_id', $request->integer('category_id')))
            ->when($request->filled('department_id'), fn ($q) => $q->where('department_id', $request->integer('department_id')))
            ->when($request->filled('from'), fn ($q) => $q->where('start_at', '>=', $request->date('from')))
            ->when($request->filled('to'), fn ($q) => $q->where('end_at', '<=', $request->date('to')))
            ->when($request->filled('status'), fn ($q) => $q->where('status', $request->string('status')))
            ->orderByDesc('start_at');

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

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'description' => ['required', 'string'],
            'start_at' => ['required', 'date', 'after:now'],
            'end_at' => ['required', 'date', 'after:start_at'],
            'venue' => ['required', 'string', 'max:255'],
            'capacity' => ['required', 'integer', 'min:1'],
            'category_id' => ['required', 'integer', 'exists:categories,id'],
            'department_id' => ['required', 'integer', 'exists:departments,id'],
            'organizer_id' => ['required', 'integer', 'exists:users,id'],
            'co_organizers' => ['nullable', 'array'],
            'co_organizers.*' => ['string', 'max:255'],
            'rules' => ['nullable', 'string'],
            'contact_info' => ['nullable', 'string'],
            'banner_url' => ['nullable', 'url'],
        ]);

        // Set seats_left = capacity initially
        $validated['seats_left'] = $validated['capacity'];
        
        // Generate slug from title
        $validated['slug'] = \Str::slug($validated['title']);
        
        $event = Event::create($validated);
        $event->load(['department', 'category', 'organizer']);

        return response()->json([
            'success' => true,
            'message' => 'Event created successfully',
            'data' => $event
        ], 201);
    }
}



