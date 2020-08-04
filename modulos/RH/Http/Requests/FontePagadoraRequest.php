<?php


namespace Modulos\RH\Http\Requests;


use Modulos\Core\Http\Request\BaseRequest;

class FontePagadoraRequest extends BaseRequest
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
          'fpg_razao_social' => 'required|min:3|max:150',
          'fpg_nome_fantasia' => 'required|min:3|max:150',
        ];
        return $rules;
    }
}