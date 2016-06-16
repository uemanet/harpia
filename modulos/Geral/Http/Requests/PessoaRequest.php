<?php

namespace Modulos\Seguranca\Http\Requests;

use Modulos\Core\Http\Request\BaseRequest;

class PessoaRequest extends BaseRequest
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
            'pes_nome' => 'required|min:3|max:150',
            'pes_sexo' => 'required',
            'pes_email' => 'required|email|unique:pessoa',
            'pes_telefone' => 'required|max:20',
            'pes_nascimento' => 'required|date',
            'pes_mae' => 'required|max:150',
            'pes_pai' => 'max:150',
            'pes_estado_civil' => 'required|max:20',
            'pes_naturalidade' => 'max:45',
            'pes_nacionalidade' => 'max:45',
            'pes_raca' => 'max:45',
            'pes_necessidade_especial' => 'max:45',
            'pes_estrangeiro' => 'boolean',
        ];

        return $rules;
    }
}
