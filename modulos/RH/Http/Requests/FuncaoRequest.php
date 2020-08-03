<?php


namespace Modulos\RH\Http\Requests;


use Modulos\Core\Http\Request\BaseRequest;

class FuncaoRequest extends BaseRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        $rules = [
            'fun_descricao' => 'required|min:3|max:60'
        ];
        return $rules;
    }
}