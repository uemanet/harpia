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
                'pes_raca' => 'required|max:45',
                'pes_necessidade_especial' => 'required|max:45',
                'pes_estrangeiro' => 'required|boolean',
                'doc_conteudo' => 'required|cpf|unique:gra_documentos',
                'pes_endereco' => 'required',
                'pes_numero' => 'required|max:45',
                'pes_cep' => 'required',
                'pes_bairro' => 'required|min:2',
                'pes_cidade' => 'required|min:2',
                'pes_estado' => 'required'
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
            'pes_naturalidade' => 'required|max:45',
            'pes_nacionalidade' => 'required|max:45',
            'pes_raca' => 'required|max:45',
            'pes_necessidade_especial' => 'required|max:45',
            'pes_estrangeiro' => 'required|boolean',
            'doc_conteudo' => 'required|cpf',
            'pes_endereco' => 'required',
            'pes_numero' => 'required|max:45',
            'pes_cep' => 'required',
            'pes_bairro' => 'required|min:2',
            'pes_cidade' => 'required|min:2',
            'pes_estado' => 'required'
        ];
    }

    public function messages()
    {
        return [
            'doc_conteudo.cpf' => 'CPF Inválido'
        ];
    }
}
