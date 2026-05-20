<?php

namespace Modulos\RH\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Modulos\Core\Http\Controller\BaseController;
use Modulos\RH\Http\Requests\DispositivoAcessoRequest;
use Modulos\RH\Repositories\DispositivoAcessoRepository;
use Modulos\RH\Services\ControlIdApiClient;
use Modulos\RH\Services\SincronizacaoUsuariosDispositivoService;

class DispositivoAcessoController extends BaseController
{
    public function __construct(
        private DispositivoAcessoRepository $dispositivoRepository,
        private ControlIdApiClient $apiClient,
        private SincronizacaoUsuariosDispositivoService $sincronizacaoService
    ) {
    }

    public function getIndex(Request $request)
    {
        $dispositivos = $this->dispositivoRepository->paginateRequest($request->all());

        return view('RH::dispositivos_acesso.index', compact('dispositivos'));
    }

    public function getCreate()
    {
        return view('RH::dispositivos_acesso.create');
    }

    public function postCreate(DispositivoAcessoRequest $request)
    {
        try {
            $dados = $request->only($this->dispositivoRepository->getFillableModelFields());
            $dados['dis_token_api'] = Str::random(64);

            $this->dispositivoRepository->create($dados);

            flash()->success('Dispositivo de acesso criado com sucesso.');
            return redirect()->route('rh.dispositivosacesso.index');
        } catch (\Exception $e) {
            if (config('app.debug')) {
                throw $e;
            }

            flash()->error('Erro ao tentar salvar o dispositivo.');
            return redirect()->back()->withInput($request->all());
        }
    }

    public function getEdit($dispositivoId)
    {
        $dispositivo = $this->dispositivoRepository->find($dispositivoId);

        if (!$dispositivo) {
            flash()->error('Dispositivo nao encontrado.');
            return redirect()->route('rh.dispositivosacesso.index');
        }

        return view('RH::dispositivos_acesso.edit', compact('dispositivo'));
    }

    public function putEdit($dispositivoId, DispositivoAcessoRequest $request)
    {
        try {
            $dispositivo = $this->dispositivoRepository->find($dispositivoId);

            if (!$dispositivo) {
                flash()->error('Dispositivo nao encontrado.');
                return redirect()->route('rh.dispositivosacesso.index');
            }

            $dados = $request->only($this->dispositivoRepository->getFillableModelFields());
            unset($dados['dis_token_api']);

            $this->dispositivoRepository->update($dados, $dispositivoId, 'dis_id');

            flash()->success('Dispositivo atualizado com sucesso.');
            return redirect()->route('rh.dispositivosacesso.index');
        } catch (\Exception $e) {
            if (config('app.debug')) {
                throw $e;
            }

            flash()->error('Erro ao tentar atualizar o dispositivo.');
            return redirect()->back()->withInput($request->all());
        }
    }

    public function postDelete(Request $request)
    {
        try {
            $this->dispositivoRepository->delete($request->get('id'));
            flash()->success('Dispositivo excluido com sucesso.');
        } catch (\Illuminate\Database\QueryException $e) {
            flash()->error('Nao foi possivel excluir o dispositivo porque ha dependencias vinculadas.');
        } catch (\Exception $e) {
            if (config('app.debug')) {
                throw $e;
            }

            flash()->error('Erro ao tentar excluir o dispositivo.');
        }

        return redirect()->back();
    }

    public function postRegenerarToken($dispositivoId)
    {
        $dispositivo = $this->dispositivoRepository->find($dispositivoId);

        if (!$dispositivo) {
            flash()->error('Dispositivo nao encontrado.');
            return redirect()->back();
        }

        $dispositivo->fill([
            'dis_token_api' => Str::random(64),
        ])->save();

        flash()->success('Token do dispositivo regenerado com sucesso.');
        return redirect()->route('rh.dispositivosacesso.edit', ['id' => $dispositivoId]);
    }

    public function postPing($dispositivoId)
    {
        $dispositivo = $this->dispositivoRepository->find($dispositivoId);

        if (!$dispositivo) {
            flash()->error('Dispositivo nao encontrado.');
            return redirect()->back();
        }

        try {
            $this->apiClient->ping($dispositivo);
            flash()->success('Dispositivo respondeu ao ping com sucesso.');
        } catch (\Exception $e) {
            if (config('app.debug')) {
                throw $e;
            }

            flash()->error('Falha ao comunicar com o dispositivo informado.');
        }

        return redirect()->route('rh.dispositivosacesso.index');
    }

    public function postSincronizarMapeamento($dispositivoId)
    {
        $dispositivo = $this->dispositivoRepository->find($dispositivoId);

        if (!$dispositivo) {
            flash()->error('Dispositivo nao encontrado.');
            return redirect()->back();
        }

        try {
            $resultado = $this->sincronizacaoService->sincronizar($dispositivo);
            flash()->success(sprintf(
                'Mapeamento sincronizado: %d usuarios, %d vinculados, %d pendentes.',
                $resultado['resumo']['total'],
                $resultado['resumo']['vinculados'],
                $resultado['resumo']['pendentes']
            ));
        } catch (\Exception $e) {
            if (config('app.debug')) {
                throw $e;
            }

            flash()->error('Falha ao sincronizar o mapeamento do dispositivo.');
        }

        return redirect()->route('rh.dispositivosacesso.index');
    }
}
