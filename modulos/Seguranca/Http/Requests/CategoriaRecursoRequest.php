<?php

namespace Modulos\Seguranca\Http\Requests;

use Modulos\Core\Http\Request\BaseRequest;

class CategoriaRecursoRequest extends BaseRequest
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
            'ctr_mod_id' => 'required',
            'ctr_nome' => 'required|min:3|max:150',
            'ctr_descricao' => 'max:300',
            'ctr_icone' => 'required|min:3',
            'ctr_ordem' => 'required',
            'ctr_ativo' => 'required',
            'ctr_visivel' => 'required'
        ];

        return $rules;
    }
}
