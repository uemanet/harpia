<?php

namespace Modulos\Seguranca\Http\Requests;

use Modulos\Core\Http\Request\BaseRequest;

class MenuItemRequest extends BaseRequest
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
            'mit_mod_id' => 'required',
            'mit_nome' => 'required|min:3',
            'mit_icone' => 'required|min:3',
            'mit_rota' => 'min:3',
            'mit_descricao' => 'min:3'
        ];
    }
}
