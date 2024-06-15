<?php

namespace App\Http\Requests\Message;

use App\DTO\Message\CreateChatDTO;
use App\Rules\EntityIdRule;
use App\Traits\Dtoable;
use Illuminate\Foundation\Http\FormRequest;

class CreateChatRequest extends FormRequest
{
    use Dtoable;

    protected string $dtoClass = CreateChatDTO::class;

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
            'name' => 'required|string|max:50',
            'avatarUrl' => 'nullable|string',
            'chatId' => [new EntityIdRule()],
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'Name is required.',
            'name.string' => 'Name must be a string.',
            'name.max' => 'Name cannot exceed 255 characters.',

            'avatarUrl.string' => 'Avatar URL must be a string.',
        ];
    }
}
