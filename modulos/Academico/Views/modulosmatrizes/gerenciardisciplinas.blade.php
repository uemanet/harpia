@extends('layouts.modulos.default')

@section('title')
    Gerenciamento de Disciplinas
@stop

@section('subtitle')
    Gerenciamento de disciplinas :: {{$curso->crs_nome}} :: {{$matriz->mtc_titulo}} :: {{ $modulo->mdo_nome }}
@stop

@section('content')
    <div class="row py-2">
        <div class="card card-primary card-outline p-0">
            <div class="card-header with-border">
                <h3 class="card-title">
                    Buscar Disciplinas
                </h3>
                <div class="card-tools">
                    <button type="button" class="btn btn-tool" data-lte-toggle="card-collapse">
                        <i class="fa fa-minus"></i>
                    </button>
                </div>
            </div>
            <div class="card-body">
                <div class="row">
                    <form action="#" id="formLocalizar" class="d-flex w-100">
                        <div class="col-md-10 px-1">
                            <div class="form-group">
                                <input type="text" class="form-control" id="disciplina" placeholder="Nome da Disciplina">
                            </div>
                        </div>
                        <div class="col-md-2 px-1">
                            <button type="submit" class="btn btn-primary w-100" id="btnLocalizar">Buscar</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="row py-2">
        <div id="cardDisciplinasLocalizadas" class="card card-primary card-outline hidden">
            <div class="card-header with-border">
                <h3 class="card-title">
                    Disciplinas Localizadas
                </h3>
                <div class="card-tools">
                    <button type="button" class="btn btn-tool" data-lte-toggle="card-collapse">
                        <i class="fa fa-minus"></i>
                    </button>
                </div>
            </div>
            <div class="card-body">
            </div>
        </div>
    </div>

    <div class="row py-2">
        <div id="cardDisciplinasCadastradas" class="card card-primary card-outline">
            <div class="card-header with-border">
                <h3 class="card-title">
                    Disciplinas do módulo
                </h3>
                <div class="card-tools">
                    <button type="button" class="btn btn-tool" data-lte-toggle="card-collapse">
                        <i class="fa fa-minus"></i>
                    </button>
                </div>
            </div>
            <div class="card-body">
                @if($disciplinas->count())
                    <table class="table table-bordered table-hover" id="tableDisciplinasModulo">
                        <thead>
                        <th>#</th>
                        <th>Nome</th>
                        <th>Nível</th>
                        <th>Carga Horária</th>
                        <th>Créditos</th>
                        <th>Tipo da Disciplina</th>
                        <th>Pré-Requisitos</th>
                        <th>Ações</th>
                        </thead>
                        <tbody>
                        @foreach($disciplinas as $disciplina)
                            <tr>
                                <td>{{$disciplina->mdc_id}}</td>
                                <td>{{$disciplina->dis_nome}}</td>
                                <td>{{$disciplina->nvc_nome}}</td>
                                <td>{{$disciplina->dis_carga_horaria}} horas</td>
                                <td>{{$disciplina->dis_creditos}}</td>
                                <td>{{$disciplina->mdc_tipo_disciplina}}</td>
                                @if(!empty($disciplina->pre_requisitos))
                                    <td>
                                        @foreach($disciplina->pre_requisitos as $disc)
                                            <p>{{ $disc->dis_nome }}</p>
                                        @endforeach
                                    </td>
                                @else
                                    <td>Sem pré-requisitos</td>
                                @endif
                                <td>
                                    {!!
                                        ActionButton::grid([
                                            'type' => 'SELECT',
                                            'config' => [
                                                'classButton' => 'btn-default',
                                                'label' => 'Selecione'
                                            ],
                                            'buttons' => [
                                                [
                                                    'classButton' => 'btnEdit',
                                                    'icon' => 'fa fa-pencil',
                                                    'route' => 'academico.cursos.matrizescurriculares.modulosmatrizes.editardisciplinas',
                                                    'parameters' => ['id' => $disciplina->mdc_id],
                                                    'label' => 'Editar',
                                                    'id' => $disciplina->mdc_id,
                                                    'method' => 'get'
                                                ],
                                                [
                                                    'classButton' => 'btn btn-delete',
                                                    'icon' => 'fa fa-trash',
                                                    'route' => 'academico.cursos.matrizescurriculares.modulosmatrizes.delete',
                                                    'id' => $disciplina->mdc_id,
                                                    'label' => 'Excluir',
                                                    'method' => 'post'
                                                ]
                                            ]
                                        ])
                                    !!}
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                @else
                    <p>Sem disciplinas cadastradas</p>
                @endif
            </div>
        </div>
    </div>

@stop

@section('scripts')
    <script>
        window.PageRoutes = {
            // Add Routes
        };
    </script>

    @vite('modulos/Academico/Resources/js/pages/modulosmatrizes/gerenciardisciplinas.js')
@stop