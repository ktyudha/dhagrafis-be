<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Article;
use App\Http\Requests\StoreArticleRequest;
use App\Http\Requests\UpdateArticleRequest;
use App\Http\Resources\ArticleResource;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ArticleController extends Controller
{
    /**
     * Create the controller instance.
     */
    public function __construct()
    {
        $this->authorizeResource(Article::class, 'article');
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $articles = Article::with(['category', 'author', 'publisher'])
            ->when($request->user()->cannot('view all articles'), function (Builder $query) use ($request) {
                $query->where('author_id', $request->user()->id);
            })->when($request->search, function (Builder $query, string $key) {
                $query->search($key);
            })->when($request->category, function (Builder $query, string $category) {
                $query->whereRelation('category', 'slug', $category);
            })->latest()->paginate(10, ['id', 'title', 'slug', 'image', 'published_at', 'category_id', 'author_id', 'published_by']);

        return ArticleResource::collection($articles);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreArticleRequest $request)
    {
        $validated = $request->validated();
        $validated['slug'] = Str::slug($validated['title'], dictionary: ['&' => 'and']);
        $validated['excerpt'] = Str::limit($validated['body']);
        $validated['image'] = $request->file('image')->store('articles');
        $validated['author_id'] = $request->user()->id;

        $validated['published_by'] = request()->user()->id;
        $validated['published_at'] = now();

        $article = Article::create($validated);

        return response()->json([
            'success' => true,
            'article' => new ArticleResource($article),
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(Article $article)
    {
        $article->load(['author', 'category', 'publisher']);

        return response()->json([
            'success' => true,
            'article' => new ArticleResource($article)
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateArticleRequest $request, Article $article)
    {
        $validated = $request->validated();

        $validated['slug'] = Str::slug($validated['title'], dictionary: ['&' => 'and']);
        $validated['excerpt'] = Str::limit($validated['body']);
        // $validated['published_by'] = null;
        // $validated['published_at'] = null;
        $validated['published_by'] = request()->user()->id;
        $validated['published_at'] = now();

        if ($request->has('image')) {
            Storage::delete($article->image);
            $validated['image'] = $request->file('image')->store('articles');
        }

        $article->update($validated);

        return response()->json([
            'success' => true,
            'article' => new ArticleResource($article),
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Article $article)
    {
        Storage::delete($article->image);
        $article->delete();
        return response()->json([
            'success' => true,
            'message' => 'Article deleted successfully'
        ]);
    }

    /**
     * Publish the specified resource from storage.
     */
    public function publish(Article $article)
    {
        $this->authorize('publish', Article::class);

        $article->update([
            'published_by' => request()->user()->id,
            'published_at' => now(),
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Article published successfully',
            'article' => new ArticleResource($article)
        ]);
    }

    /**
     * Unpublish the specified resource from storage.
     */
    public function unpublish(Article $article)
    {
        $this->authorize('unpublish', Article::class);

        $article->update([
            'published_by' => null,
            'published_at' => null,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Article unpublished successfully',
            'article' => new ArticleResource($article)
        ]);
    }
}
