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
            'cld_nome' => 'required|max:80',
            'cld_data' => 'required|date',
            'cld_observacao' => 'nullable|max:255',
            'cld_tipo_evento' => 'required|in:FN,FE,FM,PF',
        ];

        return $rules;
    }
}
