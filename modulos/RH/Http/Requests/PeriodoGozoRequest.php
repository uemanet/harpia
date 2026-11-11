<?php

namespace Modulos\RH\Http\Requests;

use Illuminate\Validation\Rule;
use Modulos\Core\Http\Request\BaseRequest;

class PeriodoGozoRequest extends BaseRequest
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
            'pgz_paq_id' => 'required',
            'pgz_data_inicio' => 'required|date_format:d/m/Y',
            'pgz_data_fim' => 'required|date_format:d/m/Y',
            'pgz_observacao' => 'required|min:3|max:255',
        ];
    }
}
