<?php

namespace Modulos\Geral\Http\Requests;

use Modulos\Core\Http\Request\BaseRequest;
use Modulos\Geral\Models\Pessoa;
use Illuminate\Validation\Rule;

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
    public function rules($idPessoa = null, $importacao = null)
    {

        if ($importacao) {
            return [
                'pes_nome' => 'required|min:3|max:150',
                'pes_sexo' => 'required',
                'pes_email' => 'required|email',
                'pes_telefone' => 'required|max:20',
                'pes_nascimento' => 'nullable|date_format:d/m/Y',
                'pes_mae' => 'nullable|max:150',
                'pes_pai' => 'max:150',
                'pes_estado_civil' => 'max:45',
                'pes_naturalidade' => 'nullable|max:45',
                'pes_nacionalidade' => 'nullable|max:45',
                'pes_raca' => 'max:45',
                'pes_necessidade_especial' => 'max:45',
                'pes_estrangeiro' => 'boolean',
                'doc_conteudo' => 'required|cpf',
                'pes_endereco' => 'nullable',
                'pes_numero' => 'nullable|max:45',
                'pes_cep' => 'nullable',
                'pes_bairro' => 'nullable|min:2',
                'pes_cidade' => 'nullable|min:2',
                'pes_estado' => 'nullable'
            ];
        }

        if ($this->method() == 'POST') {
            return [
                'pes_nome' => 'required|min:3|max:150',
                'pes_sexo' => 'required',
                'pes_email' => 'required|email|unique:gra_pessoas',
                'pes_telefone' => 'required|max:20',
                'pes_nascimento' => 'nullable|date_format:d/m/Y',
                'pes_mae' => 'nullable|max:150',
                'pes_pai' => 'nullable|max:150',
                'pes_estado_civil' => 'nullable|max:45',
                'pes_naturalidade' => 'nullable|max:45',
                'pes_nacionalidade' => 'nullable|max:45',
                'pes_raca' => 'nullable|max:45',
                'pes_necessidade_especial' => 'nullable|max:45',
                'pes_estrangeiro' => 'nullable|boolean',
                'doc_conteudo' => 'required|cpf|unique:gra_documentos',
                'pes_endereco' => 'nullable',
                'pes_numero' => 'nullable|max:45',
                'pes_cep' => 'nullable',
                'pes_bairro' => 'nullable|min:2',
                'pes_cidade' => 'nullable|min:2',
                'pes_estado' => 'nullable'
            ];
        }

        if (!$idPessoa) {
            $pessoa = $this->all();
            if (array_key_exists('pes_id', $pessoa)) {
                $idPessoa = $pessoa['pes_id'];
            }
        }

        return [
            'pes_nome' => 'required|min:3|max:150',
            'pes_sexo' => 'required',
            'pes_email' => 'required|email|'.Rule::unique('gra_pessoas')->ignore($idPessoa, 'pes_id'),
            'pes_telefone' => 'required|max:20',
            'pes_nascimento' => 'nullable|date_format:d/m/Y',
            'pes_mae' => 'nullable|max:150',
            'pes_pai' => 'max:150',
            'pes_estado_civil' => 'max:45',
            'pes_naturalidade' => 'nullable|max:45',
            'pes_nacionalidade' => 'nullable|max:45',
            'pes_raca' => 'max:45',
            'pes_necessidade_especial' => 'max:45',
            'pes_estrangeiro' => 'boolean',
            'doc_conteudo' => 'required|cpf',
            'pes_endereco' => 'nullable',
            'pes_numero' => 'nullable|max:45',
            'pes_cep' => 'nullable',
            'pes_bairro' => 'nullable|min:2',
            'pes_cidade' => 'nullable|min:2',
            'pes_estado' => 'nullable'
        ];
    }

    public function messages()
    {
        return [
            'doc_conteudo.cpf' => 'CPF Inválido'
        ];
    }
}
