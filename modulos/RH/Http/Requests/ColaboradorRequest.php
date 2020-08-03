<?php

namespace Modulos\RH\Http\Requests;

use Illuminate\Validation\Rule;
use Modulos\Core\Http\Request\BaseRequest;

class ColaboradorRequest extends BaseRequest
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
    public function rules($idPessoa = null)
    {

        if ($this->method() == 'POST') {
            return [
                'pes_nome' => 'required|min:3|max:150',
                'pes_sexo' => 'required',
                'pes_email' => 'required|email|unique:gra_pessoas',
                'pes_telefone' => 'required|max:20',
                'pes_nascimento' => 'required|date_format:d/m/Y',
                'pes_mae' => 'required|max:150',
                'pes_pai' => 'nullable|max:150',
                'pes_estado_civil' => 'nullable|max:45',
                'pes_naturalidade' => 'required|max:45',
                'pes_nacionalidade' => 'required|max:45',
                'pes_raca' => 'nullable|max:45',
                'pes_necessidade_especial' => 'nullable|max:45',
                'pes_estrangeiro' => 'nullable|boolean',
                'doc_conteudo' => 'required|cpf|unique:gra_documentos',
                'pes_endereco' => 'required',
                'pes_numero' => 'required|max:45',
                'pes_cep' => 'required',
                'pes_bairro' => 'required|min:2',
                'pes_cidade' => 'required|min:2',
                'pes_estado' => 'required',

                //            'col_pes_id' => 'required',
                'col_fun_id' => 'required',
                'col_set_id' => 'required',
                'col_qtd_filho' => 'required',
                'col_data_admissao' => 'required',
                'col_ch_diaria' => 'required',
                'col_codigo_catraca' => 'required',
                'col_vinculo_universidade' => 'required',
                'col_matricula_universidade' => 'required',
                'col_observacao' => 'required',
                'col_status' => 'required',

            ];
        }

        return [
            'pes_nome' => 'required|min:3|max:150',
            'pes_sexo' => 'required',
            'pes_email' => 'required|email|'.Rule::unique('gra_pessoas')->ignore($idPessoa, 'pes_id'),
            'pes_telefone' => 'required|max:20',
            'pes_nascimento' => 'required|date_format:d/m/Y',
            'pes_mae' => 'required|max:150',
            'pes_pai' => 'max:150',
            'pes_estado_civil' => 'max:45',
            'pes_naturalidade' => 'required|max:45',
            'pes_nacionalidade' => 'required|max:45',
            'pes_raca' => 'max:45',
            'pes_necessidade_especial' => 'max:45',
            'pes_estrangeiro' => 'boolean',
            'doc_conteudo' => 'required|cpf',
            'pes_endereco' => 'required',
            'pes_numero' => 'required|max:45',
            'pes_cep' => 'required',
            'pes_bairro' => 'required|min:2',
            'pes_cidade' => 'required|min:2',
            'pes_estado' => 'required',

            //            'col_pes_id' => 'required',
            'col_fun_id' => 'required',
            'col_set_id' => 'required',
            'col_qtd_filho' => 'required',
            'col_data_admissao' => 'required',
            'col_ch_diaria' => 'required',
            'col_codigo_catraca' => 'required',
            'col_vinculo_universidade' => 'required',
            'col_matricula_universidade' => 'required',
            'col_observacao' => 'required',
            'col_status' => 'required',

        ];
    }

    public function messages()
    {
        return [
            'doc_conteudo.cpf' => 'CPF Inválido'
        ];
    }
}
