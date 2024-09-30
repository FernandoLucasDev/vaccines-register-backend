<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreVaccineRequest;
use App\Http\Requests\UpdateVaccineRequest;
use App\Models\Vaccines;
use App\Services\VaccinesCacheService;
use Exception;
use Illuminate\Http\JsonResponse;
use InvalidArgumentException;

class VaccinesController extends Controller
{
    protected $vaccineCacheService;

    public function __construct(VaccinesCacheService $vaccineCacheService)
    {
        $this->vaccineCacheService = $vaccineCacheService;
    }

    public function store(StoreVaccineRequest $request): JsonResponse
    {
        try
        {
            $vaccine = Vaccines::create([
                'name' => $request->input('name'),
                'producer_name' => $request->input('producer_name')
            ]);

            $this->vaccineCacheService->clearCache();

            return response()->json($vaccine, 201);
        }
        catch(InvalidArgumentException $e)
        {
            return response()->json(['error' => $e->getMessage()], 422);
        }
        catch(Exception $e)
        {
            return response()->json(['error' => "Error while creating vaccine: " . $e->getMessage()], 500);
        }
    }

    public function show(): JsonResponse
    {
        try
        {
            $vaccines = $this->vaccineCacheService->getAllVacinas();

            if (!$vaccines || $vaccines->isEmpty()) {
                return response()->json(['error' => 'No vaccines found.'], 404);
            }

            return response()->json($vaccines, 200);
        }
        catch(InvalidArgumentException $e)
        {
            return response()->json(['error' => $e->getMessage()], 422);
        }
        catch(Exception $e)
        {
            return response()->json(['error' => "Error while getting vaccines: " . $e->getMessage()], 500);
        }
    }

    public function update(UpdateVaccineRequest $request, $id): JsonResponse
    {
        try
        {
            $vaccine = Vaccines::find($id);

            if(!$vaccine) {
                return response()->json(['error' => 'Vaccine not found.'], 404);
            }

            $vaccine->update($request->only([
                'name',
                'producer_name'
            ]));

            $this->vaccineCacheService->clearCache();

            return response()->json($vaccine, 201);
        }
        catch(InvalidArgumentException $e)
        {
            return response()->json(['error' => $e->getMessage()], 422);
        }
        catch(Exception $e)
        {
            return response()->json(['error' => "Error while updating vaccine:" . $e->getMessage()], 500);
        }
    }

    public function destroy($id): JsonResponse
    {
        try
        {
            $vaccine = Vaccines::find($id);

            if(!$vaccine) {
                return response()->json(['error' => 'Vaccine not found.'], 404);
            }

            $vaccine->delete();

            $this->vaccineCacheService->clearCache();

            return response()->json(null, 204);
        }
        catch(InvalidArgumentException $e)
        {
            return response()->json(['error' => $e->getMessage()], 422);
        }
        catch(Exception $e)
        {
            return response()->json(['error' => "Error while deleting vaccine: " . $e->getMessage()], 500);
        }
    }
}
