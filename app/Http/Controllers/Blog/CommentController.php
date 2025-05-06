<?php

namespace App\Http\Controllers\Blog;

use Inertia\Inertia;
use App\Models\Comment;
use App\Exports\CommentsExport;
use App\Imports\CommentsImport;
use App\Http\Controllers\Controller;
use App\Http\Requests\ImportRequest;
use Maatwebsite\Excel\Facades\Excel;
use App\Http\Requests\CommentRequest;
use App\Http\Services\CommentService;

class CommentController extends Controller
{
    protected CommentService $commentService;

    public function __construct(CommentService $commentService)
    {
        $this->commentService = $commentService;
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $data = $this->commentService->getAll();

        return Inertia::render('dashboard/Comments', [
            'comments' => $data['comments'],
            'articles' => $data['articles'],
            'users' => $data['users'],
            'isAdmin' => true
        ]);
    }

    /**
     * Handle import
     */
    public function import(ImportRequest $request)
    {
        Excel::import(new CommentsImport, $request->file('file'));
    }

    /**
     * Handle export
     */
    public function export()
    {
        return Excel::download(new CommentsExport, 'comments.xlsx');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CommentRequest $request)
    {
        $this->commentService->create($request->all());
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(CommentRequest $request, Comment $comment)
    {
        $this->commentService->edit($request->all(), $comment);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Comment $comment)
    {
        $this->commentService->delete($comment);
    }
}
