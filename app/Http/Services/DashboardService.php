<?php

namespace App\Http\Services;

use App\Models\User;
use App\Models\Article;
use App\Models\Comment;
use App\Models\Category;

class DashboardService
{
    function get()
    {
        $data = [
            'articles' => Article::with('category', 'user', 'comments.user')->orderBy('created_at', 'desc')->get(),
            'categories' => Category::all(),
            'users' => User::with('roles')->get(),
            'comments' => Comment::with('user', 'article')->orderBy('created_at', 'desc')->get(),
        ];

        return $data;
    }
}
