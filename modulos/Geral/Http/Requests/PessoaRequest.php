<?php

namespace Modulos\Geral\Http\Requests;

use Modulos\Core\Http\Request\BaseRequest;
use Modulos\Geral\Models\Pessoa;

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
        if ($this->method() == 'POST') {
            return [
                'pes_nome' => 'required|min:3|max:150',
                'pes_sexo' => 'required',
                'pes_email' => 'required|email|unique:gra_pessoas',
                'pes_telefone' => 'required|max:20',
                'pes_nascimento' => 'required|date_format:d/m/Y',
                'pes_mae' => 'required|max:150',
                'pes_pai' => 'max:150',
                'pes_estado_civil' => 'required',
                'pes_naturalidade' => 'required|max:45',
                'pes_nacionalidade' => 'required|max:45',
                'pes_raca' => 'max:45',
                'pes_necessidade_especial' => 'max:45',
                'pes_estrangeiro' => 'boolean',
                'doc_conteudo' => 'required|cpf|unique:gra_documentos'
            ];
        }

        return [
            'pes_nome' => 'required|min:3|max:150',
            'pes_sexo' => 'required',
            'pes_email' => 'required|email',
            'pes_telefone' => 'required|max:20',
            'pes_nascimento' => 'required|date_format:d/m/Y',
            'pes_mae' => 'required|max:150',
            'pes_pai' => 'max:150',
            'pes_estado_civil' => 'required',
            'pes_naturalidade' => 'max:45',
            'pes_nacionalidade' => 'max:45',
            'pes_raca' => 'max:45',
            'pes_necessidade_especial' => 'max:45',
            'pes_estrangeiro' => 'boolean',
            'doc_conteudo' => 'required|cpf'
        ];
    }

    public function messages()
    {
        return [
            'doc_conteudo.cpf' => 'CPF Inválido'
        ];
    }
}
