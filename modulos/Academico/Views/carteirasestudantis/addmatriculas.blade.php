@extends('layouts.modulos.academico')

@section('stylesheets')
    <link rel="stylesheet" href="{{asset('/css/plugins/select2.css')}}">
@endsection

@section('title')
    Adicionar Matriculas
@endsection

@section('subtitle')
    {{$lista->lst_nome}} - {{$lista->lst_descricao}}
@stop

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
                    <input type="hidden" name="lst_id" id="lst_id" value="{{$lista->lst_id}}">
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
                    <div class="col-md-2">
                        {!! Form::label('trm_id', 'Turma*') !!}
                        <div class="form-group">
                            {!! Form::select('trm_id', [], null, ['class' => 'form-control']) !!}
                        </div>
                    </div>
                    <div class="col-md-3">
                        {!! Form::label('pol_id', 'Polo*') !!}
                        <div class="form-group">
                            {!! Form::select('pol_id', [], null, ['class' => 'form-control']) !!}
                        </div>
                    </div>
                    <div class="col-md-1">
                        <label for="">&nbsp;</label>
                        <div class="form-group">
                            <button type="submit" id="btnBuscar" class="btn btn-primary form-control">
                                <i class="fa fa-search"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
        <!-- /.box-body -->
    </div>

    <div class="listas"></div>
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
            var poloSelect = $('#pol_id');
            var btnBuscar = $('#btnBuscar');

            // token
            var token = "{{csrf_token()}}";

            // evento change select de cursos
            cursoSelect.change(function () {

                // limpando selects
                ofertasCursoSelect.empty();
                turmaSelect.empty();
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

            // evento do botao Buscar
            btnBuscar.click(function (event) {

                // parar o evento de submissao do formulario
                event.preventDefault();

                var turmaId = turmaSelect.val();
                var poloId = poloSelect.val();

                if((!turmaId || turmaId == '') || (!poloId || poloId == '')) {
                    return false;
                }

                var parameters = {
                    lst_id: $('#lst_id').val(),
                    mat_trm_id: turmaId,
                    mat_pol_id: poloId
                };

                renderTable(parameters);

            });

            var renderTable = function(parameters) {
                $('.listas').empty();

                var url = "{{url('/')}}/academico/async/carteirasestudantis/gettableaddmatriculas?" + $.param(parameters);

                $.harpia.httpget(url).done(function (response) {
                    if(!$.isEmptyObject(response)) {
                        $('.listas').append(response);
                    } else {
                        $('.listas').append("<p>Não há alunos matriculados na turma/polo</p>");
                    }
                });
            };

            // evento para selecionar todos os checkboxes
            $('.listas').on('click', '#select_all',function(event) {
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
                var checkboxes = $('.listas table td input[type="checkbox"]');

                if(checkboxes.is(':checked')){
                    $(document).find('.btnIncluir').removeClass('hidden');
                }else{
                    $(document).find('.btnIncluir').addClass('hidden');
                }
            };

            $(document).on('click', '.listas table input[type="checkbox"]', hiddenButton);

            $('.listas').on('click', '.btnIncluir', function () {
                var quant = $('.matriculas:checked').length;

                var listaId = $('#lst_id').val();

                if((!(quant > 0)) || (!listaId || listaId == '')) {
                    return false;
                }

                var matriculasIds = new Array();

                $('.matriculas:checked').each(function () {
                    matriculasIds.push($(this).val());
                });

                sendMatriculas(matriculasIds, listaId);
            });

            var sendMatriculas = function(matriculasIds, listaId) {

                var dados = {
                    matriculas: matriculasIds,
                    lst_id: listaId,
                    _token: token
                };

                $.harpia.showloading();

                $.ajax({
                    type: 'POST',
                    url: '/academico/async/carteirasestudantis/incluirmatriculas',
                    data: dados,
                    success: function (data) {
                        $.harpia.hideloading();

                        toastr.success('Matrículas incluídas na lista com sucesso.', null, {progressBar: true});

                        var turmaId = turmaSelect.val();
                        var poloId = poloSelect.val();

                        var parameters = {
                            lst_id: $('#lst_id').val(),
                            mat_trm_id: turmaId,
                            mat_pol_id: poloId
                        };

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
