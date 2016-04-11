<?php

namespace App\Modulos\Seguranca\Requests;

use App\Http\Requests\Request;

class StorePerfilRequest extends Request
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
            'prf_nome' => 'required',
            'prf_descricao' => 'required',
        ];

        return $rules;
    }
}
