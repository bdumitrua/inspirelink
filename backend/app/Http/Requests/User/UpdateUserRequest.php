<?php

namespace App\Http\Requests\User;

use App\DTO\User\UpdateUserDTO;
use Illuminate\Foundation\Http\FormRequest;
use App\Rules\EmailRule;
use App\Traits\Dtoable;

class UpdateUserRequest extends FormRequest
{
    use Dtoable;

    protected string $dtoClass = UpdateUserDTO::class;

    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            // Main
            'firstName' => 'required|string|max:255',
            'lastName' => 'required|string|max:255',
            'email' => [new EmailRule()],
            'birthdate' => 'required|date|date_format:Y-m-d',

            // Additional
            'phone' => 'string|min:5',
            'nickName' => 'string|min:3|max:30',
            'about' => 'string|max:255',
            'address' => 'string|max:255',
            'avatar' => 'string',
            'gender' => 'string|in:man,woman'
        ];
    }

    public function messages()
    {
        return [
            'firstName.required' => 'Имя является обязательным полем.',
            'firstName.string'   => 'Имя должно быть строкой.',
            'firstName.max'      => 'Имя может быть не длиннее 255 символов.',

            'lastName.required' => 'Имя является обязательным полем.',
            'lastName.string'   => 'Имя должно быть строкой.',
            'lastName.max'      => 'Имя может быть не длиннее 255 символов.',

            'birthdate.required' => 'Дата обязательна к заполнению.',
            'birthdate.date' => 'Некорректный формат даты.',
            'birthdate.date_format' => 'Формат даты должен быть YYYY-MM-DD.',

            'phone.required' => 'Номер телефона является обязательным полем.',
            'phone.string' => 'Номер телефона должен быть строкой.',
            'phone.min' => 'Номер телефона должен содержать не менее 5 символов.',

            'nickName.string' => 'Никнейм должен быть строкой.',
            'nickName.min' => 'Никнейм должен содержать не менее 3 символов.',
            'nickName.max' => 'Никнейм может содержать не более 30 символов.',

            'about.string' => 'О себе должно быть строкой.',
            'about.max' => 'О себе может содержать не более 255 символов.',

            'address.string' => 'Адрес должен быть строкой.',
            'address.max' => 'Адрес может содержать не более 255 символов.',

            'avatar.string' => 'Ссылка на фото профиля должна быть строкой.',

            'gender.string' => 'Пол должен быть строкой',
            'gender.in' => 'Пол может принимать только следующие значения: man, woman',
        ];
    }
}
