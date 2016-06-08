<?php

namespace Modulos\Seguranca\Requests;

use Modulos\Core\Request\BaseRequest;

class StorePerfilRequest extends BaseRequest
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
