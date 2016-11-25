<?php

namespace Modulos\Academico\Http\Requests;

use Modulos\Core\Http\Request\BaseRequest;

class ModuloDisciplinaRequest extends BaseRequest
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
            'mdc_mdo_id' => 'required',
            'mdc_dis_id' => 'required'
            'mdc_tipo_avaliacao'= 'min:3'
        ];


        return $rules;
    }
}
