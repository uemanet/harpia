<?php

namespace Modulos\RH\Http\Requests;

use Modulos\Core\Http\Request\BaseRequest;

class DispositivoAcessoRequest extends BaseRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        $dispositivoId = $this->route('id');

        return [
            'dis_nome' => 'required|string|min:3|max:255',
            'dis_identificador' => 'required|string|max:255|unique:reh_dispositivos_acesso,dis_identificador,' . $dispositivoId . ',dis_id',
            'dis_tipo' => 'required|in:entrada,saida',
            'dis_ip' => 'nullable|ip',
            'dis_modelo' => 'nullable|string|max:255',
            'dis_status' => 'required|in:ativo,inativo',
            'dis_observacao' => 'nullable|string',
        ];
    }

    public function messages()
    {
        return [
            'dis_ip.ip' => 'O campo IP deve conter um endereco IP valido.',
        ];
    }
}
