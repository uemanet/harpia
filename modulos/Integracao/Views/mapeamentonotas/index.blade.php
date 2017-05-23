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
                <form method="POST" action="{{ route('integracao.mapeamentonotas.index') }}">
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

    @if(isset($ofertas))
        <div class="row">
            <div class="col-md-12">
                <div class="box box-primary">
                    <div class="box-header with-border">
                        <h3 class="box-title">
                            Ofertas de Disciplinas
                            @if(isset($turma))
                                - {{ $turma->trm_nome }}
                            @endif
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
                        @if(!empty($ofertas))
                            <div id="accordion" class="box-group">
                                @foreach($ofertas as $oferta)
                                    <div class="panel box box-primary">
                                        <div class="box-header with-border">
                                            <div class="box-title">
                                                <a data-toggle="collapse" data-parent="#accordion" href="#collapse{{$loop->index}}">
                                                    {{ $oferta['per_nome'] }}
                                                </a>
                                            </div>
                                        </div>
                                        <div id="collapse{{$loop->index}}" class="panel-collapse collapse in">
                                            <div class="box-body">
                                                <div class="row">
                                                    <div class="col-md-12">
                                                        @if($oferta['ofertas']->count())
                                                            <table class="table table-bordered">
                                                            <thead>
                                                                <tr>
                                                                    <th width="1%">#</th>
                                                                    <th>Disciplina</th>
                                                                    <th width="10%">ID Nota 1</th>
                                                                    <th width="10%">ID Nota 2</th>
                                                                    <th width="10%">ID Nota 3</th>
                                                                    <th width="10%">ID Conceito</th>
                                                                    <th width="10%">ID Recuperação</th>
                                                                    <th width="10%">ID Final</th>
                                                                    <th width="5%"></th>
                                                                    <th></th>
                                                                    <th></th>
                                                                </tr>
                                                            </thead>
                                                            <tbody>
                                                                @foreach($oferta['ofertas'] as $key => $value)
                                                                    @php
                                                                        $numerica = '';
                                                                        $conceito = 'disabled';

                                                                        if ($value->mdc_tipo_avaliacao == 'conceitual') {
                                                                            $numerica = 'disabled';
                                                                            $conceito = '';
                                                                        }
                                                                    @endphp
                                                                    <tr>
                                                                        <td>{{ $value->ofd_id }}</td>
                                                                        <td>{{ $value->dis_nome }}</td>
                                                                        <td>
                                                                            <div class="form-group">
                                                                                <input type="text" class="form-control" value="{{ $value->min_id_nota_um }}" {{$numerica}}>
                                                                            </div>
                                                                        </td>
                                                                        <td>
                                                                            <div class="form-group">
                                                                                <input type="text" class="form-control" value="{{ $value->min_id_nota_dois }}" {{$numerica}}>
                                                                            </div>
                                                                        </td>
                                                                        <td>
                                                                            <div class="form-group">
                                                                                <input type="text" class="form-control" value="{{ $value->min_id_nota_tres }}" {{$numerica}}>
                                                                            </div>
                                                                        </td>
                                                                        <td>
                                                                            <div class="form-group">
                                                                                <input type="text" class="form-control" value="{{ $value->min_id_conceito }}" {{$conceito}}>
                                                                            </div>
                                                                        </td>
                                                                        <td>
                                                                            <div class="form-group">
                                                                                <input type="text" class="form-control" value="{{ $value->min_id_recuperacao }}" {{$numerica}}>
                                                                            </div>
                                                                        </td>
                                                                        <td>
                                                                            <div class="form-group">
                                                                                <input type="text" class="form-control" value="{{ $value->min_id_final }}" {{$numerica}}>
                                                                            </div>
                                                                        </td>
                                                                        <td style="text-align: center;">
                                                                            <button class="btn bg-olive">
                                                                                <i class="fa fa-save"></i>
                                                                            </button>
                                                                        </td>
                                                                        <td style="text-align: center;">
                                                                            <a href="#" class="btn btn-primary">
                                                                                <i class="fa fa-users"></i> Alunos
                                                                            </a>
                                                                        </td>
                                                                        <td style="text-align: center;">
                                                                            <a href="#" class="btn btn-success">
                                                                                <i class="fa fa-exchange"></i> Mapear notas
                                                                            </a>
                                                                        </td>
                                                                    </tr>
                                                                @endforeach
                                                            </tbody>
                                                        </table>
                                                        @else
                                                            <p>Não há ofertas para este período</p>
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <p>Não há ofertas de disciplinas para esta turma</p>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    @endif
@stop

@section('scripts')
    <script src="{{url('/')}}/js/plugins/select2.js"></script>

    <script>
        $(function () {
            $('select').select2();

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

        });
    </script>
@stop
