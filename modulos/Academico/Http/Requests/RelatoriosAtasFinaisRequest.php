<?php

namespace Modulos\Academico\Http\Requests;

use Modulos\Core\Http\Request\BaseRequest;

class RelatoriosAtasFinaisRequest extends BaseRequest
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
        return [
            'crs_id' => 'required|integer',
            'ofc_id' => 'required|integer',
            'trm_id' => 'required|integer',
            'pol_id' => 'nullable|integer',
            'mat_situacao' => 'string|nullable'
        ];
    }
}
