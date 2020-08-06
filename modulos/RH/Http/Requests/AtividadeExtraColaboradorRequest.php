<?php

namespace Modulos\RH\Http\Requests;

use Illuminate\Validation\Rule;
use Modulos\Core\Http\Request\BaseRequest;

class AtividadeExtraColaboradorRequest extends BaseRequest
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

            'atc_titulo' => 'required|string',
            'atc_descricao' => 'string',
            'atc_tipo' => 'required|in:curso,evento,oficina',
            'atc_carga_horaria' => 'integer',
            'atc_data_inicio' => 'date_format:d/m/Y',
            'atc_data_fim' => 'date_format:d/m/Y',


        ];

    }
}
