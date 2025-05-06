<?php

namespace App\Http\Services;

use App\Models\User;
use App\Models\Article;
use App\Models\Category;
use Illuminate\Support\Facades\Auth;

class ArticleService
{
    function getAll()
    {
        $articles = Article::with('category', 'user', 'comments.user')->orderBy('created_at', 'desc')->get();
        $categories = Category::all();
        $users = User::all();

        return ['articles' => $articles, 'categories' => $categories, 'users' => $users];
    }

    function get()
    {
        $articles = Article::with('category', 'user', 'comments.user')->where('user_id', Auth::user()->id)->orderBy('created_at', 'desc')->get();
        $categories = Category::all();

        return ['articles' => $articles, 'categories' => $categories];
    }

    function find(Article $article)
    {
        return Article::with('category', 'user', 'comments.user')->find($article->id);
    }

    function create($data)
    {
        $article = new Article();
        $article->title = $data['title'];
        $article->content = $data['content'];
        $article->category_id = $data['category'];
        $article->user_id = Auth::user()->id;
        $article->save();
    }

    function edit($data, $article)
    {
        $article = Article::find($article->id);
        $article->title = $data['title'];
        $article->content = $data['content'];
        $article->category_id = $data['category'];
        $article->save();
    }

    function delete(Article $article)
    {
        $article->delete();
    }
}
