<?php

namespace Modulos\Matriculas\Http\Controllers;

use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Modulos\Matriculas\Models\SeletivoMatricula;
use Modulos\Matriculas\Models\SeletivoUser;
use Modulos\Matriculas\Models\User;
use Modulos\Matriculas\Repositories\InscricaoRepository;
use Modulos\Matriculas\Repositories\SeletivoRepository;
use Modulos\Seguranca\Providers\ActionButton\Facades\ActionButton;
use Illuminate\Http\Request;

/**
 * Class SeletivoController.
 */
class SeletivoController extends Controller
{
    protected $seletivoRepository;
    protected $inscricaoRepository;

    public function __construct(SeletivoRepository $seletivoRepository, InscricaoRepository $inscricaoRepository)
    {
        $this->seletivoRepository = $seletivoRepository;
        $this->inscricaoRepository = $inscricaoRepository;
    }

    /**
     * @return \Illuminate\View\View
     */
    public function getIndex(Request $request)
    {
        $actionButtons = [];

        $paginacao = null;
        $tabela = null;

        $requestData = $request->all();
        $requestData['field'] = 'id';
        $requestData['sort'] = 'desc';

        $tableData = $this->seletivoRepository->paginateRequest($requestData);

        if ($tableData->count()) {
            $tabela = $tableData->columns(array(
                'id' => '#',
                'nome' => 'Seletivo',
                'action' => 'Ações'
            ))
                ->modifyCell('action', function () {
                    return array('style' => 'width: 140px;');
                })
                ->means('action', 'id')
                ->modify('action', function ($id) {
                    return ActionButton::grid([
                        'type' => 'SELECT',
                        'config' => [
                            'classButton' => 'btn-default',
                            'label' => 'Selecione'
                        ],
                        'buttons' => [
                            [
                                'classButton' => '',
                                'icon' => 'fa fa-eye',
                                'route' => 'matriculas.index.inscricoes.index',
                                'parameters' => ['id' => $id],
                                'label' => 'Visualizar',
                                'method' => 'get'
                            ],
                        ]
                    ]);
                })
                ->sortable(array('id', 'nome'));

            $paginacao = $tableData->appends($request->except('page'));
        }

        return view('Matriculas::index.seletivo', ['tabela' => $tabela, 'paginacao' => $paginacao, 'actionButton' => $actionButtons]);
    }

    public function getShow($seletivoId, Request $request)
    {
        $seletivo = $this->seletivoRepository->find($seletivoId);
        if (!$seletivo) {
            flash()->error('Seletivo não encontrado');
            return redirect()->back();
        }

        $chamadas = $seletivo->chamadas()->pluck('nome', 'id');

        $requestData = $request->all();
        $requestData['seletivo_id'] = $seletivoId;

        $tableData = $this->inscricaoRepository->paginateRequest($requestData);

        $headers = array(
            'select_all' => 'Selecione',
            'id' => 'ID',
            'nome' => 'Nome',
            'cpf' => 'CPF',
            'email' => 'Email',
            'pontuacao' => 'pontuacao',
            'status' => 'Status',
        );

        $tabela = null;
        $paginacao = null;
        if ($tableData->count()) {
            $tabela = $tableData->columns($headers)
                ->attributes(array(
                    'class' => 'table'
                ))
                ->modifyCell('pontuacao', function () {
                    return array('style' => 'width: 12%');
                })
                ->modifyCell('select_all', function () {
                    return array('style' => 'width: 1%');
                })
                ->modify('select_all', function ($inscricao) {
                    return '<label><input class="matriculas" type="checkbox" value="'.$inscricao->user->id.'"></label>';
                })
                ->modify('nome', function ($inscricao) {
                    return '<p>'.$inscricao->user->nome.'</p>';
                })
                ->modify('status', function ($inscricao) {
                    if ($inscricao->status == 'inscrito') {
                        return '<span class="label label-primary">Inscrito</span>';
                    }
                    if ($inscricao->status == 'completo') {
                        return '<span class="label label-info">Completo</span>';
                    }
                    if (in_array($inscricao->status, ['eliminado', 'indeferido'])) {
                        return '<span class="label label-danger">'.ucfirst($inscricao->status).'</span>';
                    }
                    if (in_array($inscricao->status, ['classificado', 'deferido'])) {
                        return '<span class="label label-success">'.ucfirst($inscricao->status).'</span>';
                    }
                    if ($inscricao->status == 'avaliado') {
                        return '<span class="label label-warning">'.ucfirst($inscricao->status).'</span>';
                    }

                    return $inscricao->status;
                });

            $tabela = $tabela->sortable(array('id', 'nome', 'pontuacao', 'status'));

            $paginacao = $tableData->appends($request->except('page'));
        }

        return view('Matriculas::inscricoes.index', ['tabela' => $tabela, 'paginacao' => $paginacao, 'seletivo' => $seletivo, 'chamadas' => $chamadas]);
    }

    public function getListaChamada($seletivoId, Request $request)
    {
        $seletivo = $this->seletivoRepository->find($seletivoId);
        if (!$seletivo) {
            flash()->error('Seletivo não encontrado');
            return redirect()->back();
        }

        $chamadas = $seletivo->chamadas()->pluck('nome', 'id');

        $requestData = $request->all();
        $requestData['seletivo_id'] = $seletivoId;

        $tableData = $this->inscricaoRepository->paginateRequest($requestData);

        $headers = array(
            'select_all' => 'Selecione',
            'id' => 'ID',
            'nome' => 'Nome',
            'cpf' => 'CPF',
            'email' => 'Email',
            'pontuacao' => 'pontuacao',
            'status' => 'Status',
        );

        $tabela = null;
        $paginacao = null;
        if ($tableData->count()) {
            $tabela = $tableData->columns($headers)
                ->attributes(array(
                    'class' => 'table'
                ))
                ->modifyCell('pontuacao', function () {
                    return array('style' => 'width: 12%');
                })
                ->modifyCell('select_all', function () {
                    return array('style' => 'width: 1%');
                })
                ->modify('select_all', function ($inscricao) {
                    return '<label><input class="matriculas" type="checkbox" value="'.$inscricao->user->id.'"></label>';
                })
                ->modify('nome', function ($inscricao) {
                    return '<p>'.$inscricao->user->nome.'</p>';
                })
                ->modify('status', function ($inscricao) {
                    if ($inscricao->status == 'inscrito') {
                        return '<span class="label label-primary">Inscrito</span>';
                    }
                    if ($inscricao->status == 'completo') {
                        return '<span class="label label-info">Completo</span>';
                    }
                    if (in_array($inscricao->status, ['eliminado', 'indeferido'])) {
                        return '<span class="label label-danger">'.ucfirst($inscricao->status).'</span>';
                    }
                    if (in_array($inscricao->status, ['classificado', 'deferido'])) {
                        return '<span class="label label-success">'.ucfirst($inscricao->status).'</span>';
                    }
                    if ($inscricao->status == 'avaliado') {
                        return '<span class="label label-warning">'.ucfirst($inscricao->status).'</span>';
                    }

                    return $inscricao->status;
                });

            $tabela = $tabela->sortable(array('id', 'nome', 'pontuacao', 'status'));

            $paginacao = $tableData->appends($request->except('page'));
        }

        return view('Matriculas::inscricoes.index', ['tabela' => $tabela, 'paginacao' => $paginacao, 'seletivo' => $seletivo, 'chamadas' => $chamadas]);
    }

    public function createChamada(Request $request)
    {
        $data = $request->all();

        foreach ($data['users'] as $userSeletivo){
            $user = User::find($userSeletivo);

            if ($user){
                $userData = $user->toArray();

                $seletivoUserUpdate = SeletivoUser::where('cpf', $userData['cpf'])->first();
                $userData['password'] = bcrypt($user->cpf);

                if ($seletivoUserUpdate){
                    $checkChamada = SeletivoMatricula::where('seletivo_user_id', $seletivoUserUpdate->id)
                        ->where('chamada_id', $data['chamada_id'])->first();

                    if ($checkChamada){
                        continue;
                    }

                    $seletivoUserUpdate->update($userData);
                }else{
                    $seletivoUser = SeletivoUser::create($userData);
                }

                SeletivoMatricula::create([
                    'seletivo_user_id' => $seletivoUserUpdate ? $seletivoUserUpdate->id : $seletivoUser->id,
                    'chamada_id' => $data['chamada_id'],
                ]);
            }
        }

        return new JsonResponse(['message' => 'Sucesso']);
    }
}
