<?php

namespace Modulos\Seguranca\Http\Requests;

use Modulos\Core\Http\Request\BaseRequest;

class PerfilRequest extends BaseRequest
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
        switch ($this->method()) {
            case 'POST':
            {
                return [
                    'prf_mod_id' => 'required',
                    'prf_nome' => 'required|min:3|max:150',
                    'prf_descricao' => 'max:300',
                ];

            }
            case 'PATCH':
            case 'PUT':
            {
                return [
                    'prf_nome' => 'required|min:3|max:150',
                    'prf_descricao' => 'max:300',
                ];
            }
            default: return [];
        }
    }
}
