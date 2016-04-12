<?php

namespace Modulos\Seguranca\Requests;

use App\Http\Requests\Request;

class StoreRecursoRequest extends Request
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
            'rcs_mod_id' => 'required',
            'rcs_ctr_id' => 'required',
            'rcs_nome' => 'required|max:150',
            'rcs_descricao' => 'max:300',
            'rcs_icone' => 'max:45',
            'rcs_ativo' => 'required|integer',
            'rcs_ordem' => 'integer',
        ];

        return $rules;
    }
}
