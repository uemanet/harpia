<?php

namespace Modulos\RH\Http\Requests;

use Modulos\Core\Http\Request\BaseRequest;

class VincularMapeamentoDispositivoRequest extends BaseRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'dis_id' => 'required|integer|exists:reh_dispositivos_acesso,dis_id',
            'user_id' => 'required|string|max:20',
            'col_id' => 'required|integer|exists:reh_colaboradores,col_id',
        ];
    }
}
