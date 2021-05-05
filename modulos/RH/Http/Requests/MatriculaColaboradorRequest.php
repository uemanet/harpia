<?php

namespace Modulos\RH\Http\Requests;

use Illuminate\Validation\Rule;
use Modulos\Core\Http\Request\BaseRequest;

class MatriculaColaboradorRequest extends BaseRequest
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
            'mtc_data_fim' => 'required|date_format:d/m/Y',
        ];
    }
}
