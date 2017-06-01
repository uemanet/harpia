@extends('layouts.modulos.integracao')

@section('title')
    Mapeamento de Notas
@stop

@section('stylesheets')
    <link rel="stylesheet" href="{{url('/')}}/css/plugins/select2.css">
@stop

@section('content')
    <div class="box box-primary">
        <div class="box-header with-border">
            <h3 class="box-title">
                <i class="fa fa-filter"></i> Filtrar Dados
            </h3>
            <!-- /.box-title -->
            <div class="box-tools pull-right">
                <button type="button" class="btn btn-box-tool" data-widget="collapse">
                    <i class="fa fa-minus"></i>
                </button>
            </div>
            <!-- /.box-tools -->
        </div>
        <!-- /.box-header -->
        <div class="box-body">
            <div class="row">
                <form method="POST" action="">
                    {{ csrf_field() }}
                    <div class="col-md-3 form-group @if($errors->has('crs_id'))has-error @endif">
                        {!! Form::label('crs_id', 'Curso*') !!}
                        <div class="controls">
                            {!! Form::select('crs_id', $cursos, '', ['class' => 'form-control',
                            'placeholder' => 'Escolha o curso']) !!}
                            @if ($errors->has('crs_id')) <p class="help-block">{{ $errors->first('crs_id') }}</p> @endif
                        </div>
                    </div>
                    <div class="col-md-3 form-group @if($errors->has('ofc_id'))has-error @endif">
                        {!! Form::label('ofc_id', 'Oferta de Curso*') !!}
                        <div class="controls">
                            {!! Form::select('ofc_id', [], '', ['class' => 'form-control']) !!}
                            @if ($errors->has('ofc_id')) <p class="help-block">{{ $errors->first('ofc_id') }}</p> @endif
                        </div>
                    </div>
                    <div class="col-md-3 form-group @if($errors->has('trm_id'))has-error @endif">
                        {!! Form::label('trm_id', 'Turma*') !!}
                        <div class="controls">
                            {!! Form::select('trm_id', [], '', ['class' => 'form-control']) !!}
                            @if ($errors->has('trm_id')) <p class="help-block">{{ $errors->first('trm_id') }}</p> @endif
                        </div>
                    </div>
                    <div class="col-md-3">
                        {!! Form::label('btn', '&nbsp;') !!}
                        <div class="form-group">
                            <input type="submit" id="btnBuscar" class="form-control btn-primary" value="Buscar">
                        </div>
                    </div>
                </form>
            </div>
        </div>
        <!-- /.box-body -->
    </div>
    <!-- /.box-primary -->

    <div id="disciplinas"></div>
@stop

@section('scripts')
    <script src="{{url('/')}}/js/plugins/select2.js"></script>

    <script>
        $(function () {
            $('select').select2();

            var token = "{{ csrf_token() }}";

            var cursosSelect = $('#crs_id');
            var ofertasCursoSelect = $('#ofc_id');
            var turmaSelect = $('#trm_id');

            // evento change do select de cursos
            cursosSelect.change(function () {
                // limpando selects
                ofertasCursoSelect.empty();
                turmaSelect.empty();

                var cursoId = $(this).val();

                if(!cursoId) {
                    return false;
                }

                // faz a consulta pra trazer todas as ofertas de curso
                $.harpia.httpget('{{url("/")}}/academico/async/ofertascursos/findallbycurso/' + cursoId).done(function (response) {
                    if(!$.isEmptyObject(response)) {
                        ofertasCursoSelect.append("<option value=''>Selecione uma oferta</option>");

                        $.each(response, function (key, obj) {
                            ofertasCursoSelect.append("<option value='"+obj.ofc_id+"'>"+obj.ofc_ano+" ("+obj.mdl_nome+")</option>");
                        });
                    } else {
                        ofertasCursoSelect.append("<option value=''>Sem ofertas cadastradas</option>");
                    }
                });
            });

            // evento change do select de ofertas de curso
            ofertasCursoSelect.change(function () {
                // limpando selects
                turmaSelect.empty();

                // faz a consulta pra trazer todas as turmas da oferta de curso escolhida
                var ofertaCursoId = $(this).val();

                if(!ofertaCursoId) {
                    return false;
                }

                // buscar turmas
                $.harpia.httpget("{{url('/')}}/academico/async/turmas/findallbyofertacurso/" + ofertaCursoId).done(function (response) {
                    if(!$.isEmptyObject(response)) {
                        turmaSelect.append("<option value=''>Selecione uma turma</option>");

                        $.each(response, function (key, obj) {
                            turmaSelect.append("<option value='"+obj.trm_id+"'>"+obj.trm_nome+"</option>");
                        });
                    } else {
                        turmaSelect.append("<option value=''>Sem turmas cadastradas</option>");
                    }
                });

            });

            $('#btnBuscar').click(function (e) {
                e.preventDefault();

                var curso = $('#crs_id').val();
                var ofertaCurso = $('#ofc_id').val();
                var turma = $('#trm_id').val();

                if (curso == '' || ofertaCurso == '' || turma == '') {
                    return false;
                }

                var dados = {
                    _token: token,
                    crs_id: curso,
                    ofc_id: ofertaCurso,
                    trm_id: turma
                };

                $.ajax({
                    type: 'POST',
                    url: "{{ route('integracao.mapeamentonotas.index') }}",
                    data: dados,
                    success: function (response) {
                        $('#disciplinas').empty();
                        $('#disciplinas').append(response.html);
                    }
                });
            });

            $('#disciplinas').on('click', '.btnSalvar', function(event) {
                var ofd_id = $(event.currentTarget).data('id');

                var nota1 = $('#'+ofd_id+'_nota1').val();
                var nota2 = $('#'+ofd_id+'_nota2').val();
                var nota3 = $('#'+ofd_id+'_nota3').val();
                var conceito = $('#'+ofd_id+'_conceito').val();
                var recuperacao = $('#'+ofd_id+'_recuperacao').val();
                var final = $('#'+ofd_id+'_final').val();

                if (nota1 != '' || nota2 != '' || nota3 != '' || conceito != '' || recuperacao != '' || final != '') {

                    var dados = {
                        _token: token,
                        data: JSON.stringify({
                            'min_ofd_id': ofd_id,
                            'min_id_nota1': nota1,
                            'min_id_nota2': nota2,
                            'min_id_nota3': nota3,
                            'min_id_conceito': conceito,
                            'min_id_recuperacao': recuperacao,
                            'min_id_final': final
                        })
                    };

                    $.harpia.showloading();

                    $.ajax({
                        type: 'POST',
                        url: "/integracao/async/mapeamentonotas/setmapeamentonotas",
                        data: dados,
                        success: function (response) {
                            $.harpia.hideloading();

                            var msg = response.msg;

                            toastr.success(msg, null, {progressBar: true});

                            $(event.currentTarget).prop('disabled', true);
                        },
                        error: function (response) {
                            $.harpia.hideloading();

                            var msg = response.responseJSON.error;

                            toastr.error(msg, null, {progressBar: true});
                        }
                    });
                }
            });

            $('#disciplinas').on('click', '.btnMapear', function (event) {
                var ofd_id = $(event.currentTarget).data('id');

                $.harpia.showloading();

                $.ajax({
                    type: 'GET',
                    url: "/integracao/async/mapeamentonotas/"+ofd_id+"/mapearnotasalunos",
                    success: function (response) {
                        $.harpia.hideloading();

                        var msg = response.msg;

                        toastr.success(msg, null, {progressBar: true});
                    },
                    error: function (response) {
                        $.harpia.hideloading();

                        var msg = response.responseJSON.error;

                        toastr.error(msg, null, {progressBar: true});
                    }
                });
            });

        });
    </script>
@stop
