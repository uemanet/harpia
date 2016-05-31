<?php

namespace Modulos\Seguranca\Http\Requests;

use App\Http\Requests\Request;

class LoginRequest extends Request
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
     * @return array
     */
    public function rules()
    {
        $rules = [
            'usr_usuario' => 'required|min:4',
            'usr_senha' => 'required|min:6'
        ];

        return $rules;
    }
}
