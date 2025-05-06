<?php

namespace App\Http\Services;

use App\Models\Category;

class CategoryService
{
    function getAll()
    {
        return Category::orderBy('created_at', 'desc')->orderBy('created_at', 'desc')->get();
    }

    function create($data)
    {
        $data['name'] = ucfirst(strtolower($data['name']));
        return Category::create($data);
    }

    function edit($data, $article)
    {
        $article = Category::find($article->id);
        $article->name = $data['name'];
        $article->save();
    }

    function delete(Category $category)
    {
        $category->delete();
    }
}
