<?php

namespace Modulos\RH\Http\Requests;

use Illuminate\Validation\Rule;
use Modulos\Core\Http\Request\BaseRequest;

class SalarioColaboradorRequest extends BaseRequest
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
            'scb_ccb_id' => 'required|integer',
            'scb_vfp_id' => 'required|integer',
//            'scb_unidade' => 'nullable|integer',
            'scb_valor' => 'required|numeric',
//            'scb_valor_liquido' => 'required|integer',
            'scb_data_inicio' => 'required|date_format:d/m/Y',
            'scb_data_fim' => 'nullable|date_format:d/m/Y',
//            'scb_data_cadastro' => 'required|date_format:d/m/Y',
        ];

    }
}
