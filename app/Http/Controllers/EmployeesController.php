<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreEmployeesRequest;
use App\Http\Requests\UpdateEmployeeRequest;
use App\Models\Employees;
use App\Services\CpfFormatService;
use App\Services\CpfValidatorService;
use App\Services\EmployeesCacheService;
use App\Services\NameValidatorService;
use Exception;
use Illuminate\Http\JsonResponse;
use InvalidArgumentException;

class EmployeesController extends Controller
{
    protected $cpfValidator;

    protected $cpfFormatService;
    protected $employeesCacheService;

    protected $nameValidatorService;

    public function __construct(CpfValidatorService $cpfValidator, CpfFormatService $cpfFormatService,EmployeesCacheService $employeesCacheService, NameValidatorService $nameValidatorService)
    {
        $this->cpfValidator = $cpfValidator;
        $this->cpfFormatService = $cpfFormatService;
        $this->employeesCacheService = $employeesCacheService;
        $this->nameValidatorService = $nameValidatorService;
    }

    public function store(StoreEmployeesRequest $request): JsonResponse
    {
        try
        {
            $this->cpfValidator->validate($request->input('cpf'));
            $this->nameValidatorService->validate($request->input('full_name'));
            
            $anonymized_cpf = $this->cpfFormatService->format($request->input('cpf'));

            $employee = Employees::create([
                'cpf' => $request->input('cpf'),
                'anonymized_cpf' => $anonymized_cpf,
                'full_name' => $request->input('full_name'),
                'birth_date' => $request->input('birth_date'),
                'is_pcd' => $request->input('is_pcd'),
            ]);

            $this->employeesCacheService->clearCache();

            $employee->cpf = $this->cpfFormatService->format($employee->cpf);

            return response()->json($employee, 201);

        } 
        catch(InvalidArgumentException $e)
        {
            return response()->json(['error' => $e->getMessage()], 422);
        }
        catch(Exception $e)
        {
            return response()->json(['error' => "Error while creating employee " . $e->getMessage()], 500);
        }
    }

    public function show(): JsonResponse
    {
        try
        {
            
            $employee = $this->employeesCacheService->getAllEmployees();

            if (!$employee) {
                return response()->json(['error' => "No employee found."], 404);
            }

            return response()->json($employee, 200);
        }
        catch(Exception $e)
        {
            return response()->json(['error' => 'Error getting employees.' . $e->getMessage()], 500);
        }
    }

    public function update(UpdateEmployeeRequest $request, $id): JsonResponse
    {
        try 
        {
            $employee = Employees::find($id);

            if (!$employee) {
                return response()->json(['error' => 'Employee not found.'], 404);
            }
            
            $employee->update($request->only([
                'full_name',
                'birth_date',
                'is_pcd'
            ]));

            $this->employeesCacheService->clearCache();

            $employee->cpf = $this->cpfFormatService->format($employee->cpf);

            return response()->json($employee, 200);

        } 
        catch (InvalidArgumentException $e) 
        {
            return response()->json(['error' => $e->getMessage()], 422);
        } 
        catch (Exception $e) 
        {
            return response()->json(['error' => 'Error updating employee.' . $e->getMessage()], 500);
        }
    }

    public function destroy($id): JsonResponse
    {
        try
        {
            $employee = Employees::find($id);

            if (!$employee) {
                return response()->json(['error' => 'Employee not found.'], 404);
            }

            $employee->delete();

            $this->employeesCacheService->clearCache();

            return response()->json(null, 204);
        }
        catch(Exception $e)
        {
            return response()->json(['error' => 'Error deleting employees.' . $e->getMessage()], 500);
        }
    }

}
