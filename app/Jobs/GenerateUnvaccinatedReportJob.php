<?php

namespace App\Jobs;

use App\Models\Employees;
use App\Models\Reports;
use App\Services\FileService;
use Exception;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use InvalidArgumentException;

class GenerateUnvaccinatedReportJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */
    protected $reportId;

    public function __construct($reportId)
    {
        $this->reportId = $reportId;
    }

    /**
     * Execute the job.
     */
    public function handle(FileService $fileService): void
    {
        try
        {
            $report = Reports::find($this->reportId);

            if (!$report) {
                Log::error("Report not found with ID: " . $this->reportId);
                return;
            }
    
            $report->update(['status' => 'generating']);
    
            $employees = Employees::whereHas('vaccines', function($query) {
                $query->where('vaccines_id', 1);
            })->get(['full_name', 'anonymized_cpf']);
    
            if ($employees->isEmpty()) {
                Log::info("No unvaccinated employees found.");
                $report->update(['status' => 'no-data', 'file_path' => null]);
                return;
            }
    
            $reportContent = "Name, CPF\n";
    
            foreach ($employees as $employee) {
                $reportContent .= "{$employee->full_name}, {$employee->anonymized_cpf}\n";
            }
    
            $filePath = 'report/unvaccinated.csv';

            $fileService->savePublicFile("public/$filePath", $reportContent);

            $report->update([
                'status' => 'ready',
                'file_path' => $filePath
            ]);
        }
        catch(Exception $e)
        {
            throw new InvalidArgumentException($e->getMessage());
        }
        
    }
}
