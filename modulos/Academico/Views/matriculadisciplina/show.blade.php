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
                <div class="form-group col-md-3">
                    {!! Form::label('crs_id', 'Curso*', ['class' => 'control-label']) !!}
                    <select id="crs_id" class="form-control">
                        <option>Selecione o curso</option>
                        @foreach($matriculas as $matricula)
                            <option value="{{$matricula->crs_id}}" data-trm-id={{$matricula->trm_id}}>{{$matricula->crs_nome}}</option>
                            @endforeach
                    </select>
                </div>
                <div class="form-group col-md-2">
                    {!! Form::label('ofd_per_id', 'Período Letivo*', ['class' => 'control-label']) !!}
                    {!! Form::select('ofd_per_id', $periodoletivo, null, ['class' => 'form-control', 'placeholder' => 'Escolha o periodo']) !!}
                </div>
                <div class="form-group col-md-1">
                    <label for="" class="control-label"></label>
                    <button class="btn btn-primary form-control" id="btnLocalizar"><i class="fa fa-search"></i></button>
                </div>
            </div>
        </div>
        <!-- /.box-body -->
    </div>

    <div class="box box-primary hidden" id="boxDisciplinas">
        <div class="box-header with-border">
            <h3 class="box-title">Disciplinas Ofertadas</h3>

            <div class="box-tools pull-right">
                <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                </button>
            </div>
            <!-- /.box-tools -->
        </div>
        <!-- /.box-header -->
        <div class="box-body">
            <div class="row">
                <div class="col-md-12 conteudo"></div>
            </div>
        </div>
        <!-- /.box-body -->
    </div>

@stop

@section('scripts')
    <script src="{{asset('/js/plugins/select2.js')}}" type="text/javascript"></script>

    <script type="text/javascript">
        $(document).ready(function() {
            $("select").select2();

            var boxDisciplinas = $('#boxDisciplinas');
            var boxFormDisciplinas = $('#formDisciplinas');
            boxFormDisciplinas.hide();

            // Botao de Localizar Disciplinas Ofertadas
            $('#btnLocalizar').click(function () {
                var turma = $('#crs_id option:selected').attr('data-trm-id');
                var periodo = $('#ofd_per_id').val();

                if(turma == '' || periodo == '') {
                    return false;
                }

                localizarDisciplinasOfertadas(turma, periodo);
            });

            $(document).on('click', '#select_all',function(event) {
                if(this.checked) {
                    // Iterate each checkbox
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


            var localizarDisciplinasOfertadas = function (turmaId, periodoId) {
                $.harpia.httpget("{{url('/')}}/academico/async/ofertasdisciplinas/findall?ofd_trm_id=" + turmaId + "&ofd_per_id=" + periodoId)
                        .done(function (data) {
                            boxDisciplinas.removeClass('hidden');
                            boxFormDisciplinas.show();

                            boxDisciplinas.find('.conteudo').empty();
                            if(!$.isEmptyObject(data)) {

                                var table = '';
                                table += "<table class='table table-bordered'>";
                                table += '<tr>';
                                table += '<th><label><input id="select_all" type="checkbox"></label></th>';
                                table += "<th>Disciplina</th>";
                                table += "<th>Carga Horária</th>";
                                table += "<th>Créditos</th>";
                                table += "<th>Vagas</th>";
                                table += "<th>Professor</th>";
                                table += '</tr>';

                                $.each(data, function (key, obj) {
                                    table += '<tr>';
                                    table += "<td><label><input type='checkbox' value='"+obj.ofd_id+"'></label></td>"
                                    table += "<td>"+obj.dis_nome+"</td>";
                                    table += "<td>"+obj.dis_carga_horaria+"</td>";
                                    table += "<td>"+obj.dis_creditos+"</td>";
                                    table += "<td>"+obj.ofd_qtd_vagas+"</td>";
                                    table += "<td>"+obj.pes_nome+"</td>";
                                    table += '</tr>';
                                });

                                table += "</table>";
                                boxDisciplinas.find('.conteudo').append(table);
                            } else {
                                boxDisciplinas.find('.conteudo').append('<p>O periodo letivo não possui disciplinas ofertadas</p>');
                            }
                        });
            }
        });
    </script>


@endsection