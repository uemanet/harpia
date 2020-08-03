<?php

namespace Modulos\RH\Http\Requests;

use Modulos\Core\Http\Request\BaseRequest;

class PeriodoLaboralRequest extends BaseRequest
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
            'pel_inicio' => 'required|date_format:d/m/Y',
            'pel_termino' => 'required|date_format:d/m/Y'
        ];

        return $rules;
    }
}
