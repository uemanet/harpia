<?php

namespace Modulos\Academico\Http\Requests;

use Modulos\Core\Http\Request\BaseRequest;

class TurmaRequest extends BaseRequest
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
            'trm_ofc_id' => 'required',
            'trm_per_id' => 'required',
            'trm_nome' => 'required|min:3|max:45',
            'trm_qtd_vagas'=>'integer|min:1|max:9999'
        ];

        return $rules;
    }
}
