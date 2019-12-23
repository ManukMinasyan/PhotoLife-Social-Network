<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Report;

class ReportsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $reports = Report::whereType('post')
            ->with('type', 'member', 'reportable', 'reportable.media')
            ->orderBy('created_at', 'DESC')
            ->paginate(20);

        return view('dashboard.report.index', compact('reports'));
    }

    /**
     * @param Report $report
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Exception
     */
    public function markSafe(Report $report)
    {
        $report->delete();

        return redirect()->back();
    }

    /**
     * @param Report $report
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Exception
     */
    public function deletePost(Report $report)
    {
        $report->reportable->reports()->delete();
        $report->reportable->delete();

        return redirect()->back();
    }
}
