<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreEmployeeVaccineRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'vaccine_id' => 'required|exists:vaccines,id',
            'batch' => 'nullable|string',
            'validate_date' => 'nullable|date',
            'first_dose_vaccine' => 'nullable|date',
            'second_dose_vaccine' => 'nullable|date',
            'third_dose_vaccine' => 'nullable|date',
        ];
    }
}
