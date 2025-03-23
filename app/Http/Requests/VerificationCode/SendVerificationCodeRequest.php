<?php

namespace App\Http\Requests\VerificationCode;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Validation\Rule;

class SendVerificationCodeRequest extends FormRequest
{
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
        $isRegistration = filter_var($this->input('registration'), FILTER_VALIDATE_BOOLEAN);

        return [
            'email' => ['required', 'email:dns',
                Rule::when(
                    $isRegistration,
                    ['unique:users,email'],
                    ['exists:users,email']
                ), ],
            'registration' => ['required', 'in:1,0,true,false'],
        ];

    }

    public function messages(): array
    {
        return [
            'email.exists' => 'there no account with this email',
            'email.unique' => 'there is already an account with this email',
        ];
    }

    public function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(
            response()->json([
                'errors' => $validator->errors(),
            ], 422)
        );
    }
}
