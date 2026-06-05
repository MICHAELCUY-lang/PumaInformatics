<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\Article;
use App\Repositories\Contracts\ArticleRepositoryInterface;
use Illuminate\Http\Request;

class ArticleController extends Controller
{
    protected $articleRepository;

    public function __construct(ArticleRepositoryInterface $articleRepository)
    {
        $this->articleRepository = $articleRepository;
    }

    public function index()
    {
        // For the public index, we want published articles only
        $articles = Article::with(['author', 'media'])
            ->where('status', 'published')
            ->where('published_at', '<=', now())
            ->latest('published_at')
            ->paginate(12);

        return view('public.news.index', compact('articles'));
    }

    public function show(string $slug)
    {
        $article = Article::with(['author', 'media'])
            ->where('slug', $slug)
            ->where('status', 'published')
            ->where('published_at', '<=', now())
            ->firstOrFail();

        // Increment views (skipped, requires schema update)

        // Fetch related articles
        $relatedArticles = Article::with(['author', 'media'])
            ->where('status', 'published')
            ->where('id', '!=', $article->id)
            ->latest('published_at')
            ->take(3)
            ->get();

        return view('public.news.show', compact('article', 'relatedArticles'));
    }
}
