<?php

namespace App\Http\Controllers\Blog;

use Inertia\Inertia;
use App\Models\Category;
use App\Exports\CategoriesExport;
use App\Imports\CategoriesImport;
use App\Http\Controllers\Controller;
use App\Http\Requests\ImportRequest;
use Maatwebsite\Excel\Facades\Excel;
use App\Http\Services\CategoryService;
use App\Http\Requests\CategoryRequest;

class CategoryController extends Controller
{
    protected CategoryService $categoryService;

    public function __construct(CategoryService $categoryService)
    {
        $this->categoryService = $categoryService;
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $categories = $this->categoryService->getAll();

        return Inertia::render('dashboard/Categories', [
            'categories' => $categories,
            'isAdmin' => true
        ]);
    }

    /**
     *  Handle import
     */
    public function import(ImportRequest $request)
    {
        Excel::import(new CategoriesImport, $request->file('file'));
    }

    /**
     *  Handle export
     */
    public function export()
    {
        return Excel::download(new CategoriesExport, 'categories.xlsx');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CategoryRequest $request)
    {
        $this->categoryService->create($request->all());
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(CategoryRequest $request, Category $category)
    {
        $this->categoryService->edit($request->all(), $category);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Category $category)
    {
        $this->categoryService->delete($category);
    }
}
