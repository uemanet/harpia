<?php

namespace Modulos\Seguranca\Requests;

use Modulos\Core\Request\BaseRequest;

class StoreUsuarioRequest extends BaseRequest
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
        switch($this->method()){
            case 'POST':
                $rules = [
                    'usr_nome' => 'required|max:150|min:5',
                    'usr_email' => 'email|required',
                    'usr_telefone' => 'max:15',
                    'usr_usuario' => 'required|max:45|min:5|unique:seg_usuarios',
                    'usr_senha' => 'required|min:6',
                    'usr_ativo' => 'required|integer',
                ];
                break;
            case 'PUT':
                 $rules = [
                    'usr_nome' => 'max:150|min:5',
                    'usr_email' => 'email|required',
                    'usr_telefone' => 'max:15',
                    'usr_ativo' => 'integer',
                    'usr_senha' => 'min:6',
                ];
                break;
            default:
                $rules = [];
        }
        return $rules;
    }
}
