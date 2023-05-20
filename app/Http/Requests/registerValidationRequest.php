<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class registerValidationRequest extends FormRequest
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
     * @return array<string, \Illuminate\Contracts\Validation\Rule|array|string>
     */
    public function rules(): array
    {
        return [
            'name_rp' => 'required|string|max:255',
            'discordusers' => 'required|string|regex:/^([a-zA-Z0-9]+)#([0-9]{4})$/', // https://regex101.com/r/4Q5Z2T/1
            'email' => 'required|string|email|max:255|unique:users',
        ];
    }
}
