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
            'col_id' => 'sometimes|nullable|integer|exists:reh_colaboradores,col_id',
            'nome' => 'nullable|string|max:255',
            'registration' => 'nullable|string|max:50',
            'foto' => 'nullable|image|max:10240',
            'dispositivos_destino' => 'nullable|array',
            'dispositivos_destino.*' => 'integer|exists:reh_dispositivos_acesso,dis_id',
            'cadastrar_em_todos_dispositivos' => 'nullable|boolean',
            'sincronizar_todos_dispositivos' => 'nullable|boolean',
        ];
    }
}
