<?php

namespace App\Exports;

use App\Models\Article;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithHeadings;

class ArticlesExport implements FromCollection, WithMapping, WithHeadings
{
    /**
     * Retrieve a collection of articles with the necessary relationships.
     */
    public function collection()
    {
        return Article::with(['category', 'user', 'comments.user'])
            ->orderBy('created_at', 'desc')
            ->get();
    }

    /**
     * Map each article to an array with the desired fields.
     *
     * @param  \App\Models\Article  $article
     * @return array
     */
    public function map($article): array
    {
        $commentsData = $article->comments->map(function ($comment) {
            return [
                'content'   => $comment->content,
                'user_name' => $comment->user->name,
            ];
        })->toArray();

        $commentsJson = json_encode($commentsData);

        return [
            $article->title,
            $article->content,
            $article->category->name,
            $article->user->name,
            $article->views,
            $article->created_at,
            $article->updated_at,
            $commentsJson,
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
            'Title',
            'Content',
            'Category',
            'User',
            'Views',
            'Created At',
            'Updated At',
            'Comments',
        ];
    }
}
