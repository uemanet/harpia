@extends('layouts.modulos.academico')

@section('title')
    Certificação
@stop

@section('subtitle')
    Gerenciamento de registros
@stop

@section('stylesheets')
    <link rel="stylesheet" href="{{url('/')}}/css/plugins/select2.css">
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
            <div class="row">
                <div class="form-group col-md-4">
                    {!! Form::label('crs_id', 'Curso*', ['class' => 'control-label']) !!}
                    {!! Form::select('crs_id', $cursos, null, ['class' => 'form-control', 'placeholder' => 'Escolha um curso']) !!}
                </div>
                <div class="form-group col-md-4">
                    {!! Form::label('trm_id', 'Turma*', ['class' => 'control-label']) !!}
                    {{ Form::select('trm_id', ['A', 'B'], null, ['class' => 'form-control', 'id' => 'trm_id', 'value' => Input::get('trm_id'), 'placeholder' => 'Turma']) }}
                </div>
                <div class="form-group col-md-4">
                    {!! Form::label('crs_id', 'Módulo*', ['class' => 'control-label']) !!}
                    {{ Form::select('pes_email', ['A', 'B'], null, ['class' => 'form-control', 'id' => 'pes_email', 'value' => Input::get('pes_email'), 'placeholder' => 'Módulo']) }}
                </div>
            </div>
        </div>
        <!-- /.box-body -->
    </div>
@stop

@section('scripts')
    <script src="{{url('/')}}/js/plugins/select2.js"></script>

    <script type="text/javascript">
        $(function () {
            $('select').select2();


        });
    </script>
@stop