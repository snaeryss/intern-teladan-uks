<?php

namespace App\Http\Requests\Student;

use App\Enums\Student\Gender;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class UpdateStudentRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return Auth::user()->hasRole('SuperVisor');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'date_birth' => ['required', 'date:Y-m-d'],
            'sex' => ['required', Rule::enum(Gender::class)],
        ];
    }

    public function attributes(): array
    {
        return [
            'name' => 'Nama',
            'date_birth' => 'Tanggal Lahir',
            'sex' => 'Jenis Kelamin',
        ];
    }
}
