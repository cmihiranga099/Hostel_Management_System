<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ProfileUpdateRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'first_name' => ['nullable', 'string', 'max:255'],
            'last_name' => ['nullable', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users')->ignore($this->user()->id)],
            'phone' => ['nullable', 'string', 'max:20'],
            'date_of_birth' => ['nullable', 'date'],
            'gender' => ['nullable', 'string', 'in:male,female,other'],
            'address' => ['nullable', 'string'],
            'university' => ['nullable', 'string', 'max:255'],
            'student_id' => ['nullable', 'string', 'max:50'],
            'faculty' => ['nullable', 'string', 'max:255'],
            'degree_program' => ['nullable', 'string', 'max:255'],
            'academic_year' => ['nullable', 'string', 'max:10'],
            'expected_graduation' => ['nullable', 'string', 'max:7'],
            'emergency_contact_name' => ['nullable', 'string', 'max:255'],
            'emergency_contact_relationship' => ['nullable', 'string', 'max:100'],
            'emergency_contact_phone' => ['nullable', 'string', 'max:20'],
            'emergency_contact_email' => ['nullable', 'email', 'max:255'],
            'emergency_contact_address' => ['nullable', 'string'],
        ];
    }
}