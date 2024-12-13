<?php

namespace App\Http\Controllers\Api;

use App\Data\ArticleData;
use App\Http\Controllers\Controller;
use App\Models\Article;
use Spatie\QueryBuilder\QueryBuilder;

class ArticleController extends Controller
{
    public function index()
    {
        $articles = QueryBuilder::for(Article::class)
                            ->allowedFilters(['title', 'description', 'source'])
                            ->allowedSorts(['id', 'published_at'])
                            ->paginate(request('per_page', 20))
                            ->withQueryString();

        return ArticleData::collect($articles);
    }
}
