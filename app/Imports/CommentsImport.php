<?php

namespace App\Imports;

use App\Models\Comment;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class CommentsImport implements ToModel, WithHeadingRow
{
    /**
     * Map a row from import file to a Comment model.
     *
     * @param array $row
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function model(array $row)
    {
        // Check if a comment with the same content already exists.
        if (Comment::where('content', $row['comment'])->exists()) {
            return null;
        }

        // Create the new comment.
        return Comment::create([
            'content' => $row['comment'],
            'user_id' => $row['user'],
            'article_id' => $row['article'],
            'created_at'  => $row['created_at'],
            'updated_at'  => $row['updated_at'],
        ]);
    }
}
