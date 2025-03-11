<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ShowHabitRequest extends FormRequest
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
    protected $user_year,$user_month;
    public function rules(): array
    {
        return [
            'month' => ['nullable', 'integer', "min:1", "max:$this->user_month"],
            'year' => ['nullable', 'integer',"max:$this->user_year"],
        ];
    }
    protected function prepareForValidation(): void
    {
        $user=\Auth::user();
        $this->user_year=now($user->timezone)->year;
        $this->user_month=now($user->timezone)->month;
    }
}
