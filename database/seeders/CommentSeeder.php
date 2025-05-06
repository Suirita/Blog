<?php

namespace Database\Seeders;

use App\Models\Comment;
use Illuminate\Database\Seeder;

class CommentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $comments = [
            [
                'content' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed euismod, nisl eu ultricies ultricies, nunc purus ultrices nunc, eu tincidunt nisi nisl sit amet nunc. Donec euismod, nisl eu ultricies ultricies, nunc purus ultrices nunc, eu tincidunt nisi nisl sit amet nunc. Donec euismod, nisl eu ultricies ultricies, nunc purus ultrices nunc, eu tincidunt nisi nisl sit amet nunc.',
                'user_id' => 1,
                'article_id' => 1,
            ],
            [
                'content' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed euismod, nisl eu ultricies ultricies, nunc purus ultrices nunc, eu tincidunt nisi nisl sit amet nunc. Donec euismod, nisl eu ultricies ultricies, nunc purus ultrices nunc, eu tincidunt nisi nisl sit amet nunc. Donec euismod, nisl eu ultricies ultricies, nunc purus ultrices nunc, eu tincidunt nisi nisl sit amet nunc.',
                'user_id' => 2,
                'article_id' => 1,
            ],
            [
                'content' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed euismod, nisl eu ultricies ultricies, nunc purus ultrices nunc, eu tincidunt nisi nisl sit amet nunc. Donec euismod, nisl eu ultricies ultricies, nunc purus ultrices nunc, eu tincidunt nisi nisl sit amet nunc. Donec euismod, nisl eu ultricies ultricies, nunc purus ultrices nunc, eu tincidunt nisi nisl sit amet nunc.',
                'user_id' => 1,
                'article_id' => 2,
            ],
            [
                'content' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed euismod, nisl eu ultricies ultricies, nunc purus ultrices nunc, eu tincidunt nisi nisl sit amet nunc. Donec euismod, nisl eu ultricies ultricies, nunc purus ultrices nunc, eu tincidunt nisi nisl sit amet nunc. Donec euismod, nisl eu ultricies ultricies, nunc purus ultrices nunc, eu tincidunt nisi nisl sit amet nunc.',
                'user_id' => 1,
                'article_id' => 3,
            ],
            [
                'content' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed euismod, nisl eu ultricies ultricies, nunc purus ultrices nunc, eu tincidunt nisi nisl sit amet nunc. Donec euismod, nisl eu ultricies ultricies, nunc purus ultrices nunc, eu tincidunt nisi nisl sit amet nunc. Donec euismod, nisl eu ultricies ultricies, nunc purus ultrices nunc, eu tincidunt nisi nisl sit amet nunc.',
                'user_id' => 1,
                'article_id' => 4,
            ],
            [
                'content' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed euismod, nisl eu ultricies ultricies, nunc purus ultrices nunc, eu tincidunt nisi nisl sit amet nunc. Donec euismod, nisl eu ultricies ultricies, nunc purus ultrices nunc, eu tincidunt nisi nisl sit amet nunc. Donec euismod, nisl eu ultricies ultricies, nunc purus ultrices nunc, eu tincidunt nisi nisl sit amet nunc.',
                'user_id' => 1,
                'article_id' => 5,
            ]
        ];

        foreach ($comments as $comment) {
            Comment::create($comment);
        }
    }
}
