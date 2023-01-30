<?php

namespace Modulos\Academico\Http\Requests;
use Modulos\Core\Http\Request\BaseRequest;

class InstituicaoRequest extends BaseRequest
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
            'itt_nome' => 'required|min:3|max:100',
            'itt_sigla' => 'required|min:3|max:10'
        ];

        return $rules;
    }
}
