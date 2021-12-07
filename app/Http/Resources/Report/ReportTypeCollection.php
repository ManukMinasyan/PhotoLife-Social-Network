<?php

namespace App\Http\Resources\Report;

use App\Http\Resources\Report\ReportTypeResource;
use App\Models\ReportType;
use Illuminate\Http\Resources\Json\ResourceCollection;

class ReportTypeCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @param  \Illuminate\Http\Request $request
     * @return array
     */
    public function toArray($request)
    {
        $this->collection->transform(function (ReportType $reportType) {
            return new ReportTypeResource($reportType);
        });

        return parent::toArray($request);
    }
}
