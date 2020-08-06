<?php

namespace Modulos\RH\Http\Requests;

use Illuminate\Validation\Rule;
use Modulos\Core\Http\Request\BaseRequest;

class ContaColaboradorRequest extends BaseRequest
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
            'ccb_ban_id' => 'required|integer',
            'ccb_agencia' => 'required|integer',
            'ccb_conta' => 'required|integer',
            'ccb_variacao' => 'required|integer'
        ];

    }
}
