<?php

namespace App\Http\Controllers\Blog;

use Inertia\Inertia;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Http\Services\DashboardService;

class DashboardController extends Controller
{
    protected DashboardService $dashboardService;

    public function __construct(DashboardService $dashboardService)
    {
        $this->dashboardService = $dashboardService;
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $data = $this->dashboardService->get();

        if (Auth::user()->hasRole('admin')) {
            return Inertia::render('dashboard/Dashboard', [
                'articles' => $data['articles'],
                'categories' => $data['categories'],
                'users' => $data['users'],
                'comments' => $data['comments'],
                'isAdmin' => true
            ]);
        } elseif (Auth::user()->hasRole('author')) {
            return Inertia::render('dashboard/Dashboard', [
                'articles' => $data['articles'],
                'categories' => $data['categories'],
                'users' => $data['users'],
                'comments' => $data['comments'],
                'isAdmin' => false
            ]);
        }
    }
}
