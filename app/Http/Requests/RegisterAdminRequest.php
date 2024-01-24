<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RegisterAdminRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'firstname' => 'bail|required|string|min:2',
            'lastname' => 'bail|required|string|min:2',
            'email' => 'bail|required|email|unique:users',
        ];
    }
    /**
     * Get the error messages for the defined validation rules.
     *
     * @return array
     */
    public function messages()
    {
        return [
            'required' => 'Veuillez remplir obligatoirement ce champ',
            'min' => 'Veuillez renseigner au plus 2 caractères',
            'email' => 'Ce champ ne correspond pas à un email valide',
            'email.unique' => 'Cet email est déjà utilisé',
        ];
    }
}
