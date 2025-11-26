<?php

namespace App\Http\Requests\AcademicYear;

use App\Enums\AcademicYearStatusEnum;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class StoreAcademicYearRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return Auth::check();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'year_start' => ['required', 'numeric', 'digits:4'],
            'year_end' => ['required', 'numeric', 'digits:4', 'after:year_start'],
            'is_active' => ['required', 'boolean'],
        ];
    }

    public function attributes(): array
    {
        return [
            'year_start' => 'Tahun Mulai',
            'year_end' => 'Tahun Selesai',
            'is_active' => 'Status',
        ];
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'is_active' => AcademicYearStatusEnum::INACTIVE->value,
        ]);
    }
}
