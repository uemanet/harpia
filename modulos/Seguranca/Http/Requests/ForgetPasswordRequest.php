<?php

namespace Modulos\Seguranca\Http\Requests;

use Modulos\Core\Http\Request\BaseRequest;

class ForgetPasswordRequest extends BaseRequest
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
            'email' => 'required|email|exists:Modulos\Geral\Models\Pessoa,pes_email'
        ];

        return $rules;
    }

    public function messages()
    {
        return [
            'email.exists' => 'Este email não está cadastrado na base de dados',
        ];
    }
}
