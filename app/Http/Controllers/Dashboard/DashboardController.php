<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Member;
use App\Models\Post;
use App\Models\Report;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        $statistics = [
            'members_count' => Member::count(),
            'posts_count' => Post::count(),
            'reports_count' => Report::count(),
        ];

        return view('dashboard.dashboard', compact('statistics'));
    }
}
