<?php


namespace Modulos\RH\Http\Requests;


use Modulos\Core\Http\Request\BaseRequest;

class SetorRequest extends BaseRequest
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
            'set_descricao' => 'required|min:3|max:60',
            'set_sigla' => 'required|min:2|max:15',
        ];

        return $rules;
    }
}