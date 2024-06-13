<?php

namespace App\Http\Requests\Team;

use Illuminate\Foundation\Http\FormRequest;

class UpdateTeamApplicationRequest extends FormRequest
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
            'status' => 'required|string|max:20',
        ];
    }

    public function messages()
    {
        return [
            'status.max' => 'Status must be 20 characters or fewer.',
        ];
    }
}