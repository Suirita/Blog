<?php

namespace App\Exports;

use App\Models\Comment;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\FromCollection;

class CommentsExport implements FromCollection, WithMapping, WithHeadings
{
    /**
     * Retrieve a collection of users with the necessary relationships.
     */
    public function collection()
    {
        return Comment::with('user', 'article')->get();
    }

    /**
     * Map each comment to an array with the desired fields.
     *
     * @param  \App\Models\Comment  $comment
     * @return array
     */
    public function map($comment): array
    {
        return [
            $comment->content,
            $comment->user->name,
            $comment->article->title,
            $comment->created_at,
            $comment->updated_at,
        ];
    }

    /**
     * Define the headings for your export.
     *
     * @return array
     */
    public function headings(): array
    {
        return [
            'Comment',
            'User',
            'Article',
            'Created At',
            'Updated At',
        ];
    }
}
