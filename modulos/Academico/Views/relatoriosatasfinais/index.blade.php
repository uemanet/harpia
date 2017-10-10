@extends('layouts.modulos.academico')

@section('stylesheets')
    <link rel="stylesheet" href="{{asset('/css/plugins/select2.css')}}">
@endsection

@section('title')
    Relatório de Atas Finais
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
            <form id="form" method="POST" action="{{ route('academico.relatoriosatasfinais.pdf') }}">
                {{ csrf_field() }}
                <div class="row">
                    <div class="col-md-4 @if ($errors->has('crs_id')) has-error @endif">
                        {!! Form::label('crs_id', 'Curso*') !!}
                        <div class="form-group">
                            {!! Form::select('crs_id', $cursos, Input::get('crs_id'), ['class' => 'form-control', 'placeholder' => 'Escolha o Curso']) !!}
                            @if ($errors->has('crs_id')) <p class="help-block">{{ $errors->first('crs_id') }}</p> @endif
                        </div>
                    </div>
                    <div class="col-md-4 @if ($errors->has('ofc_id')) has-error @endif">
                        {!! Form::label('ofc_id', 'Oferta de Curso*') !!}
                        <div class="form-group">
                            {!! Form::select('ofc_id', $ofertasCurso, Input::get('ofc_id'), ['class' => 'form-control']) !!}
                            @if ($errors->has('ofc_id')) <p class="help-block">{{ $errors->first('ofc_id') }}</p> @endif
                        </div>
                    </div>
                    <div class="col-md-4 @if ($errors->has('trm_id')) has-error @endif">
                        {!! Form::label('trm_id', 'Turma*') !!}
                        <div class="form-group">
                            {!! Form::select('trm_id', $turmas, Input::get('trm_id'), ['class' => 'form-control']) !!}
                            @if ($errors->has('trm_id')) <p class="help-block">{{ $errors->first('trm_id') }}</p> @endif
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-5 @if ($errors->has('pol_id')) has-error @endif">
                        {!! Form::label('pol_id', 'Polo') !!}
                        <div class="form-group">
                            {!! Form::select('pol_id', $polos, Input::get('pol_id'), ['class' => 'form-control', 'placeholder' => 'Selecione o polo']) !!}
                            @if ($errors->has('pol_id')) <p class="help-block">{{ $errors->first('pol_id') }}</p> @endif
                        </div>
                    </div>
                    <div class="col-md-5 @if ($errors->has('mat_situacao')) has-error @endif">
                        {!! Form::label('mat_situacao', 'Situação') !!}
                        <div class="form-group">
                            {!! Form::select('mat_situacao', $situacao, Input::get('mat_situacao'), ['class' => 'form-control']) !!}
                            @if ($errors->has('mat_situacao')) <p class="help-block">{{ $errors->first('mat_situacao') }}</p> @endif
                        </div>
                    </div>
                    <div class="col-md-2">
                        <label for="">&nbsp;</label>
                        <div class="form-group">
                            {!! ActionButton::grid([
                                    'type' => 'LINE',
                                    'buttons' => [
                                        [
                                        'classButton' => 'btn btn-danger pdfButton',
                                        'icon' => 'fa fa-file-pdf-o',
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
        <!-- /.box-body -->
    </div>
@endsection

@section('scripts')
    <script src="{{url('/')}}/js/plugins/select2.js"></script>

    <script type="text/javascript">
        $(function () {
            // select2
            $('select').select2();

            var ofertasCursoSelect = $('#ofc_id');
            var turmaSelect = $('#trm_id');
            var polosSelect = $('#pol_id');
            var routePdf = "{{ route('academico.relatoriosatasfinais.pdf') }}";
            var routeIndex = "{{ route('academico.relatoriosatasfinais.index') }}";
            var cursoSelect = $('#crs_id');
            var situacaoSelect = $('#mat_situacao');

            // evento change select de cursos
            $('#crs_id').change(function () {

                // limpando selects
                ofertasCursoSelect.empty();
                turmaSelect.empty();
                polosSelect.empty();

                // buscar as ofertas de curso de acordo com o curso escolhido
                var cursoId = $(this).val();

                if (!cursoId || cursoId == '') {
                    return false;
                }

                $.harpia.httpget("{{url('/')}}/academico/async/ofertascursos/findallbycurso/" + cursoId).done(function (response) {
                    if (!$.isEmptyObject(response)) {
                        ofertasCursoSelect.append("<option value=''>Selecione a oferta</option>");
                        $.each(response, function (key, obj) {
                            ofertasCursoSelect.append('<option value="' + obj.ofc_id + '">' + obj.ofc_ano + ' (' + obj.mdl_nome + ')</option>');
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
                polosSelect.empty();

                // buscar as turmas de acordo com a oferta de curso
                var ofertaCursoId = $(this).val();

                if (!ofertaCursoId || ofertaCursoId == '') {
                    return false;
                }

                $.harpia.httpget("{{url('/')}}/academico/async/turmas/findallbyofertacurso/" + ofertaCursoId).done(function (response) {
                    if (!$.isEmptyObject(response)) {
                        turmaSelect.append('<option value="">Selecione a turma</option>');
                        $.each(response, function (key, obj) {
                            turmaSelect.append('<option value="' + obj.trm_id + '">' + obj.trm_nome + '</option>');
                        });
                    } else {
                        turmaSelect.append('<option>Sem turmas disponíveis</option>');
                    }
                });

                // buscar polos
                $.harpia.httpget("{{url('/')}}/academico/async/polos/findallbyofertacurso/" + ofertaCursoId).done(function (response) {
                    if(!$.isEmptyObject(response)) {
                        polosSelect.append("<option value=''>Selecione um polo</option>");

                        $.each(response, function (key, obj) {
                            polosSelect.append("<option value='"+obj.pol_id+"'>"+obj.pol_nome+"</option>");
                        });
                    } else {
                        polosSelect.append("<option value=''>Sem polos cadastrados</option>");
                    }
                });
            });



            $(document).on('click', '#btnBuscar', function () {
                $('#form').attr('action', routeIndex).removeAttr('target', '_blank').submit();
            });

            $('#turmaId').attr('value', turmaSelect.val());
            $('#ofertaCursoId').attr('value', ofertasCursoSelect.val());
            $('#poloId').attr('value', polosSelect.val());
            $('#cursoId').attr('value', cursoSelect.val());
            $('#situacao').attr('value', situacaoSelect.val());

            $('#pturmaId').attr('value', turmaSelect.val());
            $('#pofertaCursoId').attr('value', ofertasCursoSelect.val());
            $('#ppoloId').attr('value', polosSelect.val());
            $('#pcursoId').attr('value', cursoSelect.val());
            $('#psituacao').attr('value', situacaoSelect.val());

            $(document).on('click', '.pdfButton', function (event) {
                $.harpia.showloading();
                $('#exportPdf').submit();
            });
        });
    </script>
@endsection
