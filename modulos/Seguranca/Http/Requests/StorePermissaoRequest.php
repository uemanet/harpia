<?php

namespace Modulos\Seguranca\Requests;

use Modulos\Core\Request\BaseRequest;

class StorePermissaoRequest extends BaseRequest
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
            'prm_rcs_id' => 'required',
            'prm_nome' => 'required|max:45',
            'prm_descricao' => 'max:300',
        ];

        return $rules;
    }
}
