@extends('layouts.modulos.academico')

@section('stylesheets')
    <link rel="stylesheet" href="{{asset('/css/plugins/select2.css')}}">
@endsection

@section('title')
    Matriculas em Lote
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
            <form method="GET" action="">
                <div class="row">
                    <div class="col-md-4">
                        {!! Form::label('crs_id', 'Curso*') !!}
                        <div class="form-group">
                            {!! Form::select('crs_id', $cursos, null, ['class' => 'form-control', 'placeholder' => 'Escolha o Curso']) !!}
                        </div>
                    </div>
                    <div class="col-md-4">
                        {!! Form::label('ofc_id', 'Oferta de Curso*') !!}
                        <div class="form-group">
                            {!! Form::select('ofc_id', [], null, ['class' => 'form-control']) !!}
                        </div>
                    </div>
                    <div class="col-md-4">
                        {!! Form::label('trm_id', 'Turma*') !!}
                        <div class="form-group">
                            {!! Form::select('trm_id', [], null, ['class' => 'form-control']) !!}
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-4">
                        {!! Form::label('per_id', 'Período Letivo*') !!}
                        <div class="form-group">
                            {!! Form::select('per_id', [], null, ['class' => 'form-control']) !!}
                        </div>
                    </div>
                    <div class="col-md-4">
                        {!! Form::label('ofd_id', 'Disciplinas Ofertadas*') !!}
                        <div class="form-group">
                            {!! Form::select('ofd_id', [], null, ['class' => 'form-control']) !!}
                        </div>
                    </div>
                    <div class="col-md-1">
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
@endsection

@section('scripts')
    <script src="{{url('/')}}/js/plugins/select2.js"></script>

    <script type="text/javascript">
        $(function() {
            // select2
            $('select').select2();

            var ofertasCursoSelect = $('#ofc_id');
            var turmaSelect = $('#trm_id');
            var periodosLetivosSelect = $('#per_id');
            var disciplinasOfertadasSelect = $('#ofd_id');
            var btnBuscar = $('#btnBuscar');

            // evento change select de cursos
            $('#crs_id').change(function () {

                // limpando selects
                ofertasCursoSelect.empty();
                turmaSelect.empty();
                periodosLetivosSelect.empty();
                disciplinasOfertadasSelect.empty();

                // buscar as ofertas de curso de acordo com o curso escolhido
                var cursoId = $(this).val();

                if(!cursoId || cursoId == '') {
                    return false;
                }

                $.harpia.httpget("{{url('/')}}/academico/async/ofertascursos/findallbycurso/" + cursoId).done(function (response) {
                    if(!$.isEmptyObject(response)) {
                        ofertasCursoSelect.append("<option value=''>Selecione a oferta</option>");
                        $.each(response, function (key, obj) {
                            ofertasCursoSelect.append('<option value="'+obj.ofc_id+'">'+obj.ofc_ano+'</option>');
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
                periodosLetivosSelect.empty();
                disciplinasOfertadasSelect.empty();

                // buscar as turmas de acordo com a oferta de curso
                var ofertaCursoId = $(this).val();

                if(!ofertaCursoId || ofertaCursoId == '') {
                    return false;
                }

                $.harpia.httpget("{{url('/')}}/academico/async/turmas/findallbyofertacurso/" + ofertaCursoId).done(function (response) {
                    if(!$.isEmptyObject(response)) {
                        turmaSelect.append('<option value="">Selecione a Turma</option>');
                        $.each(response, function (key, obj) {
                            turmaSelect.append('<option value="'+obj.trm_id+'">'+obj.trm_nome+'</option>');
                        });
                    } else {
                        turmaSelect.append('<option>Sem turmas disponíveis</option>');
                    }
                });
            });

            // evento change select de turmas
            turmaSelect.change(function () {

                // limpando selects
                periodosLetivosSelect.empty();
                disciplinasOfertadasSelect.empty();

                // buscar os periodos letivos de acordo com a turma escolhida
                var turmaId = $(this).val();

                if(!turmaId || turmaId == '') {
                    return false;
                }

                $.harpia.httpget("{{url('/')}}/academico/async/periodosletivos/findallbyturma/" + turmaId).done(function (response) {
                    if(!$.isEmptyObject(response)) {
                        periodosLetivosSelect.append('<option value="">Selecione o periodo letivo</option>');
                        $.each(response, function (key, obj) {
                            periodosLetivosSelect.append('<option value="'+obj.per_id+'">'+obj.per_nome+'</option>');
                        });
                    } else {
                        periodosLetivosSelect.append('<option>Sem periodos letivos disponiveis</option>');
                    }
                });
            });

            //evento change select de periodos letivos
            periodosLetivosSelect.change(function () {

                // limpando select
                disciplinasOfertadasSelect.empty();

                // buscar todas as disciplinas ofertadas de acordo com o periodo e a turma
                var turmaId = turmaSelect.val();
                var periodoLetivoId = $(this).val();

                if((!turmaId || turmaId == '') || (!periodoLetivoId || periodoLetivoId == '')) {
                    return false;
                }

                $.harpia.httpget("{{url('/')}}/academico/async/ofertasdisciplinas/findall?ofd_trm_id=" + turmaId + "&ofd_per_id=" + periodoLetivoId).done(function (response) {
                    if(!$.isEmptyObject(response)) {
                        disciplinasOfertadasSelect.append('<option value="">Selecione a disciplina ofertada</option>');
                        $.each(response, function (key, obj) {
                            disciplinasOfertadasSelect.append('<option value="'+obj.ofd_id+'">'+obj.dis_nome+'</option>');
                        });
                    } else {
                        disciplinasOfertadasSelect.append('<option>Sem disciplinas ofertadas disponíveis</option>');
                    }
                })
            });

            // evento do botao Buscar
            btnBuscar.click(function (event) {

                // parar o evento de submissao do formaulario
                event.preventDefault();

                var turmaId = turmaSelect.val();
                var disciplinaOfertadaId = disciplinasOfertadasSelect.val();
                var cursoId = $('#crs_id').val();

                if((!turmaId || turmaId == '') || (!disciplinaOfertadaId || disciplinaOfertadaId == '') || (!cursoId || cursoId == '')) {
                    return false;
                }

                $.harpia.httpget("{{url('/')}}/academico/async/matriculasofertasdisciplinas/getalunosmatriculaslote/"+cursoId+"/"+turmaId+"/" + disciplinaOfertadaId).done(function (response) {
                    console.log(response);
                });

            });
        });
    </script>
@stop
