<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Password;

class RegisterRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'firstname' => 'bail|required|string|min:2|max:120',
            'lastname' => 'bail|required|string|min:2|max:120',
            'email' => 'bail|required|email|max:190|unique:users',
            'password' => ['required', Password::defaults()],
            'confirm_password' => 'required|same:password',
        ];
    }

    public function attributes(): array
    {
        return [
            'firstname' => 'nom',
            'lastname' => 'prénom',
            'email' => 'adresse e-mail',
            'password' => 'mot de passe',
            'confirm_password' => 'confirmation du mot de passe',
        ];
    }

    public function messages(): array
    {
        return [
            'required' => 'Le champ :attribute est obligatoire.',
            'min.string' => 'Le champ :attribute doit contenir au moins :min caractères.',
            'max.string' => 'Le champ :attribute ne doit pas dépasser :max caractères.',
            'email' => 'L\'adresse e-mail saisie n\'est pas valide.',
            'email.unique' => 'Cette adresse e-mail est déjà utilisée. Connectez-vous ou utilisez une autre adresse.',
            'password.required' => 'Choisissez un mot de passe.',
            'confirm_password.required' => 'Confirmez votre mot de passe.',
            'confirm_password.same' => 'La confirmation ne correspond pas au mot de passe.',
        ];
    }
}
