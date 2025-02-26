<?php

namespace Modulos\RH\Http\Controllers;

use App\Exports\FeriasExport;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use Maatwebsite\Excel\Excel;
use Modulos\RH\Http\Requests\ColaboradorFuncaoDeleteRequest;
use Modulos\RH\Http\Requests\ColaboradorFuncaoRequest;
use Modulos\RH\Http\Requests\ColaboradorRequest;
use Modulos\RH\Http\Requests\MatriculaColaboradorRequest;
use Modulos\RH\Models\Colaborador;
use Modulos\RH\Models\ColaboradorFuncao;
use Modulos\RH\Models\Funcao;
use Modulos\RH\Models\Setor;
use Modulos\RH\Repositories\ColaboradorFuncaoRepository;
use Modulos\RH\Repositories\ColaboradorRepository;
use Modulos\Core\Http\Controller\BaseController;
use Modulos\RH\Repositories\FuncaoRepository;
use Modulos\RH\Repositories\MatriculaColaboradorRepository;
use Modulos\RH\Repositories\PeriodoAquisitivoRepository;
use Modulos\RH\Repositories\SetorRepository;
use Modulos\Geral\Repositories\DocumentoRepository;
use Modulos\Geral\Repositories\PessoaRepository;
use Modulos\Seguranca\Providers\ActionButton\TButton;
use ActionButton;

class ColaboradoresController extends BaseController
{
    protected $colaboradorRepository;
    protected $pessoaRepository;
    protected $documentoRepository;
    protected $funcaoRepository;
    protected $setorRepository;
    protected $colaboradorFuncaoRepository;
    protected $periodosAquisitivosRepository;
    protected $matriculaColaboradorRepository;

    protected $excel;

    public function __construct(
        ColaboradorRepository $colaborador,
        PessoaRepository $pessoa,
        DocumentoRepository $documento,
        FuncaoRepository $funcao,
        SetorRepository $setor,
        ColaboradorFuncaoRepository $colaborador_funcao,
        PeriodoAquisitivoRepository $periodo_aquisitivo,
        MatriculaColaboradorRepository $matricula_colaborador,
        Excel $excel
    )
    {
        $this->colaboradorFuncaoRepository = $colaborador_funcao;
        $this->colaboradorRepository = $colaborador;
        $this->pessoaRepository = $pessoa;
        $this->documentoRepository = $documento;
        $this->funcaoRepository = $funcao;
        $this->setorRepository = $setor;
        $this->periodosAquisitivosRepository = $periodo_aquisitivo;
        $this->matriculaColaboradorRepository = $matricula_colaborador;
        $this->excel = $excel;
    }

    public function getIndex(Request $request)
    {
        $btnNovo = new TButton();
        $btnNovo->setName('Novo')->setRoute('rh.colaboradores.create')->setIcon('fa fa-plus')->setStyle('btn bg-olive');

        $actionButtons[] = $btnNovo;

        $paginacao = null;
        $tabela = null;


        $tableData = $this->colaboradorRepository->paginateRequest($request->all());

        if ($tableData->count()) {
            $tabela = $tableData->columns(array(
                'col_id' => '#',
                'pes_nome' => 'Nome',
                'pes_email' => 'Email',
                'setores_index' => 'Setor',
                'funcoes_index' => 'Função',
                'col_status' => 'Status',
                'col_action' => 'Ações'
            ))
                ->modifyCell('col_action', function () {
                    return array('style' => 'width: 140px;');
                })
                ->means('col_action', 'col_id')
                ->modify('col_action', function ($id) {
                    return ActionButton::grid([
                        'type' => 'SELECT',
                        'config' => [
                            'classButton' => 'btn-default',
                            'label' => 'Selecione'
                        ],
                        'buttons' => [
                            [
                                'classButton' => '',
                                'icon' => 'fa fa-pencil',
                                'route' => 'rh.colaboradores.edit',
                                'parameters' => ['id' => $id],
                                'label' => 'Editar',
                                'method' => 'get'
                            ],
                            [
                                'classButton' => '',
                                'icon' => 'fa fa-exchange',
                                'route' => 'rh.colaboradores.status',
                                'parameters' => ['id' => $id],
                                'label' => 'Matrículas',
                                'method' => 'get'
                            ],
                            [
                                'classButton' => '',
                                'icon' => 'fa fa-exchange',
                                'route' => 'rh.colaboradores.movimentacaosetor.index',
                                'parameters' => ['id' => $id],
                                'label' => 'Movimentação de Setor/Função',
                                'method' => 'get'
                            ],
                            [
                                'classButton' => '',
                                'icon' => 'fa fa-eye',
                                'route' => 'rh.colaboradores.show',
                                'parameters' => ['id' => $id],
                                'label' => 'Visualizar',
                                'method' => 'get'
                            ],
                            [
                                'classButton' => '',
                                'icon' => 'fa fa-eye',
                                'route' => 'rh.colaboradores.horastrabalhadas',
                                'parameters' => ['id' => $id],
                                'label' => 'Horas Trabalhadas',
                                'method' => 'get'
                            ]
                        ]
                    ]);
                })
                ->sortable(array('col_id', 'pes_nome', 'col_status'));

            $paginacao = $tableData->appends($request->except('page'));
        }

        $setores = Setor::all()->sortBy('set_descricao')->pluck('set_descricao', 'set_id');
        $funcoes = Funcao::all()->sortBy('fun_descricao')->pluck('fun_descricao', 'fun_id');

        return view('RH::colaboradores.index', ['tabela' => $tabela, 'paginacao' => $paginacao, 'actionButton' => $actionButtons, 'setores' => $setores, 'funcoes' => $funcoes]);
    }

    public function getCreate(Request $request)
    {
        $pessoaId = $request->get('id');
        $funcoes = $this->funcaoRepository->lists('fun_id', 'fun_descricao');
        $setores = $this->setorRepository->lists('set_id', 'set_descricao');

        $colaboradores = $this->colaboradorRepository->search(array(['col_pes_id', '=', $pessoaId]));

        if (!$colaboradores->isEmpty()) {
            flash()->error('Este CPF já tem um colaborador cadastrado!');
            return redirect()->route('rh.colaboradores.index');
        }

        if (!is_null($pessoaId)) {
            $pessoa = $this->pessoaRepository->findById($pessoaId);

            if ($pessoa) {
                return view('RH::colaboradores.create', ['pessoa' => $pessoa, 'funcoes' => $funcoes, 'setores' => $setores]);
            }
        }

        return view('RH::colaboradores.create', ['pessoa' => [], 'funcoes' => $funcoes, 'setores' => $setores]);
    }

    public function postCreate(Request $request)
    {

        $data = $request->all();

        $colaboradorRequest = new ColaboradorRequest();


        try {

            $cpf = $request->input('doc_conteudo');

            $dataForm = $request->all();
            $pes_id = isset($dataForm['pes_id']) ? $request->input('pes_id') : null;

            if ($this->documentoRepository->verifyCpf($request->input('doc_conteudo'), $pes_id)) {
                $errors = ['doc_conteudo' => 'CPF já cadastrado'];
                return redirect()->back()->with('validado', true)->withInput($request->all())->withErrors($errors);
            }

            $data['col_status'] = 'ativo';
            if ($pes_id) {

                $data['col_pes_id'] = $pes_id;

                $validator = Validator::make($request->all(), $colaboradorRequest->rules($pes_id));
                if ($validator->fails()) {
                    return redirect()->back()->with('validado', true)->withInput($request->all())->withErrors($validator);
                }

                DB::beginTransaction();
                $this->pessoaRepository->update($data, $pes_id, 'pes_id');
            } else {
                $validator = Validator::make($data, $colaboradorRequest->rules());

                if ($validator->fails()) {
                    return redirect()->back()->with('validado', true)->withInput($request->all())->withErrors($validator);
                }

                DB::beginTransaction();
                $pessoa = $this->pessoaRepository->create($data);
                $data['col_pes_id'] = $pessoa->pes_id;
            }

            $colaborador = $this->colaboradorRepository->create($data);
            $matricula = $this->matriculaColaboradorRepository->create(['mtc_col_id' => $colaborador->col_id,'mtc_data_inicio' => $data['col_data_admissao']]);

            $dataDocumento = array(
                'doc_tpd_id' => 2,
                'doc_conteudo' => $cpf,
                'doc_pes_id' => $data['col_pes_id']
            );

            $this->documentoRepository->updateOrCreate(['doc_pes_id' => $data['col_pes_id'], 'doc_tpd_id' => 2], $dataDocumento);

            flash()->success('Colaborador criado com sucesso!');

            DB::commit();
            return redirect()->route('rh.colaboradores.show', $colaborador->col_id);
        } catch (\Exception $e) {
            if (config('app.debug')) {
                throw $e;
            }
            DB::rollback();
            flash()->error('Erro ao tentar salvar. Caso o problema persista, entre em contato com o suporte.');

            return redirect()->back()->with('validado', true);
        }
    }

    public function getEdit($colaboradorId)
    {
        $colaborador = $this->colaboradorRepository->find($colaboradorId);
        $funcoes = $this->funcaoRepository->lists('fun_id', 'fun_descricao');
        $setores = $this->setorRepository->lists('set_id', 'set_descricao');

        if (!$colaborador) {
            flash()->error('Colaborador não existe.');
            return redirect()->back();
        }

        $pessoa = $this->pessoaRepository->findById($colaborador->col_pes_id);

        return view('RH::colaboradores.edit', compact(['pessoa', 'colaborador', 'funcoes', 'setores']));
    }

    public function putEdit($colaboradorId, Request $request)
    {

        $data = $request->all();

        $colaboradorRequest = new ColaboradorRequest();

        $colaborador = $this->colaboradorRepository->find($colaboradorId);

        $pessoa = $colaborador->pessoa;

        $validator = Validator::make($data, $colaboradorRequest->rules($pessoa->pes_id));

        if ($validator->fails()) {
            return redirect()->back()->with('validado', true)->withInput($request->all())->withErrors($validator);
        }

        if (!$pessoa) {
            flash()->error('Pessoa não existe.');
            return redirect()->route('rh.colaboradores.index');
        }

        if ($this->documentoRepository->verifyCpf($request->input('doc_conteudo'), $pessoa->pes_id)) {
            $errors = ['doc_conteudo' => 'CPF já cadastrado'];
            return redirect()->back()->withInput($request->all())->withErrors($errors);
        }

        DB::beginTransaction();
        try {
            $oldPessoa = clone $pessoa;

            $pessoa->fill($request->all())->save();

            $this->colaboradorRepository->update($data, $colaboradorId);

            $dataDocumento = [
                'doc_pes_id' => $pessoa->pes_id,
                'doc_conteudo' => $request->input('doc_conteudo'),
                'doc_tpd_id' => 2
            ];

            $this->documentoRepository->updateOrCreate(['doc_pes_id' => $pessoa->pes_id, 'doc_tpd_id' => 2], $dataDocumento);

            DB::commit();

            if ($this->checkUpdateMigracao($oldPessoa, $pessoa)) {
                $this->pessoaRepository->updatePessoaAmbientes($pessoa);
            }

            flash()->success('Colaborador editado com sucesso!');
            return redirect()->route('rh.colaboradores.show', $pessoa->colaborador->col_id);
        } catch (ValidationException $e) {
            DB::rollback();
            return redirect()->back()->withInput($request->all())->withErrors($e);
        } catch (\Exception $e) {
            DB::rollback();
            if (config('app.debug')) {
                throw $e;
            }

            flash()->error('Erro ao tentar editar. Caso o problema persista, entre em contato com o suporte.');

            return redirect()->back();
        }
    }

    public function getCreateMatricula($colaboradorId)
    {
        $colaborador = $this->colaboradorRepository->find($colaboradorId);

        if (!$colaborador) {
            flash()->error('Colaborador não existe');
            return redirect()->back();
        }

        return view('RH::colaboradores.create-status', compact('colaborador'));
    }


    public function getStatus($colaboradorId)
    {
        $actionButtons = [];

        $colaborador = $this->colaboradorRepository->find($colaboradorId);
        if($colaborador->col_status === 'desligado'){
            $btnNovo = new TButton();
            $btnNovo->setName('Nova Matrícula')->setRoute('rh.colaboradores.matricula.create')->setParameters(['id' => $colaboradorId])->setIcon('fa fa-plus')->setStyle('btn bg-olive');
            $actionButtons[] = $btnNovo;
        }


        $matriculas = $this->matriculaColaboradorRepository->getMatriculasByColId($colaboradorId);

        if (!$colaborador) {
            flash()->error('Colaborador não existe.');
            return redirect()->back();
        }

        $pessoa = $this->pessoaRepository->findById($colaborador->col_pes_id);

        return view('RH::colaboradores.status', compact(['colaborador', 'matriculas', 'actionButtons']));
    }

    public function createMatricula($colaboradorId, Request $request)
    {
        $colaborador = $this->colaboradorRepository->find($colaboradorId);

        if (!$colaborador) {
            flash()->error('Colaborador não existe.');
            return redirect()->back();
        }

        $data = $request->all();
        $data['mtc_col_id'] = $colaboradorId;
        DB::beginTransaction();
        try {

            $this->matriculaColaboradorRepository->create($data);
            $colaborador->col_status = 'ativo';
            $colaborador->save();
            DB::commit();

            flash()->success('Status atualizado com sucesso!');

            return redirect()->route('rh.colaboradores.status', $colaboradorId);
        } catch (ValidationException $e) {
            DB::rollback();
            return redirect()->back()->withInput($request->all())->withErrors($e);
        } catch (\Exception $e) {
            DB::rollback();
            if (config('app.debug')) {
                throw $e;
            }

            flash()->error('Erro ao tentar editar. Caso o problema persista, entre em contato com o suporte.');

            return redirect()->back();
        }
    }



    public function putMatricula($matriculaId, Request $request)
    {

        $data = $request->all();
        $matricula = $this->matriculaColaboradorRepository->find($matriculaId);
        $colaborador = $this->colaboradorRepository->find($matricula->mtc_col_id);
        $funcoes = $colaborador->funcoes;

        $validatorRequest = new MatriculaColaboradorRequest();
        $validator = Validator::make($request->all(), $validatorRequest->rules());
        if ($validator->fails()) {
            flash()->error('Data de fim é obrigatória.');
            return redirect()->back();
        }

        DB::beginTransaction();
        try {

            $this->matriculaColaboradorRepository->update($data, $matriculaId);

            foreach ($funcoes as $funcao) {
                $funcao->cfn_data_fim = $data['mtc_data_fim'];
                $funcao->save();
            }
            $colaborador->col_status = 'desligado';
            $colaborador->save();
            DB::commit();

            flash()->success('Status atualizado com sucesso!');

            return redirect()->route('rh.colaboradores.status', $colaborador->col_id);
        } catch (ValidationException $e) {
            DB::rollback();
            return redirect()->back()->withInput($request->all())->withErrors($e);
        } catch (\Exception $e) {
            DB::rollback();
            if (config('app.debug')) {
                throw $e;
            }

            flash()->error('Erro ao tentar editar. Caso o problema persista, entre em contato com o suporte.');

            return redirect()->back();
        }
    }

    public function postDeleteMatricula(Request $request)
    {
        $matriculaId = $request->get('id');
        try {

            $this->matriculaColaboradorRepository->delete($matriculaId);

            flash()->success('Matrícula excluída com sucesso.');

            return redirect()->back();
        } catch (\Illuminate\Database\QueryException $e) {
            flash()->error('Erro ao tentar deletar. O recurso contém dependências no sistema.');
            return redirect()->back();
        } catch (\Exception $e) {
            if (config('app.debug')) {
                throw $e;
            }

            flash()->error('Erro ao tentar excluir. Caso o problema persista, entre em contato com o suporte.');
            return redirect()->back();
        }
    }

    public function getMovimentacaoSetor($colaboradorId)
    {
        $colaborador = $this->colaboradorRepository->find($colaboradorId);

        $funcoes = $this->funcaoRepository->lists('fun_id', 'fun_descricao');
        $setores = $this->setorRepository->lists('set_id', 'set_descricao');

        if (!$colaborador) {
            flash()->error('Colaborador não existe.');
            return redirect()->back();
        }

        $historico_setores = $this->colaboradorRepository->getHistory($colaborador->col_id);

        $pessoa = $this->pessoaRepository->findById($colaborador->col_pes_id);

        return view('RH::colaboradores.movimentacaosetor', compact(['pessoa', 'colaborador', 'funcoes', 'setores']));
    }

    public function putMovimentacaoSetor($colaboradorId, Request $request)
    {

        $data = $request->all();

        $colaborador = $this->colaboradorRepository->find($colaboradorId);

        DB::beginTransaction();
        try {

            $this->colaboradorRepository->update($data, $colaboradorId);

            DB::commit();

            flash()->success('Status atualizado com sucesso!');

            return redirect()->back();
        } catch (ValidationException $e) {
            DB::rollback();
            return redirect()->back()->withInput($request->all())->withErrors($e);
        } catch (\Exception $e) {
            DB::rollback();
            if (config('app.debug')) {
                throw $e;
            }

            flash()->error('Erro ao tentar editar. Caso o problema persista, entre em contato com o suporte.');

            return redirect()->back();
        }
    }

    public function attachFuncao($colaboradorId, ColaboradorFuncaoRequest $request)
    {

        $data = $request->except('_token');

        $colaborador = $this->colaboradorRepository->find($colaboradorId);

        if (!$colaborador) {
            flash()->error('Colaborador não existe.');
            return redirect()->back();
        }

        try {
            DB::beginTransaction();

            $data['cfn_col_id'] = $colaborador->col_id;
            $colaborador_funcao = $this->colaboradorFuncaoRepository->create($data);

            DB::commit();

            flash()->success('Função adicionada ao colaborador com sucesso!');

            return redirect()->back();
        } catch (ValidationException $e) {
            DB::rollback();
            return redirect()->back()->withInput($request->all())->withErrors($e);
        } catch (\Exception $e) {
            DB::rollback();
            if (config('app.debug')) {
                throw $e;
            }

            flash()->error('Erro ao tentar adicionar função ao colaborador. Caso o problema persista, entre em contato com o suporte.');

            return redirect()->back();
        }
    }

    public function detachFuncao($colaboradorId, $colaboradorFuncaoId, Request $request)
    {

        $validatorRequest = new ColaboradorFuncaoDeleteRequest();
        $validator = Validator::make($request->all(), $validatorRequest->rules());
        if ($validator->fails()) {
            flash()->error('Data de fim é obrigatória.');
            return redirect()->back();
        }

        $data = $request->except('_token');
        $colaborador = $this->colaboradorRepository->find($colaboradorId);

        if (!$colaborador) {
            flash()->error('Colaborador não existe.');
            return redirect()->back();
        }

        $colaborador_funcao = $this->colaboradorFuncaoRepository->find($colaboradorFuncaoId);

        if (!$colaborador_funcao) {
            flash()->error('Requisição inválida.');
            return redirect()->back();
        }

        try {
            DB::beginTransaction();

            $colaborador_funcao = $this->colaboradorFuncaoRepository->update($data, $colaborador_funcao->cfn_id);

            DB::commit();

            flash()->success('Função removida do colaborador com sucesso!');

            return redirect()->back();
        } catch (ValidationException $e) {
            DB::rollback();
            return redirect()->back()->withInput($request->all())->withErrors($e);
        } catch (\Exception $e) {
            DB::rollback();
            if (config('app.debug')) {
                throw $e;
            }

            flash()->error('Erro ao tentar adicionar função ao colaborador. Caso o problema persista, entre em contato com o suporte.');

            return redirect()->back();
        }
    }

    public function removeFuncao($colaboradorId, $colaboradorFuncaoId, Request $request)
    {

        $data = $request->except('_token');

        $colaborador = $this->colaboradorRepository->find($colaboradorId);

        if (!$colaborador) {
            flash()->error('Colaborador não existe.');
            return redirect()->back();
        }

        $colaborador_funcao = $this->colaboradorFuncaoRepository->find($colaboradorFuncaoId);

        if (!$colaborador_funcao) {
            flash()->error('Requisição inválida.');
            return redirect()->back();
        }

        try {
            DB::beginTransaction();

            $colaborador_funcao = $this->colaboradorFuncaoRepository->delete($colaborador_funcao->cfn_id);

            DB::commit();

            flash()->success('Função removida do colaborador com sucesso!');

            return redirect()->back();
        } catch (ValidationException $e) {
            DB::rollback();
            return redirect()->back()->withInput($request->all())->withErrors($e);
        } catch (\Exception $e) {
            DB::rollback();
            if (config('app.debug')) {
                throw $e;
            }

            flash()->error('Erro ao tentar remover função ao colaborador. Caso o problema persista, entre em contato com o suporte.');

            return redirect()->back();
        }
    }

    public function getShow($colaboradorId)
    {
        $colaborador = $this->colaboradorRepository->find($colaboradorId);
        session(['last_acad_route' => 'rh.colaboradores.show', 'last_id' => $colaboradorId]);

        if (!$colaborador) {
            flash()->error('Colaborador não existe.');
            return redirect()->back();
        }

        $periodos_matriculas = [];
        $matriculas_colaborador = $colaborador->matriculas;
        foreach ($matriculas_colaborador as $matricula){

            $data['data'] = $this->periodosAquisitivosRepository->periodData($matricula);
            $data['matricula'] = $matricula;
            $periodos_matriculas[] = $data;
        }

        $situacao = [
            'cursando' => 'Cursando',
            'reprovado' => 'Reprovado',
            'evadido' => 'Evadido',
            'trancado' => 'Trancado',
            'desistente' => 'Desistente'
        ];

        return view('RH::colaboradores.show', ['pessoa' => $colaborador->pessoa, 'colaborador' => $colaborador, 'situacao' => $situacao, 'periodos_matriculas' => $periodos_matriculas]);
    }

    private function checkUpdateMigracao($oldPessoa, $pessoa)
    {
        if (strcmp($oldPessoa->pes_nome, $pessoa->pes_nome) != 0 || strcmp($oldPessoa->pes_email, $pessoa->pes_email) != 0
            || strcmp($oldPessoa->pes_cidade, $pessoa->pes_cidade) != 0) {
            return true;
        }

        return false;
    }

    public function exportFerias(Request $request)
    {
        $colaboradores = Colaborador::where('col_status', 'ativo')->get();

        $reportData = [
            [
                'Nome do Colaborador',
                'Função',
                'Setor',
                'Data Contratação',
                'Início Período Gozo',
                'Fim Período Gozo',
                'Limite de Gozo',
                'Dias já Gozados',
                'Dias Vencidos'
            ]
        ];

        foreach ($colaboradores as $colaborador) {
            $funcaoAtual = $colaborador->funcoes->first();
            $funcaoNome = $funcaoAtual ? $funcaoAtual->funcao->fun_descricao : '-';
            $setorNome = $funcaoAtual ? $funcaoAtual->setor->set_descricao : '-';

            $matricula = $colaborador->matriculas->sortByDesc('mtc_data_inicio')->first();
            if (!$matricula) {
                continue;
            }

            $periodos = $this->periodosAquisitivosRepository->periodData($matricula);
            if (empty($periodos)) {
                continue;
            }

            $periodos = array_values($periodos);
            $totalPeriodos = count($periodos);
            $periodoSelecionado = null;

            // Percorre os períodos na ordem (do mais antigo para o mais recente)
            foreach ($periodos as $index => $periodo) {
                if ($periodo['dias'] < 30) {
                    $existePosteriorComDias = false;
                    // Verifica os períodos posteriores
                    for ($j = $index + 1; $j < $totalPeriodos; $j++) {
                        if ($periodos[$j]['dias'] > 0) {
                            $existePosteriorComDias = true;
                            break;
                        }
                    }
                    if (!$existePosteriorComDias) {
                        $periodoSelecionado = $periodo;
                        break;
                    }
                }
            }

            // Se nenhum período atender a condição, usa o último período
            if (!$periodoSelecionado) {
                $periodoSelecionado = end($periodos);
            }

            // Cálculo do Limite de Gozo: fim do período menos 30 dias
            $limiteGozo = '-';
            if (isset($periodoSelecionado['fim'])) {
                $limiteGozo = Carbon::createFromFormat('d/m/Y', $periodoSelecionado['fim'])
                    ->subDays(30)
                    ->format('d/m/Y');
            }

            // Cálculo dos Dias Vencidos: diferença entre hoje e o fim do período
            $diasVencidos = 0;
            if (isset($periodoSelecionado['fim'])) {
                $fimPeriodo = Carbon::createFromFormat('d/m/Y', $periodoSelecionado['fim'])->startOfDay();
                $hoje = Carbon::now()->startOfDay();
                $diasVencidos = $fimPeriodo->isPast() ? $fimPeriodo->diffInDays($hoje) : 0;
            }

            $dataContratacao = Carbon::parse($matricula->getRawOriginal('mtc_data_inicio'))->format('d/m/Y');

            $reportData[] = [
                $colaborador->pessoa->pes_nome,
                $funcaoNome,
                $setorNome,
                $dataContratacao,
                $periodoSelecionado['inicio'] ?? '-',
                $periodoSelecionado['fim'] ?? '-',
                $limiteGozo,
                $periodoSelecionado['dias'] ?? 0,
                $diasVencidos
            ];
        }

        return $this->excel->download(
            new FeriasExport($reportData),
            'relatorio_ferias_' . date('Y-m-d') . '.xlsx'
        );
    }

}
