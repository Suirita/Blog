<?php

namespace App\Http\Controllers\Blog;

use Inertia\Inertia;
use App\Models\Article;
use App\Exports\ArticlesExport;
use App\Imports\ArticlesImport;
use App\Http\Controllers\Controller;
use App\Http\Requests\ImportRequest;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;
use App\Http\Services\ArticleService;
use App\Http\Requests\ArticleEditRequest;
use App\Http\Requests\ArticleCreateRequest;

class ArticleController extends Controller
{
    protected ArticleService $articleService;

    public function __construct(ArticleService $articleService)
    {
        $this->articleService = $articleService;
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $user            = Auth::user();
        $isAuthenticated = (bool) $user;
        $isAdmin         = $isAuthenticated && $user->hasRole('admin');
        $isAuthor        = $isAuthenticated && $user->hasRole('author');

        // Decide which data to fetch
        $data = $isAuthor
            ? $this->articleService->get()
            : $this->articleService->getAll();

        // Pick the right Inertia view
        $view = ($isAdmin || $isAuthor)
            ? 'dashboard/Articles'
            : 'Articles';

        // Base payload
        $payload = [
            'articles'   => $data['articles'],
            'categories' => $data['categories'],
        ];

        if ($isAdmin || $isAuthor) {
            // Dashboard users only need isAdmin
            $payload['isAdmin'] = $isAdmin;
        } else {
            // Guests & other roles get users list and auth status
            $payload['users']          = $data['users'];
            $payload['isAuthenticated'] = $isAuthenticated;
        }

        return Inertia::render($view, $payload);
    }


    /**
     *  Handle import
     */
    public function import(ImportRequest $request)
    {
        Excel::import(new ArticlesImport, $request->file('file'));
    }

    /**
     *  Handle export
     */
    public function export()
    {
        return Excel::download(new ArticlesExport, 'articles.xlsx');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function show(Article $article)
    {
        $article = $this->articleService->find($article);

        return Inertia::render('ArticleDetail', [
            'article' => $article
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(ArticleCreateRequest $request)
    {
        $this->articleService->create($request->all());
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(ArticleEditRequest $request, Article $article)
    {
        $this->articleService->edit($request->all(), $article);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Article $article)
    {
        $this->articleService->delete($article);
    }
}
