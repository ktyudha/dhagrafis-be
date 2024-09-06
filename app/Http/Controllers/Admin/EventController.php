<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Http\Requests\StoreEventRequest;
use App\Http\Requests\UpdateEventRequest;
use App\Http\Resources\EventResource;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class EventController extends Controller
{
    /**
     * Create the controller instance.
     */
    public function __construct()
    {
        $this->authorizeResource(Event::class, 'event');
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $events = Event::with(['author', 'publisher'])
            ->when($request->user()->cannot('view all events'), function (Builder $query) use ($request) {
                $query->where('author_id', $request->user()->id);
            })->when($request->search, function (Builder $query, string $key) {
                $query->search($key);
            })->latest()->paginate(10);

        return EventResource::collection($events);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreEventRequest $request)
    {
        $validated = $request->validated();
        $validated['slug'] = Str::slug($validated['name'], dictionary: ['&' => 'and']);
        $validated['poster'] = $request->file('poster')->store('events');
        $validated['author_id'] = $request->user()->id;

        $validated['published_by'] = request()->user()->id;
        $validated['published_at'] = now();

        $event = Event::create($validated);


        return response()->json([
            'success' => true,
            'event' => new EventResource($event)
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(Event $event)
    {
        $event->load(['author', 'publisher']);

        return response()->json([
            'success' => true,
            'event' => new EventResource($event)
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateEventRequest $request, Event $event)
    {
        $validated = $request->validated();
        $validated['slug'] = Str::slug($validated['name'], dictionary: ['&' => 'and']);
        // $validated['published_by'] = null;
        // $validated['published_at'] = null;
        $validated['published_by'] = request()->user()->id;
        $validated['published_at'] = now();

        if ($request->has('poster')) {
            Storage::delete($event->poster);
            $validated['poster'] = $request->file('poster')->store('events');
        }

        $event->update($validated);

        return response()->json([
            'success' => true,
            'event' => new EventResource($event)
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Event $event)
    {
        Storage::delete($event->poster);
        $event->delete();
        return response()->json([
            'success' => true,
            'message' => 'Event deleted successfully'
        ]);
    }

    /**
     * Publish the specified resource from storage.
     */
    public function publish(Event $event)
    {
        $this->authorize('publish', Event::class);

        $event->update([
            'published_by' => request()->user()->id,
            'published_at' => now(),
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Event published successfully',
            'event' => new EventResource($event)
        ]);
    }

    /**
     * Unpublish the specified resource from storage.
     */
    public function unpublish(Event $event)
    {
        $this->authorize('unpublish', Event::class);

        $event->update([
            'published_by' => null,
            'published_at' => null,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Event unpublished successfully',
            'event' => new EventResource($event)
        ]);
    }
}
