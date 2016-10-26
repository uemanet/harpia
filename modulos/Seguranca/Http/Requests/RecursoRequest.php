<?php

namespace Modulos\Seguranca\Http\Requests;

use Modulos\Core\Http\Request\BaseRequest;

class RecursoRequest extends BaseRequest
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
            'mod_id' => 'required',
            'rcs_ctr_id' => 'required',
            'rcs_nome' => 'required|min:3|max:150',
            'rcs_rota' => 'required|min:3',
            'rcs_descricao' => 'max:300',
            'rcs_icone' => 'required|min:3',
            'rcs_ativo' => 'required',
            'rcs_ordem' => 'required',
        ];

        return $rules;
    }
}
