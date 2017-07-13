<?php

namespace Modulos\Academico\Http\Requests;

use Modulos\Core\Http\Request\BaseRequest;

class LancamentoTccRequest extends BaseRequest
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

            'ltc_prf_id' => 'required',
            'ltc_mof_id' => 'required',
            'ltc_titulo' => 'required|max:255',
            'ltc_file' => 'mimes:pdf',
            'ltc_tipo' => 'required',
            'ltc_data_apresentacao'=>'required|date_format:"d/m/Y"',
            'ltc_observacao' => 'max:255'
        ];


        return $rules;
    }
}
