<?php

namespace App\Http\Requests\Doctor;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class StoreDoctorRequest extends FormRequest
{
    public function authorize(): bool
    {
        return Auth::user()->hasRole('SuperVisor');
    }
    
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'signature' => ['nullable', 'image', 'mimes:jpeg,jpg,png', 'max:2048'],
            'is_active' => ['boolean'],
        ];
    }
    
    public function attributes(): array
    {
        return [
            'name' => 'Nama',
            'signature' => 'Tanda Tangan',
            'is_active' => 'Status',
        ];
    }
}