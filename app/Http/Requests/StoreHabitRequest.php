<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class StoreHabitRequest extends FormRequest
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
            'name' => ['required', 'string', 'max:255'],
            'days' => ['required', 'array'],
            'days.*' => ['required', 'in:Sunday,Monday,Tuesday,Wednesday,Thursday,Friday,Saturday'],
            'reminder_time' => ['nullable', 'date-format:H:i'],
            'notifications_enabled' => ['boolean'],
        ];
    }


    public function withValidator($validator): void
    {
        $validator->after(function ($validator) {
            $user = auth()->user();
            if ($user && $user->habits()->count() >= config('app.data.max_habits')) {
                $validator->errors()->add(
                    'max_habits',
                    'Sorry, you can’t add more than ' . config('app.data.max_habits') . ' habits.'
                );
            }
        });
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
