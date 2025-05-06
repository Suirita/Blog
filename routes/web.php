<?php

use Inertia\Inertia;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Blog\UserController;
use App\Http\Controllers\Blog\ArticleController;
use App\Http\Controllers\Blog\CommentController;
use App\Http\Controllers\Blog\CategoryController;
use App\Http\Controllers\Blog\DashboardController;

Route::get('/', [ArticleController::class, 'index'])->name('home');

Route::middleware('auth')->group(function () {

    Route::middleware('IsAuthor')->group(function () {
        Route::get('dashboard', [DashboardController::class, 'index'])->name('dashboard');
    });

    Route::middleware('IsAdmin')->group(function () {
        Route::post('articles/import', [ArticleController::class, 'import'])->name('articles.import');
        Route::get('articles/export', [ArticleController::class, 'export'])->name('articles.export');

        Route::post('categories/import', [CategoryController::class, 'import'])->name('categories.import');
        Route::get('categories/export', [CategoryController::class, 'export'])->name('categories.export');

        Route::post('users/import', [UserController::class, 'import'])->name('users.import');
        Route::get('users/export', [UserController::class, 'export'])->name('users.export');

        Route::post('comments/import', [CommentController::class, 'import'])->name('comments.import');
        Route::get('comments/export', [CommentController::class, 'export'])->name('comments.export');

        Route::resource('categories', CategoryController::class);
        Route::resource('users', UserController::class);
        Route::resource('comments', CommentController::class);
    });
});

Route::resource('articles', ArticleController::class);


require __DIR__ . '/settings.php';
require __DIR__ . '/auth.php';
