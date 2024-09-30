<?php 

namespace App\Services;

use App\Models\Employees;
use Illuminate\Support\Facades\Cache;

class EmployeesCacheService
{
    protected $key = 'employees';

    protected $cpfFormatService;

    public function __construct(CpfFormatService $cpfFormatService)
    {
        $this->cpfFormatService = $cpfFormatService;
    }
    /**
    * Store employees in a cache.
    */
    public function getAllEmployees()
    {
        return Cache::remember($this->key, 60, function () {
            return Employees::with(['vaccines'])->get()->map(function ($employee) {
                $vaccineData = $employee->vaccines->map(function ($vaccine) {
                    return [
                        'vaccine_id' => $vaccine->id,
                        'name' => $vaccine->name,
                        'first_dose_vaccine' => $vaccine->pivot->first_dose_vaccine,
                        'second_dose_vaccine' => $vaccine->pivot->second_dose_vaccine,
                        'third_dose_vaccine' => $vaccine->pivot->third_dose_vaccine,
                        'batch' => $vaccine->pivot->batch,
                        'validate_date' => $vaccine->pivot->validate_date,
                    ];
                });

                return [
                    'id' => $employee->id,
                    'full_name' => $employee->full_name,
                    'birth_date' => $employee->birth_date,
                    'is_pcd' => $employee->is_pcd,
                    'cpf' => $employee->anonymized_cpf,
                    'vaccines' => $vaccineData
                ];
            });
        });
    }

    /**
     * Clear employees cache.
     */
    public function clearCache()
    {
        Cache::forget($this->key);
    }
}
