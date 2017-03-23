@extends('layouts.modulos.academico')

@section('stylesheets')
    <link rel="stylesheet" href="{{asset('/css/plugins/select2.css')}}">
@endsection

@section('title')
    Relatório de Alunos por Curso
@endsection

@section('content')
    <div class="box box-primary">
        <div class="box-header with-border">
            <h3 class="box-title"><i class="fa fa-filter"></i> Filtrar dados</h3>

            <div class="box-tools pull-right">
                <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                </button>
            </div>
            <!-- /.box-tools -->
        </div>
        <!-- /.box-header -->
        <div class="box-body">
            <form id="form" method="GET" action="">
                <div class="row">
                    <div class="col-md-3 @if ($errors->has('crs_id')) has-error @endif">
                        {!! Form::label('crs_id', 'Curso*') !!}
                        <div class="form-group">
                            {!! Form::select('crs_id', $cursos, Input::get('crs_id'), ['class' => 'form-control', 'placeholder' => 'Escolha o Curso']) !!}
                            @if ($errors->has('crs_id')) <p class="help-block">{{ $errors->first('crs_id') }}</p> @endif
                        </div>
                    </div>
                    <div class="col-md-2 @if ($errors->has('ofc_id')) has-error @endif">
                        {!! Form::label('ofc_id', 'Oferta de Curso*') !!}
                        <div class="form-group">
                            {!! Form::select('ofc_id', $ofertasCurso, Input::get('ofc_id'), ['class' => 'form-control']) !!}
                            @if ($errors->has('ofc_id')) <p class="help-block">{{ $errors->first('ofc_id') }}</p> @endif
                        </div>
                    </div>
                    <div class="col-md-3 @if ($errors->has('trm_id')) has-error @endif">
                        {!! Form::label('trm_id', 'Turma*') !!}
                        <div class="form-group">
                            {!! Form::select('trm_id', $turmas, Input::get('trm_id'), ['class' => 'form-control']) !!}
                            @if ($errors->has('trm_id')) <p class="help-block">{{ $errors->first('trm_id') }}</p> @endif
                        </div>
                    </div>
                    <div class="col-md-2 @if ($errors->has('mat_situacao')) has-error @endif">
                        {!! Form::label('mat_situacao', 'Situação da matricula') !!}
                        <div class="form-group">
                            {!! Form::select('mat_situacao', ["cursando" => "Cursando",
                                "concluido" => "Concluído",
                                "reprovado" => "Reprovado",
                                "evadido" => "Evadido",
                                "trancado" => "Trancado",
                                "desistente" => "Desistente"
                            ], Input::get('mat_situacao'), ['class' => 'form-control', 'placeholder' => 'Selecione o status']) !!}
                        </div>
                    </div>
                    <div class="col-md-2">
                        <label for="">&nbsp;</label>
                        <div class="form-group">
                            <input type="submit" id="btnBuscar" class="form-control btn-primary" value="Buscar">
                        </div>
                    </div>
                </div>
            </form>
        </div>
        <!-- /.box-body -->
    </div>

    @if(!is_null($tabela))
        <div class="box box-primary">
            <div class="box-header">
                <div class="pull-right box-tools">
                    <button id="formPdf" type="button" class="btn btn-success" >
                        <i class="fa fa-file-pdf-o"></i> Exportar para PDF</button>
                </div>
            </div>
            <div class="box-body">
                {!! $tabela->render() !!}
            </div>
        </div>

        <div class="text-center">{!! $paginacao->links() !!}</div>

    @else
        <div id="boxInfo" class="box box-primary ">
            <div class="box-body">Sem registros para apresentar</div>
        </div>
    @endif
@endsection

@section('scripts')
    <script src="{{url('/')}}/js/plugins/select2.js"></script>

    <script type="text/javascript">
        $(function() {
            // select2
            $('select').select2();

            var ofertasCursoSelect = $('#ofc_id');
            var turmaSelect = $('#trm_id');
            var routePdf = "{{ route('academico.relatoriosmatriculascurso.pdf') }}";
            var routeIndex = "{{ route('academico.relatoriosmatriculascurso.index') }}";

            // evento change select de cursos
            $('#crs_id').change(function () {

                // limpando selects
                ofertasCursoSelect.empty();
                turmaSelect.empty();

                // buscar as ofertas de curso de acordo com o curso escolhido
                var cursoId = $(this).val();

                if(!cursoId || cursoId == '') {
                    return false;
                }

                $.harpia.httpget("{{url('/')}}/academico/async/ofertascursos/findallbycurso/" + cursoId).done(function (response) {
                    if(!$.isEmptyObject(response)) {
                        ofertasCursoSelect.append("<option value=''>Selecione a oferta</option>");
                        $.each(response, function (key, obj) {
                            ofertasCursoSelect.append('<option value="'+obj.ofc_id+'">'+obj.ofc_ano+' ('+obj.mdl_nome+')</option>');
                        });
                    } else {
                        ofertasCursoSelect.append("<option>Sem ofertas disponiveis</option>");
                    }
                });
            });

            // evento change select de ofertas de curso
            ofertasCursoSelect.change(function () {

                //limpando selects
                turmaSelect.empty();

                // buscar as turmas de acordo com a oferta de curso
                var ofertaCursoId = $(this).val();

                if(!ofertaCursoId || ofertaCursoId == '') {
                    return false;
                }

                $.harpia.httpget("{{url('/')}}/academico/async/turmas/findallbyofertacurso/" + ofertaCursoId).done(function (response) {
                    if(!$.isEmptyObject(response)) {
                        turmaSelect.append('<option value="">Selecione a turma</option>');
                        $.each(response, function (key, obj) {
                            turmaSelect.append('<option value="'+obj.trm_id+'">'+obj.trm_nome+'</option>');
                        });
                    } else {
                        turmaSelect.append('<option>Sem turmas disponíveis</option>');
                    }
                });
            });

            $(document).on('click', '#btnBuscar', function () {
                $('#form').attr('action', routeIndex).removeAttr('target', '_blank').submit();
            });

            $(document).on('click', '#formPdf', function () {
                $('#form').attr('action', routePdf).attr('target', '_blank').submit();
            });
        });
    </script>
@endsection