<?php

namespace Modulos\Geral\Http\Requests;

use Modulos\Core\Http\Request\BaseRequest;

class PoloRequest extends BaseRequest
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
            'pol_nome' => 'required|min:3|max:45'
        ];

        return $rules;
    }
}
