<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreEmployeeVaccineRequest;
use App\Http\Requests\UpdateEmployeeVaccineRequest;
use App\Models\Employees;
use Illuminate\Http\JsonResponse;

class EmployeeVaccineController extends Controller
{
    public function store(StoreEmployeeVaccineRequest $request, $employeeId): JsonResponse
    {

        $employee = Employees::findOrFail($employeeId);

        $employee->vaccines()->attach($request->vaccine_id, [
            'batch' => $request->batch,
            'validate_date' => $request->validate_date,
            'first_dose_vaccine' => $request->first_dose_vaccine,
            'second_dose_vaccine' => $request->second_dose_vaccine,
            'third_dose_vaccine' => $request->third_dose_vaccine
        ]);

        return response()->json(['message' => 'Vaccine applied to employee successfully.']);
    }

    public function update(UpdateEmployeeVaccineRequest $request, $employeeId, $vaccineId): JsonResponse
    {

        $employee = Employees::findOrFail($employeeId);

        $pivotData = $employee->vaccines()->wherePivot('vaccines_id', 1)->first();

        if ($pivotData) {
            $employee->vaccines()->updateExistingPivot(1, [
                'vaccines_id' => $request->vaccine_id,
                'batch' => $vaccineId != 1 ? $request->batch : null,
                'validate_date' => $vaccineId != 1 ? $request->validate_date : null,
                'first_dose_vaccine' => $vaccineId != 1 ? $request->first_dose_vaccine : null,
                'second_dose_vaccine' => $vaccineId != 1 ? $request->second_dose_vaccine : null,
                'third_dose_vaccine' => $vaccineId != 1 ? $request->third_dose_vaccine : null,
            ]);

            return response()->json(['message' => 'Vaccine record updated.']);
        }

        $pivotData = $employee->vaccines()->find($vaccineId);
        if (!$pivotData) {
            return response()->json(['message' => 'Vaccine not found for this employee.'], 404);
        }

        $employee->vaccines()->updateExistingPivot($vaccineId, [
            'batch' => $request->batch,
            'validate_date' => $request->validate_date,
            'first_dose_vaccine' => $request->first_dose_vaccine,
            'second_dose_vaccine' => $request->second_dose_vaccine,
            'third_dose_vaccine' => $request->third_dose_vaccine,
        ]);

        return response()->json(['message' => 'Vaccine updated successfully.']);
    }


    public function getEmployeeVaccines($employeeId)
    {
        $employee = Employees::with('vaccines')->findOrFail($employeeId);

        return response()->json($employee->vaccines);
    }
}
