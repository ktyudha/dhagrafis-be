<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Artwork;
use App\Http\Requests\StoreArtworkRequest;
use App\Http\Requests\UpdateArtworkRequest;
use App\Http\Resources\ArtworkResource;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ArtworkController extends Controller
{
    /**
     * Create the controller instance.
     */
    public function __construct()
    {
        $this->authorizeResource(Artwork::class, 'artwork');
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $artworks = Artwork::with(['author', 'publisher'])
            ->when($request->user()->cannot('view all artworks'), function (Builder $query) use ($request) {
                $query->where('author_id', $request->user()->id);
            })->when($request->search, function (Builder $query, string $key) {
                $query->search($key);
            })->latest()->paginate(10);

        return ArtworkResource::collection($artworks);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreArtworkRequest $request)
    {
        $validated = $request->validated();
        $validated['slug'] = Str::slug($validated['title'], dictionary: ['&' => 'and']);
        $validated['image'] = $request->file('image')->store('artworks');
        $validated['author_id'] = $request->user()->id;

        $validated['published_by'] = request()->user()->id;
        $validated['published_at'] = now();

        $artwork = Artwork::create($validated);

        return response()->json([
            'success' => true,
            'artwork' => new ArtworkResource($artwork)
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(Artwork $artwork)
    {
        $artwork->load(['author', 'publisher']);

        return response()->json([
            'success' => true,
            'artwork' => new ArtworkResource($artwork)
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateArtworkRequest $request, Artwork $artwork)
    {
        $validated = $request->validated();

        $validated['slug'] = Str::slug($validated['title'], dictionary: ['&' => 'and']);
        // $validated['published_by'] = null;
        // $validated['published_at'] = null;
        $validated['published_by'] = request()->user()->id;
        $validated['published_at'] = now();

        if ($request->has('image')) {
            Storage::delete($artwork->image);
            $validated['image'] = $request->file('image')->store('artworks');
        }

        $artwork->update($validated);

        return response()->json([
            'success' => true,
            'artwork' => new ArtworkResource($artwork)
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Artwork $artwork)
    {
        Storage::delete($artwork->image);
        $artwork->delete();
        return response()->json([
            'success' => true,
            'message' => 'Artwork deleted successfully'
        ]);
    }

    /**
     * Publish the specified resource from storage.
     */
    public function publish(Artwork $artwork)
    {
        $this->authorize('publish', Artwork::class);

        $artwork->update([
            'published_by' => request()->user()->id,
            'published_at' => now(),
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Artwork published successfully',
            'artwork' => new ArtworkResource($artwork)
        ]);
    }

    /**
     * Unpublish the specified resource from storage.
     */
    public function unpublish(Artwork $artwork)
    {
        $this->authorize('unpublish', Artwork::class);

        $artwork->update([
            'published_by' => null,
            'published_at' => null,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Artwork unpublished successfully',
            'artwork' => new ArtworkResource($artwork)
        ]);
    }
}
