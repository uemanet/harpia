<?php

namespace Modulos\RH\Repositories;

use Modulos\Core\Repository\BaseRepository;
use Modulos\RH\Models\ConfiguracaoPonto;

class ConfiguracaoPontoRepository extends BaseRepository
{
    public function __construct(ConfiguracaoPonto $configuracaoPonto)
    {
        $this->model = $configuracaoPonto;
    }

    public function buscarPorChave(string $chave): ?ConfiguracaoPonto
    {
        return $this->model->where('cop_chave', $chave)->first();
    }

    public function getValor(string $chave, $default = null): ?string
    {
        $config = $this->buscarPorChave($chave);
        return $config ? $config->cop_valor : $default;
    }

    public function setValor(string $chave, string $valor, ?string $descricao = null): ConfiguracaoPonto
    {
        return $this->model->updateOrCreate(
            ['cop_chave' => $chave],
            ['cop_valor' => $valor, 'cop_descricao' => $descricao]
        );
    }
}
