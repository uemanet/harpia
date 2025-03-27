<?php

namespace Modulos\RH\Http\Requests;

use Illuminate\Validation\Rule;
use Modulos\Core\Http\Request\BaseRequest;

class PeriodoAquisitivoRequest extends BaseRequest
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
            'paq_mtc_id' => 'required',
            'paq_data_inicio' => 'required|date_format:d/m/Y',
            'paq_data_fim' => 'required|date_format:d/m/Y',
            'paq_observacao' => 'required|min:3|max:255',
            'paq_periodo_aquisitivo' => 'required',
        ];
    }
}
