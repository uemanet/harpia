@extends('layouts.modulos.academico')

@section('title', 'Matricular Aluno na Disciplina')

@section('stylesheets')
    <link rel="stylesheet" href="{{asset('/css/plugins/select2.css')}}">
@endsection

@section('content')

    @include('Geral::pessoas.includes.dadospessoais')

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
            <div class="row">
                <div class="form-group col-md-4">
                    {!! Form::label('crs_id', 'Curso*', ['class' => 'control-label']) !!}
                    <select id="crs_id" class="form-control">
                        @if($matriculas->count())
                            <option>Selecione o curso</option>
                            @foreach($matriculas as $matricula)
                                <option value="{{$matricula->crs_id}}" data-trm-id={{$matricula->trm_id}} data-mat-id={{$matricula->mat_id}}>{{$matricula->crs_nome}}</option>
                            @endforeach
                        @else
                            <option value="">Nenhuma matrícula disponível</option>
                        @endif
                    </select>
                </div>
                <div class="form-group col-md-2">
                    {!! Form::label('ofd_per_id', 'Período Letivo*', ['class' => 'control-label']) !!}
                    {!! Form::select('ofd_per_id', [], null, ['class' => 'form-control']) !!}
                </div>
                <div class="form-group col-md-1">
                    <label for="" class="control-label"></label>
                    <button class="btn btn-primary form-control" id="btnLocalizar"><i class="fa fa-search"></i></button>
                </div>
            </div>
        </div>
        <!-- /.box-body -->
    </div>

    <div class="tabela-ofertas"></div>

@stop

@section('scripts')
    <script src="{{asset('/js/plugins/select2.js')}}" type="text/javascript"></script>

    <script type="text/javascript">
        $(document).ready(function() {
            $("select").select2();

            var token = "{{csrf_token()}}";
            var alunoId = "{{$aluno->alu_id}}";

            $('#crs_id').change(function () {
                var turmaId = $(this).find('option:selected').attr('data-trm-id');
                var selectPeriodos = $('#ofd_per_id');

                if(turmaId) {
                    $.harpia.httpget("{{url('/')}}/academico/async/periodosletivos/findallbyturma/"+turmaId)
                        .done(function (response) {
                            selectPeriodos.empty();
                            if(!$.isEmptyObject(response))
                            {
                                selectPeriodos.append("<option value=''>Selecione um periodo</option>");
                                $.each(response, function (key, obj) {
                                    selectPeriodos.append("<option value='"+obj.per_id+"'>"+obj.per_nome+"</option>");
                                });
                            } else {
                                selectPeriodos.append("<option value=''>Sem períodos disponíveis</option>");
                            }
                        });
                }
            });

            // Botao de Localizar Disciplinas Ofertadas
            $('#btnLocalizar').click(function () {
                var turma = $('#crs_id option:selected').attr('data-trm-id');
                var periodo = $('#ofd_per_id').val();

                if(turma == '' || periodo == '') {
                    return false;
                }

                renderTable(turma, periodo, alunoId);
            });

            // evento para selecionar todos os checkboxes
            $('.tabela-ofertas').on('click', '#select_all',function(event) {
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
                var checkboxes = $('.tabela-ofertas table td input[type="checkbox"]');

                if(checkboxes.is(':checked')){
                    $(document).find('#confirmMatricula').removeClass('hidden');
                }else{
                    $(document).find('#confirmMatricula').addClass('hidden');
                }
            };

            $(document).on('click', '.tabela-ofertas table input[type="checkbox"]', hiddenButton);

            // evento do botão de confirmar a matricula na(s) disciplina(s)
            $('.tabela-ofertas').on('click', '#confirmMatricula', function (e) {

                var quant = $('.ofertas:checked').length;

                if(!(quant > 0)) {
                    return false;
                }

                var ofertasIds = new Array();
                var matriculaId = $('#crs_id option:selected').attr('data-mat-id');

                $('.ofertas:checked').each(function () {
                    ofertasIds.push($(this).val());
                });

                sendDisciplinas(matriculaId,ofertasIds);
            });

            var renderTable = function(turmaId, periodoId, alunoId) {
                $.harpia.httpget("{{ url('/')}}/academico/async/matriculasofertasdisciplinas/gettableofertasdisciplinas/"+alunoId+"/"+turmaId+"/"+periodoId)
                    .done(function(response) {
                        $('.tabela-ofertas').empty();
                        $('.tabela-ofertas').append(response);
                });
            };

            var sendDisciplinas = function (matriculaId, ofertasIds) {

                var dados = {
                    ofertas: ofertasIds,
                    mof_mat_id: matriculaId,
                    _token: token
                };

                $.harpia.showloading();

                var result = false;

                $.ajax({
                    type: 'POST',
                    url: '/academico/async/matriculasofertasdisciplinas/matricular',
                    data: dados,
                    success: function (data) {
                        $.harpia.hideloading();

                        toastr.success('Aluno matriculado com sucesso!', null, {progressBar: true});

                        var turma = $('#crs_id option:selected').attr('data-trm-id');
                        var periodo = $('#ofd_per_id').val();

                        renderTable(turma, periodo, alunoId);
                    },
                    error: function (xhr, textStatus, error) {
                        $.harpia.hideloading();

                        switch (xhr.status) {
                            case 400:
                                toastr.error(xhr.responseText.replace(/\"/g, ''), null, {progressBar: true});
                                break;
                            default:
                                toastr.error(xhr.responseText.replace(/\"/g, ''), null, {progressBar: true});

                                result = false;
                        }
                    }
                });
            };

        });
    </script>

@endsection