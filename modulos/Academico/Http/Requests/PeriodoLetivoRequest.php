<?php

namespace Modulos\Academico\Http\Requests;

use Modulos\Core\Http\Request\BaseRequest;

class PeriodoLetivoRequest extends BaseRequest
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
            'per_nome' => 'required',
            'per_inicio' => 'required|date_format:"d/m/Y"',
            'per_fim' => 'required|date_format:"d/m/Y"'
        ];

        return $rules;
    }
}
