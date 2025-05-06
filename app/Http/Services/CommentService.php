<?php

namespace App\Http\Services;

use App\Models\User;
use App\Models\Article;
use App\Models\Comment;

class CommentService
{
    public function getAll()
    {
        $comments = Comment::with('user', 'article')->orderBy('created_at', 'desc')->get();
        $artiles = Article::all();
        $users = User::all();

        return [
            'comments' => $comments,
            'articles' => $artiles,
            'users' => $users
        ];
    }

    public function create(array $data)
    {
        $comment = new Comment(
            [
                'content' => $data['content'],
                'user_id' => $data['user'],
                'article_id' => $data['article'],
            ]
        );
        $comment->save();
    }

    public function edit($data, $comment)
    {
        $comment = Comment::find($comment->id);
        $comment->content = $data['content'];
        $comment->save();
    }

    public function delete(Comment $comment)
    {
        $comment->delete();
    }
}
