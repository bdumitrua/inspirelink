<?php

namespace App\Http\Requests;

use App\DTO\CreateSubscriptionDTO;
use App\Rules\EntityIdRule;
use App\Traits\CreateDTO;
use Illuminate\Foundation\Http\FormRequest;

class CreateSubscriptionRequest extends FormRequest
{
    use CreateDTO;

    protected string $dtoClass = CreateSubscriptionDTO::class;

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
            'entityType' => 'required|in:user,team',
            'entityId' => [new EntityIdRule()],
        ];
    }
}