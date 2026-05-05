@extends('layouts.modulos.default')

@section('title')
    Relatório de Alunos por Disciplina
@endsection

@section('content')
    <div class="row py-2">
        <div class="card card-primary card-outline p-0">
            <div class="card-header with-border">
                <h3 class="card-title"><i class="fa fa-filter"></i> Filtrar dados</h3>

                <div class="card-tools">
                    <button type="button" class="btn btn-tool" data-lte-toggle="card-collapse">
                        <i class="fa fa-minus"></i>
                    </button>
                </div>
                <!-- /.card-tools -->
            </div>
            <!-- /.card-header -->
            <div class="card-body">
                <div class="card-body">
                    <form id="form" method="GET" action="">
                        <div class="row">
                            <div class="col-md-4 @if ($errors->has('crs_id')) has-error @endif">
                                <label for="crs_id">Curso <small class="obrigatorio-dot">*</small></label>
                                <div class="form-group">
                                    <select name="crs_id" id="crs_id" class="form-control">
                                        <option value="">Escolha o Curso</option>
                                        @foreach($cursos as $key => $value)
                                            <option value="{{ $key }}" {{ Request::input('crs_id') == $key ? 'selected' : '' }}>{{ $value }}</option>
                                        @endforeach
                                    </select>
                                    @if ($errors->has('crs_id')) <p class="help-block">{{ $errors->first('crs_id') }}</p> @endif
                                </div>
                            </div>
                            <div class="col-md-4 @if ($errors->has('ofc_id')) has-error @endif">
                                <label for="ofc_id">Oferta de Curso <small class="obrigatorio-dot">*</small></label>
                                <div class="form-group">
                                    <select name="ofc_id" id="ofc_id" class="form-control">
                                        @foreach($ofertasCurso as $key => $value)
                                            <option value="{{ $key }}" {{ Request::input('ofc_id') == $key ? 'selected' : '' }}>{{ $value }}</option>
                                        @endforeach
                                    </select>
                                    @if ($errors->has('ofc_id')) <p class="help-block">{{ $errors->first('ofc_id') }}</p> @endif
                                </div>
                            </div>
                            <div class="col-md-4 @if ($errors->has('trm_id')) has-error @endif">
                                <label for="trm_id">Turma <small class="obrigatorio-dot">*</small></label>
                                <div class="form-group">
                                    <select name="trm_id" id="trm_id" class="form-control">
                                        @foreach($turmas as $key => $value)
                                            <option value="{{ $key }}" {{ Request::input('trm_id') == $key ? 'selected' : '' }}>{{ $value }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-2 @if ($errors->has('per_id')) has-error @endif">
                                <label for="per_id">Período Letivo <small class="obrigatorio-dot">*</small></label>
                                <div class="form-group">
                                    <select name="per_id" id="per_id" class="form-control">
                                        @foreach($periodos as $key => $value)
                                            <option value="{{ $key }}" {{ Request::input('per_id') == $key ? 'selected' : '' }}>{{ $value }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-3 @if ($errors->has('ofd_id')) has-error @endif">
                                <label for="ofd_id">Disciplinas Ofertadas <small class="obrigatorio-dot">*</small></label>
                                <div class="form-group">
                                    <select name="ofd_id" id="ofd_id" class="form-control">
                                        @foreach($disciplinas as $key => $value)
                                            <option value="{{ $key }}" {{ Request::input('ofd_id') == $key ? 'selected' : '' }}>{{ $value }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-3 @if ($errors->has('pol_id')) has-error @endif">
                                <label for="pol_id">Polo</label>
                                <div class="form-group">
                                    <select name="pol_id" id="pol_id" class="form-control">
                                        <option value="">Selecione o polo</option>
                                        @foreach($polos as $key => $value)
                                            <option value="{{ $key }}" {{ Request::input('pol_id') == $key ? 'selected' : '' }}>{{ $value }}</option>
                                        @endforeach
                                    </select>
                                    @if ($errors->has('pol_id')) <p class="help-block">{{ $errors->first('pol_id') }}</p> @endif
                                </div>
                            </div>
                            <div class="col-md-2 @if ($errors->has('mof_situacao_matricula')) has-error @endif">
                                <label for="mof_situacao_matricula">Situação</label>
                                <div class="form-group">
                                    <select name="mof_situacao_matricula" id="mof_situacao_matricula" class="form-control">
                                        @foreach($situacao as $key => $value)
                                            <option value="{{ $key }}" {{ Request::input('mof_situacao_matricula') == $key ? 'selected' : '' }}>{{ $value }}</option>
                                        @endforeach
                                    </select>
                                    @if ($errors->has('mof_situacao_matricula')) <p class="help-block">{{ $errors->first('mof_situacao_matricula') }}</p> @endif
                                </div>
                            </div>
                            <div class="col-md-2">
                                <label for=""></label>
                                <div class="form-group">
                                    <input type="submit" id="btnBuscar" class="btn btn-primary w-100" value="Buscar">
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            <!-- /.card-body -->
        </div>
    </div>

    <div class="row py-2">
        @if(!is_null($tabela))
            <div class="card card-primary card-outline p-0">
                <div class="card-header">
                    <div class="row">
                        <div class="col-md-2" style="float: right; margin-right: 1%;">
                            <form id="exportPdf" target="_blank" method="post" action="{{{ route('academico.relatoriosmatriculasdisciplinas.xls') }}}">
                                {!! ActionButton::grid([
                                        'type' => 'LINE',
                                        'buttons' => [
                                            [
                                            'classButton' => 'btn btn-success',
                                            'icon' => 'fa fa-file-excel-o',
                                            'route' => 'academico.relatoriosmatriculasdisciplinas.xls',
                                            'label' => 'Exportar para XLS',
                                            'method' => 'post',
                                            'id' => '',
                                            'attributes' => ['id' => 'formPdf','target' => '_blank']
                                            ]
                                        ]
                                ]) !!}
                                <input type="hidden" name="trm_id" id="turmaId" value="">
                                <input type="hidden" name="crs_id" id="cursoId" value="">
                                <input type="hidden" name="ofc_id" id="ofertaCursoId" value="">
                                <input type="hidden" name="pol_id" id="poloId" value="">
                                <input type="hidden" name="per_id" id="periodoId" value="">
                                <input type="hidden" name="ofd_id" id="ofertaDisciplinaId" value="">
                                <input type="hidden" name="mof_situacao_matricula" id="situacao" value="">
                            </form>
                        </div>
                        <div class="col-md-2" style="float: right;">
                            <form id="exportPdf" target="_blank" method="post" action="{{{ route('academico.relatoriosmatriculasdisciplinas.pdf') }}}">
                                {!! ActionButton::grid([
                                        'type' => 'LINE',
                                        'buttons' => [
                                            [
                                            'classButton' => 'btn btn-danger',
                                            'icon' => 'fa fa-file-pdf-o',
                                            'route' => 'academico.relatoriosmatriculasdisciplinas.pdf',
                                            'label' => 'Exportar para PDF',
                                            'method' => 'post',
                                            'id' => '',
                                            'attributes' => ['id' => 'formPdf','target' => '_blank']
                                            ]
                                        ]
                                ]) !!}
                                <input type="hidden" name="trm_id" id="pturmaId" value="">
                                <input type="hidden" name="crs_id" id="pcursoId" value="">
                                <input type="hidden" name="ofc_id" id="pofertaCursoId" value="">
                                <input type="hidden" name="pol_id" id="ppoloId" value="">
                                <input type="hidden" name="per_id" id="pperiodoId" value="">
                                <input type="hidden" name="ofd_id" id="pofertaDisciplinaId" value="">
                                <input type="hidden" name="mof_situacao_matricula" id="psituacao" value="">
                            </form>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    {!! $tabela->render() !!}
                </div>
            </div>

            <div class="text-center">{!! $paginacao->links('pagination::bootstrap-4') !!}</div>

        @else
            <div class="card card-primary card-outline p-0">
                <div class="card-body">Sem registros para apresentar</div>
            </div>
        @endif
    </div>
@endsection

@section('scripts')
    <script>
        window.PageRoutes = {
            // Add Routes
        };
    </script>

    @vite('modulos/Academico/Resources/js/pages/relatoriosmatriculasdisciplina/index.js')
@stop
