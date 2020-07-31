<?php

namespace Modulos\RH\Http\Requests;

use Modulos\Core\Http\Request\BaseRequest;

class BancoRequest extends BaseRequest
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
            'ban_nome' => 'required|min:3|max:80',
            'ban_codigo' => 'required|min:3|max:10',
            'ban_sigla' => 'required|min:3|max:25',
        ];

        return $rules;
    }
}
