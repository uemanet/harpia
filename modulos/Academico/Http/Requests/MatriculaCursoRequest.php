<?php

namespace Modulos\Academico\Http\Requests;

use Modulos\Core\Http\Request\BaseRequest;

class MatriculaCursoRequest extends BaseRequest
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
            'crs_id' => 'required',
            'ofc_id' => 'required',
            'mat_trm_id' => 'required',
            'mat_pol_id' => 'required',
            'mat_modo_entrada' => 'required'
        ];

        return $rules;
    }
}
