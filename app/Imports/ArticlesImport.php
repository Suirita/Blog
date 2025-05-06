<?php

namespace App\Imports;

use App\Models\Article;
use App\Models\Category;
use App\Models\User;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class ArticlesImport implements ToModel, WithHeadingRow
{
    /**
     * Map a row from the import file to an Article model.
     *
     * @param array $row
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function model(array $row)
    {
        // Check if an article with the same title already exists.
        if (Article::where('title', $row['title'])->exists()) {
            // Skip creating a duplicate article.
            return null;
        }

        // Get the related category and user, if they exist.
        $category = Category::where('name', $row['category'])->first();
        $user = User::where('name', $row['user'])->first();

        // Create the new article.
        $article = new Article([
            'title'       => $row['title'],
            'content'     => $row['content'],
            'category_id' => $category ? $category->id : null,
            'user_id'     => $user ? $user->id : null,
            'views'       => $row['views'],
            'created_at'  => $row['created_at'],
            'updated_at'  => $row['updated_at'],
        ]);

        $article->save();

        // If there are comments provided in the row, import them as well.
        if (isset($row['comments']) && !empty($row['comments'])) {
            $commentsData = json_decode($row['comments'], true);

            if (is_array($commentsData)) {
                foreach ($commentsData as $commentData) {
                    $commentUser = User::where('name', $commentData['user_name'])->first();

                    $article->comments()->create([
                        'content' => $commentData['content'],
                        'user_id' => $commentUser ? $commentUser->id : null,
                    ]);
                }
            }
        }

        return $article;
    }
}
