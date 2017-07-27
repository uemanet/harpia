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
        if ($this->getMethod() == 'PUT') {
            return [
                'ofd_tipo_avaliacao' => 'required',
                'ofd_qtd_vagas' => 'required|numeric|min:1',
                'ofd_prf_id' => 'required'
            ];
        }

        return [
            'ofd_mdc_id' => 'required',
            'ofd_trm_id' => 'required',
            'ofd_per_id' => 'required',
            'ofd_prf_id' => 'required',
            'ofd_qtd_vagas' => 'required'
        ];

    }

    public function messages()
    {
        return [
            'ofd_qtd_vagas.required' => 'Você deve inserir a quantidade de vagas',
            'ofd_qtd_vagas.min' => 'A quantidade de vagas deve ser maior que 0'
        ];
    }
}
