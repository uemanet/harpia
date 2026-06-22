@extends('layouts.modulos.default')

@section('title')
    Relatório de Atas Finais
@endsection

@section('content')
    <div class="row">
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
            <form id="form" method="POST" action="{{{ route('academico.relatoriosatasfinais.pdf') }}}">
                {{ csrf_field() }}
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
                            @if ($errors->has('trm_id')) <p class="help-block">{{ $errors->first('trm_id') }}</p> @endif
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-5 @if ($errors->has('pol_id')) has-error @endif">
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
                    <div class="col-md-4 @if ($errors->has('mat_situacao')) has-error @endif">
                        <label for="mat_situacao">Situação</label>
                        <div class="form-group">
                            <select name="mat_situacao" id="mat_situacao" class="form-control">
                                @foreach($situacao as $key => $value)
                                    <option value="{{ $key }}" {{ Request::input('mat_situacao') == $key ? 'selected' : '' }}>{{ $value }}</option>
                                @endforeach
                            </select>
                            @if ($errors->has('mat_situacao')) <p class="help-block">{{ $errors->first('mat_situacao') }}</p> @endif
                        </div>
                    </div>
                    <div class="col-md-3">
                        <label for="">&nbsp;</label>
                        <div class="form-group">
                            {!! ActionButton::grid([
                                    'type' => 'LINE',
                                    'buttons' => [
                                        [
                                        'classButton' => 'btn btn-danger pdfButton',
                                        'icon' => 'fa-solid fa-file-pdf',
                                        'route' => 'academico.relatoriosatasfinais.pdf',
                                        'label' => 'Exportar para PDF',
                                        'method' => 'post',
                                        'id' => '',
                                        'attributes' => ['id' => 'formPdf','target' => '_blank']
                                        ]
                                    ]
                            ]) !!}
                        </div>
                    </div>
                </div>
            </form>
        </div>
        <!-- /.card-body -->
    </div>
@endsection

        @section('scripts')
            <script>
                window.PageRoutes = {
                    // Add Routes
                };
            </script>

    @vite('modulos/Academico/Resources/js/pages/relatoriosatasfinais/index.js')
@stop