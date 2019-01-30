<?php

namespace Modulos\Academico\Http\Requests;

use Modulos\Core\Http\Request\BaseRequest;

class DisciplinaRequest extends BaseRequest
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
            'dis_nome' => 'required|min:3|max:255',
            'dis_nvc_id' => 'required',
            'dis_creditos' => 'required',
            'dis_carga_horaria' => 'required'
        ];

        return $rules;
    }
}
