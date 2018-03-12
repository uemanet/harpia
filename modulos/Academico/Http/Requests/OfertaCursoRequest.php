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
        switch ($this->method()) {
            case 'POST':
                {
                    return [
                        'ofc_crs_id' => 'required',
                        'ofc_mtc_id' => 'required',
                        'ofc_mdl_id' => 'required',
                        'ofc_ano' => 'integer|required|min:1|max:9999',
                        'polos' => 'required'
                    ];

                }
            case 'PUT':
                {
                    return [
                        'polos' => 'required'
                    ];
                }
            default: return [];
        }
    }

    public function messages()
    {
        return [
            'polos.required' => 'Você deve escolher os polos da oferta'
        ];
    }
}
