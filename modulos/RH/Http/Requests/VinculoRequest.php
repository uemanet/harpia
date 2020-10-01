<?php


namespace Modulos\RH\Http\Requests;


use Modulos\Core\Http\Request\BaseRequest;

class VinculoRequest extends BaseRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        $rules = [
            'vin_descricao' => 'required|min:3|max:60'
        ];
        return $rules;
    }
}