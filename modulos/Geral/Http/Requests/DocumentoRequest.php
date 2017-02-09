<?php

namespace Modulos\Geral\Http\Requests;

use Modulos\Core\Http\Request\BaseRequest;

class DocumentoRequest extends BaseRequest
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
        return [
            'doc_pes_id' => 'required',
            'doc_tpd_id' => 'required',
            'doc_file' => 'mimes:pdf,jpg,bpm,png,jpeg',
            'doc_conteudo' => 'required:max:255',
            'doc_data_expedicao' => 'date_format:d/m/Y',
            'doc_orgao' => 'max:255',
            'doc_observacao' => 'max:255'
        ];
    }
}
