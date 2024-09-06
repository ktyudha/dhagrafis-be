<?php

namespace App\Http\Controllers;

use App\Http\Resources\ArtworkResource;
use App\Models\Artwork;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

class ArtworkController extends Controller
{
    public function index(Request $request)
    {
        $artworks = Artwork::with('author')
            ->published()
            ->when($request->search, function (Builder $query, string $key) {
                $query->search($key);
            })
            ->latest('published_at')
            ->paginate(10);

        return ArtworkResource::collection($artworks);
    }

    public function show(string $slug)
    {
        $event = Artwork::with('author')
            ->published()
            ->where('slug', $slug)
            ->firstOrFail();

        return new ArtworkResource($event);
    }
}
