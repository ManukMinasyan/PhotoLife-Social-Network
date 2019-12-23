<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\Report\ReportTypeCollection;
use App\Models\Post;
use App\Models\ReportType;
use Illuminate\Support\Facades\Auth;

class ReportController extends Controller
{
    /**
     * @return ReportTypeCollection
     */
    public function getReportTypes()
    {
        $report_types = ReportType::all();

        return new ReportTypeCollection($report_types);
    }


    /**
     * @param Post $post
     * @param ReportType $reportType
     * @return \Illuminate\Http\JsonResponse
     */
    public function postReport(Post $post, ReportType $reportType)
    {
        $post->reports()->firstOrCreate([
            'member_id' => Auth::id(),
            'report_type_id' => $reportType->id
        ]);

        return response()->json(['success' => true]);
    }
}
