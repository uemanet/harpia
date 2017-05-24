<?php

namespace Modulos\Academico\Http\Requests;

use Modulos\Core\Http\Request\BaseRequest;

class CursoRequest extends BaseRequest
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
            'crs_dep_id' => 'required',
            'crs_nvc_id' => 'required',
            'crs_prf_diretor' => 'required',
            'crs_nome' => 'required|min:3|max:45',
            'crs_sigla'=>'required|max:10',
            'crs_descricao' => 'max:255',
            'crs_resolucao' => 'max:255',
            'crs_autorizacao' => 'max:255',
            'crs_data_autorizacao' => 'required|date_format:d/m/Y',
            'crs_eixo' => 'max:150',
            'crs_habilitacao' => 'max:150',
            'media_min_aprovacao' => 'required',
            'media_min_final' => 'required',
            'media_min_aprovacao_final' => 'required',
            'modo_recuperacao' => 'required',
            'conceitos_aprovacao' => 'required'
        ];

        return $rules;
    }
}
