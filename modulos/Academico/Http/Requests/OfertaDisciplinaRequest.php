<?php

namespace Modulos\Academico\Http\Requests;

use Modulos\Core\Http\Request\BaseRequest;

class OfertaDisciplinaRequest extends BaseRequest
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
            'ofd_mdc_id' => 'required',
            'ofd_trm_id' => 'required',
            'ofd_per_id' => 'required',
            'ofd_prf_id' => 'required',
            'ofd_qtd_vagas' => 'required'
        ];

        return $rules;
    }

    public function messages()
    {
        return [
            'ofd_qtd_vagas.required' => 'Você deve inserir a quantidade de vagas'
        ];
    }
}
