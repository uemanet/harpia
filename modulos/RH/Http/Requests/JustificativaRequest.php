<?php

namespace Modulos\RH\Http\Requests;

use Modulos\Core\Http\Request\BaseRequest;

class JustificativaRequest extends BaseRequest
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
            'jus_horas' => 'required',
            'jus_data' => 'required|date_format:d/m/Y',
            'jus_data_fim' => 'required|date_format:d/m/Y',
            'jus_descricao' => 'required|min:2|max:512',
            'jus_file' => 'nullable|mimes:pdf,jpg,bpm,png,jpeg',
        ];


        return $rules;
    }
}
