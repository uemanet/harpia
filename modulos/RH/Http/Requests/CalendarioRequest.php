<?php

namespace Modulos\RH\Http\Requests;

use Modulos\Core\Http\Request\BaseRequest;

class CalendarioRequest extends BaseRequest
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

            'cld_nome' => 'nullable',
            'cld_data' => 'nullable',
            'cld_observacao' => 'nullable',
            'cld_tipo_evento' => 'nullable',
        ];

        return $rules;
    }
}
