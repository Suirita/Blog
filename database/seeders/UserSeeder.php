<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = [
            [
                'name' => 'Admin',
                'email' => 'admin@gmail.com',
                'password' => bcrypt('admin123'),
            ],
            [
                'name' => 'Author',
                'email' => 'author@gmail.com',
                'password' => bcrypt('author123'),
            ],
            [
                'name' => 'User',
                'email' => 'user@gmail.com',
                'password' => bcrypt('user12345'),
            ],

        ];

        foreach ($users as $user) {
            User::create($user);
        }

        $user1 = User::where('email', 'admin@gmail.com')->first();
        $user1->assignRole('admin');

        $user2 = User::where('email', 'author@gmail.com')->first();
        $user2->assignRole('author');

        $user3 = User::where('email', 'user@gmail.com')->first();
        $user3->assignRole('user');
    }
}
