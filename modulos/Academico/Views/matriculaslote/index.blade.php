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
                    <div class="col-md-3">
                        {!! Form::label('crs_id', 'Curso*') !!}
                        <div class="form-group">
                            {!! Form::select('crs_id', $cursos, null, ['class' => 'form-control', 'placeholder' => 'Escolha o Curso']) !!}
                        </div>
                    </div>
                    <div class="col-md-3">
                        {!! Form::label('ofc_id', 'Oferta de Curso*') !!}
                        <div class="form-group">
                            {!! Form::select('ofc_id', [], null, ['class' => 'form-control']) !!}
                        </div>
                    </div>
                    <div class="col-md-3">
                        {!! Form::label('trm_id', 'Turma*') !!}
                        <div class="form-group">
                            {!! Form::select('trm_id', [], null, ['class' => 'form-control']) !!}
                        </div>
                    </div>
                    <div class="col-md-3">
                        {!! Form::label('per_id', 'Período Letivo*') !!}
                        <div class="form-group">
                            {!! Form::select('per_id', [], null, ['class' => 'form-control']) !!}
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-3">
                        {!! Form::label('ofd_id', 'Disciplinas Ofertadas*') !!}
                        <div class="form-group">
                            {!! Form::select('ofd_id', [], null, ['class' => 'form-control']) !!}
                        </div>
                    </div>
                    <div class="col-md-3">
                        {!! Form::label('pol_id', 'Polo') !!}
                        <div class="form-group">
                            {!! Form::select('pol_id', [], null, ['class' => 'form-control']) !!}
                        </div>
                    </div>
                    <div class="col-md-3">
                        <label for="">&nbsp;</label>
                        <div class="form-group">
                            <input type="submit" id="btnBuscar" class="form-control btn btn-primary" value="Buscar">
                        </div>
                    </div>
                </div>
            </form>
        </div>
        <!-- /.box-body -->
    </div>

    <div class="alunos"></div>
@endsection

@section('scripts')
    <script src="{{url('/')}}/js/plugins/select2.js"></script>

    <script type="text/javascript">
        $(function() {
            // select2
            $('select').select2();

            var cursoSelect = $('#crs_id');
            var ofertasCursoSelect = $('#ofc_id');
            var turmaSelect = $('#trm_id');
            var periodosLetivosSelect = $('#per_id');
            var disciplinasOfertadasSelect = $('#ofd_id');
            var poloSelect = $('#pol_id');
            var btnBuscar = $('#btnBuscar');

            // token
            var token = "{{csrf_token()}}";

            // evento change select de cursos
            cursoSelect.change(function () {

                // limpando selects
                ofertasCursoSelect.empty();
                turmaSelect.empty();
                periodosLetivosSelect.empty();
                disciplinasOfertadasSelect.empty();
                poloSelect.empty();

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
                periodosLetivosSelect.empty();
                disciplinasOfertadasSelect.empty();
                poloSelect.empty();

                var ofertaCursoId = $(this).val();

                if(!ofertaCursoId || ofertaCursoId == '') {
                    return false;
                }

                // buscar as turmas de acordo com a oferta de curso
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

                // busca os polos de acordo com a oferta
                $.harpia.httpget("{{url('/')}}/academico/async/polos/findallbyofertacurso/" + ofertaCursoId)
                    .done(function (response) {
                        if(!$.isEmptyObject(response)) {
                            poloSelect.append('<option value="">Selecione o polo</option>');
                            $.each(response, function (key, obj) {
                                poloSelect.append('<option value="'+obj.pol_id+'">'+obj.pol_nome+'</option>');
                            });
                        } else {
                            poloSelect.append('<option>Sem polos disponíveis</option>');
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
                var ofertaDisciplinaId = disciplinasOfertadasSelect.val();
                var poloId = poloSelect.val();

                if((!turmaId || turmaId == '') || (!ofertaDisciplinaId || ofertaDisciplinaId == '')) {
                    return false;
                }

                var parameters = {
                    trm_id: turmaId,
                    ofd_id: ofertaDisciplinaId
                };

                if (poloId && poloId != '') {
                    parameters['pol_id'] = poloId;
                }

                renderTable(parameters);

            });

            var renderTable = function(parameters) {
                $('.alunos').empty();

                var url = "{{url('/')}}/academico/async/matriculasofertasdisciplinas/getalunosmatriculaslote?" + $.param(parameters);

                $.harpia.httpget(url).done(function (response) {
                    if(!$.isEmptyObject(response)) {
                        $('.alunos').append(response);
                    } else {
                        $('.alunos').append("<p>Não há alunos matriculados na turma/polo</p>");
                    }
                });
            };

            // evento para selecionar todos os checkboxes
            $('.alunos').on('click', '#select_all',function(event) {
                if(this.checked) {
                    $(':checkbox').each(function() {
                        this.checked = true;
                    });
                }
                else {
                    $(':checkbox').each(function() {
                        this.checked = false;
                    });
                }
            });

            var hiddenButton = function () {
                var checkboxes = $('.alunos table td input[type="checkbox"]');

                if(checkboxes.is(':checked')){
                    $(document).find('.btnMatricular').removeClass('hidden');
                }else{
                    $(document).find('.btnMatricular').addClass('hidden');
                }
            };

            $(document).on('click', '.alunos table input[type="checkbox"]', hiddenButton);

            $('.alunos').on('click', '.btnMatricular', function () {
                var quant = $('.matriculas:checked').length;

                var ofertaId = $('#ofd_id').val();

                if((!(quant > 0)) || (!ofertaId || ofertaId == '')) {
                    return false;
                }

                var matriculasIds = new Array();

                $('.matriculas:checked').each(function () {
                    matriculasIds.push($(this).val());
                });

                sendMatriculas(matriculasIds, ofertaId);
            });

            var sendMatriculas = function(matriculasIds, ofertaId) {

                var dados = {
                    matriculas: matriculasIds,
                    ofd_id: ofertaId,
                    _token: token
                };

                $.harpia.showloading();

                $.ajax({
                    type: 'POST',
                    url: '/academico/async/matriculasofertasdisciplinas/matriculaslote',
                    data: dados,
                    success: function (data) {
                        $.harpia.hideloading();

                        toastr.success('Alunos matriculados com sucesso!', null, {progressBar: true});

                        var turma = turmaSelect.val();
                        var ofertaDisciplina = disciplinasOfertadasSelect.val();
                        var polo = poloSelect.val();

                        var parameters = {
                            trm_id: turma,
                            ofd_id: ofertaDisciplina
                        };

                        if (polo && polo != '') {
                            parameters['pol_id'] = polo;
                        }

                        renderTable(parameters);
                    },
                    error: function (xhr, textStatus, error) {
                        $.harpia.hideloading();

                        switch (xhr.status) {
                            case 400:
                                toastr.error(xhr.responseText.replace(/\"/g, ''), null, {progressBar: true});
                                break;
                            default:
                                toastr.error(xhr.responseText.replace(/\"/g, ''), null, {progressBar: true});
                        }
                    }
                });
            };
        });
    </script>
@stop
