<?php

namespace Modulos\Academico\Http\Requests;

use Modulos\Core\Http\Request\BaseRequest;

class OfertaCursoRequest extends BaseRequest
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
            'ofc_crs_id' => 'required',
            'ofc_mtc_id' => 'required',
            'ofc_mdl_id' => 'required',
            'ofc_ano' => 'integer|min:1|max:9999'
        ];

        return $rules;
    }
}
