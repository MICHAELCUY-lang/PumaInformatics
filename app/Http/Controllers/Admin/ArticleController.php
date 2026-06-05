<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\ArticleService;
use App\Http\Requests\Admin\StoreArticleRequest;
use App\DTOs\ArticleData;
use App\Models\Article;

class ArticleController extends Controller
{
    protected ArticleService $service;

    public function __construct(ArticleService $service)
    {
        $this->service = $service;
    }

    public function index()
    {
        $articles = $this->service->paginateArticles();
        return view('admin.articles.index', compact('articles'));
    }

    public function create()
    {
        return view('admin.articles.create');
    }

    public function store(StoreArticleRequest $request)
    {
        $data = ArticleData::fromArray($request->validated());
        $article = $this->service->createArticle($data, auth()->id());

        if ($request->hasFile('cover_image')) {
            $article->addMediaFromRequest('cover_image')->toMediaCollection('cover');
        }

        return redirect()->route('admin.articles.index')->with('success', 'Article created successfully.');
    }

    public function edit(Article $article)
    {
        return view('admin.articles.edit', compact('article'));
    }

    public function update(\App\Http\Requests\Admin\UpdateArticleRequest $request, Article $article)
    {
        $data = ArticleData::fromArray($request->validated());
        $this->service->updateArticle($article->id, $data);

        if ($request->hasFile('cover_image')) {
            $article->addMediaFromRequest('cover_image')->toMediaCollection('cover');
        }

        return redirect()->route('admin.articles.index')->with('success', 'Article updated successfully.');
    }

    public function destroy(Article $article)
    {
        $this->service->deleteArticle($article->id);
        return redirect()->route('admin.articles.index')->with('success', 'Article deleted.');
    }
}
