<?php

namespace Modulos\Integracao\Http\Requests;

use Modulos\Core\Http\Request\BaseRequest;

class AmbienteVirtualRequest extends BaseRequest
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
            'amb_nome' => 'required|min:3|max:45',
            'amb_versao'=>'required|max:20',
            'amb_url' => 'max:90'
        ];

        return $rules;
    }
}
