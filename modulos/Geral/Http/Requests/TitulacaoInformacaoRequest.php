<?php

namespace Modulos\Geral\Http\Requests;

use App\Http\Middleware\VerifyCsrfToken;
use Modulos\Core\Http\Request\BaseRequest;

class TitulacaoInformacaoRequest extends BaseRequest
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
            'tin_tit_id' => 'required',
            'tin_titulo' => 'required|min:3|max:150',
            'tin_instituicao' => 'required|min:3|max:150',
            'tin_instituicao_sigla' => 'required|max:10',
            'tin_instituicao_sede' => 'required|min:3|max:45',
            'tin_anoinicio' => 'required|max:4|min:4',
            'tin_anofim' => 'max:4|min:4'
        ];

        return $rules;
    }
}
