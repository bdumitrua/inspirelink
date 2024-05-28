<?php

namespace App\Http\Requests\Team;

use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;
use App\Rules\EntityIdRule;

class UpdateTeamMemberRequest extends FormRequest
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
            'isModerator' => 'required|boolean',
            'about' => 'nullable|string|max:80',
        ];
    }

    public function messages()
    {
        return [
            'isModerator.required' => 'IsModerator is required.',
            'about.max' => 'About cannot exceed 80 characters.',
        ];
    }
}
