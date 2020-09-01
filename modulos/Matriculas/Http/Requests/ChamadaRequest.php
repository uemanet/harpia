<?php

namespace Modulos\Matriculas\Http\Requests;

use Modulos\Core\Http\Request\BaseRequest;

class ChamadaRequest extends BaseRequest
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
            'nome' => 'required|min:5',
            'seletivo_id' => 'required',
            'tipo_chamada' => 'required',
            'inicio_matricula' => 'required',
            'fim_matricula' => 'required',
        ];

        return $rules;
    }
}
