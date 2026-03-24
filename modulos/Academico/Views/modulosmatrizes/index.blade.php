@extends('layouts.modulos.default')

@section('title')
    Módulos
@stop

@section('subtitle')
    Gerenciamento de matrizes curriculares :: {{$curso->crs_nome}} :: {{$matrizcurricular->mtc_titulo}}
@stop

@section('actionButton')
    {!!ActionButton::render($actionButton)!!}
@stop

@section('content')

    @if(!$modulos->isEmpty())
        <div id="accordionModulos">
            @foreach($modulos as $modulo)
                <div class="card card-primary card-outline mb-3 shadow-sm border">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h4 class="card-title m-0 w-100">
                            <a class="d-block w-100 text-decoration-none text-dark fw-bold" data-bs-toggle="collapse" data-bs-parent="#accordionModulos" href="#collapse{{$modulo->mdo_id}}" aria-expanded="{{ $loop->first ? 'true' : 'false' }}">
                                <i class="fa fa-cube me-2 text-primary"></i> {{$modulo->mdo_nome}}
                            </a>
                        </h4>

                        <!-- Botões de Ação do Módulo (Alinhados à direita com ms-auto) -->
                        <div class="card-tools ms-auto flex-shrink-0">
                            {!! ActionButton::grid([
                                'type' => 'LINE',
                                'buttons' => [
                                    [
                                        'classButton' => 'btn btn-sm btn-outline-primary',
                                        'icon' => 'fa fa-pencil',
                                        'route' => 'academico.cursos.matrizescurriculares.modulosmatrizes.edit',
                                        'parameters' => ['id' => $modulo->mdo_id],
                                        'label' => '',
                                        'method' => 'get'
                                    ],
                                    [
                                        'classButton' => 'btn btn-sm btn-outline-danger btn-delete',
                                        'icon' => 'fa fa-trash',
                                        'route' => 'academico.cursos.matrizescurriculares.modulosmatrizes.delete',
                                        'id' => $modulo->mdo_id,
                                        'label' => '',
                                        'method' => 'post'
                                    ]
                                ]
                            ]) !!}
                        </div>
                    </div>

                    <div id="collapse{{$modulo->mdo_id}}" class="collapse @if($loop->first) show @endif" data-bs-parent="#accordionModulos">
                        <div class="card-body p-0">
                            @if(!$modulo->disciplinas->isEmpty())
                                <div class="table-responsive">
                                    <table class="table table-striped table-hover align-middle m-0">
                                        <thead class="table-light">
                                        <tr>
                                            <th>Nome</th>
                                            <th>Nível</th>
                                            <th>Carga Horária</th>
                                            <th>Créditos</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        @foreach($modulo->disciplinas as $disciplina)
                                            <tr>
                                                <td class="fw-medium">{{$disciplina->dis_nome}}</td>
                                                <td>{{$disciplina->nvc_nome}}</td>
                                                <td>{{$disciplina->dis_carga_horaria}} horas</td>
                                                <td>{{$disciplina->dis_creditos}}</td>
                                            </tr>
                                        @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            @else
                                <div class="p-4 text-center text-muted">
                                    <i class="fa fa-folder-open-o fa-3x mb-3 text-light"></i>
                                    <p class="mb-0">Sem disciplinas cadastradas neste módulo.</p>
                                </div>
                            @endif
                        </div>

                        <div class="card-footer bg-light">
                            {!! ActionButton::grid([
                                'type' => 'LINE',
                                'buttons' => [
                                    [
                                        'classButton' => 'btn btn-success',
                                        'icon' => 'fa fa-cogs',
                                        'route' => 'academico.cursos.matrizescurriculares.modulosmatrizes.gerenciardisciplinas',
                                        'parameters' => ['id' => $modulo->mdo_id],
                                        'label' => ' Gerenciar disciplinas do módulo',
                                        'method' => 'get'
                                    ],
                                ]
                            ]) !!}
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @else
        <div class="card card-primary card-outline shadow-sm">
            <div class="card-body text-center py-5 text-muted">
                <i class="fa fa-inbox fa-4x mb-3 text-light"></i>
                <h4>Nenhum registro encontrado</h4>
                <p class="mb-0">Ainda não existem módulos cadastrados para esta matriz curricular.</p>
            </div>
        </div>
    @endif
@stop