<?php

namespace App\Http\Controllers;

use App\Jobs\GenerateUnvaccinatedReportJob;
use App\Models\Reports;
use Illuminate\Http\JsonResponse;

class ReportsController extends Controller
{

    public function generateUnvaccinatedReport(): JsonResponse
    {
        $report = Reports::create([
            'name' => 'Unvaccined report',
            'status' => 'pending',
        ]);

        GenerateUnvaccinatedReportJob::dispatch($report->id);

        return response()->json([
            'message' => 'Generating Report',
            'report_id' => $report->id
        ], 200);
    }
    public function getReportStatus($id): JsonResponse
    {
        $report = Reports::find($id);

        if (!$report) {
            return response()->json(['error' => 'Report not found'], 404);
        }

        return response()->json([
            'status' => $report->status,
            'file_path' => $report->file_path
        ]);
    }
}
