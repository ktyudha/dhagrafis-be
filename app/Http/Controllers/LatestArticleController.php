<?php

namespace App\Http\Controllers;

use App\Http\Resources\ArticleResource;
use App\Models\Article;
use Illuminate\Http\Request;

class LatestArticleController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request)
    {
        $articles = Article::with(['category', 'author'])
            ->published()->latest('published_at')->limit(3)
            ->get(['id', 'title', 'slug', 'image', 'excerpt', 'category_id', 'author_id', 'published_at']);

        return ArticleResource::collection($articles);
    }
}
