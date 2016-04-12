<?php

namespace Modulos\Seguranca\Requests;

use App\Http\Requests\Request;

class StoreCategoriaRecursoRequest extends Request
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
            'ctr_nome' => 'required|max:45',
            'ctr_icone' => 'required|max:45',
            'ctr_ordem' => 'required',
            'ctr_ativo' => 'required'
        ];

        return $rules;
    }
}
