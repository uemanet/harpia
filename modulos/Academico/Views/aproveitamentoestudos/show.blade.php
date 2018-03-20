@extends('layouts.modulos.academico')

@section('title', 'Aproveitamento de Disciplinas')

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
                <div class="form-group col-md-4">
                    {!! Form::label('ofd_per_id', 'Período Letivo', ['class' => 'control-label']) !!}
                    {!! Form::select('ofd_per_id', [], null, ['class' => 'form-control']) !!}
                </div>
                <div class="form-group col-md-1">
                    <label for="" class="control-label"></label>
                    <button class="btn btn-primary form-control" id="btnLocalizar"><i class="fa fa-search"></i></button>
                </div>
            </div>
        </div>
        <!-- /.box-body -->


        <!-- Modal Alterar Situacao Matricula  -->
        <div class="modal fade" id="matricula-modal">

        </div>


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


            $(document).on("click", ".modalButton",function(event){

                event.preventDefault();

                var ofertaCursoId = $(this).attr('data-ofc-id');


                var matriculaId = $(document).find('option:selected').attr('data-mat-id');

                $.harpia.httpget("{{ url('/')}}/academico/async/aproveitamentoestudos/getmodal/"+ofertaCursoId + "/" + matriculaId)
                    .done(function(response) {
                        $('.modal').empty();
                        $('.modal').append(response);
                    });

                $('#matricula-modal').modal();
            });

            var renderModal = function(ofertaId) {

                $.harpia.httpget("{{ url('/')}}/academico/async/aproveitamentoestudos/getmodal/"+ofertaId)
                    .done(function(response) {
                        $('.modal').empty();
                        $('.modal').append(response);
                    });
            };

            // Botao de Localizar Disciplinas Ofertadas
            $('#btnLocalizar').on('click',function(){
                var turma = $('#crs_id option:selected').attr('data-trm-id');
                var periodo = $('#ofd_per_id').val();

                if(!turma ) {
                    return false;
                }

                if (periodo == '') {
                  periodo = null;
                }

                renderTable(turma, periodo, alunoId);
            });

            var renderTable = function(turmaId, periodoId, alunoId) {

              if (periodoId) {
                $.harpia.httpget("{{ url('/')}}/academico/async/aproveitamentoestudos/gettableofertasdisciplinas/"+alunoId+"/"+turmaId+"/"+periodoId)
                .done(function(response) {
                  $('.tabela-ofertas').empty();
                  $('.tabela-ofertas').append(response);
                });
              }else {
                $.harpia.httpget("{{ url('/')}}/academico/async/aproveitamentoestudos/gettableofertasdisciplinas/"+alunoId+"/"+turmaId)
                .done(function(response) {
                  $('.tabela-ofertas').empty();
                  $('.tabela-ofertas').append(response);
                });
              }
            };
        });
    </script>

@endsection
