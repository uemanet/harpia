<?php

namespace Modulos\Academico\Http\Requests;

use Modulos\Core\Http\Request\BaseRequest;

class ModuloMatrizRequest extends BaseRequest
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
            'mdo_mtc_id' => 'required',
            'mdo_nome' => 'required|min:3|max:45|',
            'mdo_descricao' => 'required|min:3|max:255',
            'mdo_qualificacao' => 'required|min:3|max:255'
        ];

        return $rules;
    }
}
