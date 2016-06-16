<?php

namespace Modulos\Seguranca\Http\Requests;

use Modulos\Core\Http\Request\BaseRequest;

class ModuloRequest extends BaseRequest
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
            'mod_nome' => 'required|min:3|max:150',
            'mod_rota' => 'required|min:3',
            'mod_descricao' => 'max:300',
            'mod_icone' => 'required|max:45',
            'mod_class' => 'required',
            'mod_ativo' => 'required'
        ];

        return $rules;
    }
}
