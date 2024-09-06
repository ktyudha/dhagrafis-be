<?php

namespace App\Http\Controllers;

use App\Http\Resources\EventResource;
use App\Models\Event;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

class EventController extends Controller
{
    public function  index(Request $request)
    {
        $events = Event::with('author')
            ->published()
            ->when($request->search, function (Builder $query, string $key) {
                $query->search($key);
            })
            ->latest('end_date')
            ->paginate(10);

        return EventResource::collection($events);
    }

    public function show(string $slug)
    {
        $event = Event::with('author')->published()
            ->where('slug', $slug)
            ->firstOrFail();

        return new EventResource($event);
    }
}
