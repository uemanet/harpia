<?php

namespace Modulos\Seguranca\Requests;

use Modulos\Core\Request\BaseRequest;

class StoreModuloRequest extends BaseRequest
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
            'mod_nome' => 'required|max:150',
            'mod_descricao' => 'max:300',
            'mod_icone' => 'max:45',
            'mod_ativo' => 'required'
        ];

        return $rules;
    }
}
