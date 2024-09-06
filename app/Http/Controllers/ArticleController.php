<?php

namespace App\Http\Controllers;

use App\Http\Resources\ArticleResource;
use App\Models\Article;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

class ArticleController extends Controller
{
    private $columns = ['id', 'title', 'slug', 'image', 'excerpt', 'created_at', 'category_id', 'author_id', 'published_at'];

    public function index(Request $request)
    {
        $articles = Article::with(['category', 'author'])
            ->published()
            ->when($request->search, function (Builder $query, string $key) {
                $query->search($key);
            })
            ->when($request->category, function (Builder $query, string $category) {
                $query->whereRelation('category', 'slug', $category);
            })
            ->latest('published_at')
            ->paginate(10, $this->columns);

        return ArticleResource::collection($articles);
    }

    public function show(string $slug)
    {
        $article = Article::with(['category', 'author'])
            ->published()
            ->where('slug', $slug)
            ->firstOrFail(['id', 'title', 'slug', 'image', 'body', 'created_at', 'category_id', 'author_id', 'published_at']);

        $releated = Article::with(['category', 'author'])
            ->published()
            ->where('category_id', $article->category->id)
            ->latest('published_at')
            ->limit(3)
            ->get($this->columns);

        return [
            'article' => new ArticleResource($article),
            'releated_articles' => ArticleResource::collection($releated)
        ];
    }
}
