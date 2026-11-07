<?php

namespace Modulos\RH\Http\Requests;

use Modulos\Core\Http\Request\BaseRequest;

class DispositivoUsuarioRequest extends BaseRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'dis_id' => 'required|integer|exists:reh_dispositivos_acesso,dis_id',
            'col_id' => 'required|integer|exists:reh_colaboradores,col_id',
            'foto' => 'nullable|image|max:10240|mimes:jpg,jpeg,png',
            'cadastrar_em_todos_dispositivos' => 'nullable|boolean',
            'aplicar_todos' => 'nullable|boolean',
        ];
    }
}
