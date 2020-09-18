<?php


namespace Modulos\RH\Http\Requests;


use Modulos\Core\Http\Request\BaseRequest;

class FontePagadoraRequest extends BaseRequest
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
            'fpg_razao_social' => 'required|min:3|max:150',
            'fpg_nome_fantasia' => 'required|min:3|max:150',
            'fpg_cnpj' => 'required|min:3|max:19',
            'fpg_cep' => 'nullable|min:3|max:10',
            'fpg_endereco' => 'nullable|min:3|max:150',
            'fpg_bairro' => 'nullable|min:3|max:150',
            'fpg_numero' => 'nullable|min:3|max:45',
            'fpg_complemento' => 'nullable|min:3|max:150',
            'fpg_cidade' => 'nullable|min:3|max:150',
            'fpg_uf' => 'nullable|min:2|max:2',
            'fpg_email' => 'nullable|min:3|max:150',
            'fpg_telefone' => 'nullable|min:3|max:15',
            'fpg_celular' => 'nullable|min:3|max:15',
            'fpg_observacao' => 'nullable|min:3',
        ];
        return $rules;
    }
}