<?php

namespace App\Http\Controllers\Blog;

use App\Models\User;
use Inertia\Inertia;
use App\Models\Article;
use App\Exports\UsersExport;
use App\Imports\UsersImport;
use Illuminate\Http\Request;
use App\Http\Services\UserService;
use App\Http\Controllers\Controller;
use App\Http\Requests\ImportRequest;
use Maatwebsite\Excel\Facades\Excel;
use App\Http\Requests\UserEditRequest;
use App\Http\Requests\UserCreateRequest;

class UserController extends Controller
{
    protected UserService $userService;

    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $data = $this->userService->getAll();

        return Inertia::render('dashboard/Users', [
            'users' => $data['users'],
            'roles' => $data['roles'],
            'isAdmin' => true
        ]);
    }

    /**
     *  Handle import
     */
    public function import(ImportRequest $request)
    {
        Excel::import(new UsersImport, $request->file('file'));
    }

    /**
     *  Handle export
     */
    public function export()
    {
        return Excel::download(new UsersExport, 'users.xlsx');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(UserCreateRequest $request)
    {
        $this->userService->create($request->all());
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UserEditRequest $request, User $user)
    {
        $this->userService->edit($request->all(), $user);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user)
    {
        $this->userService->delete($user);
    }
}
