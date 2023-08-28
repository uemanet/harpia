@extends('layouts.modulos.academico')

@section('stylesheets')
    <link rel="stylesheet" href="{{asset('/css/plugins/select2.css')}}">
@endsection

@section('title')
    Editar Oferta de Disciplina
@stop

@section('subtitle')
    {{$ofertaDisciplina->moduloDisciplina->disciplina->dis_nome}}
@stop

@section('content')
    <div class="box box-primary">
        <div class="box-header">
            <h4 class="box-title">Editar</h4>
        </div>
        <div class="box-body">
            <div class="row">
                <div class="col-md-4">
                    <div class="form-group">
                        {!! Form::label('disciplina', 'Disciplina*') !!}
                        {!! Form::text('disciplina', $ofertaDisciplina->moduloDisciplina->disciplina->dis_nome, ['class' => 'form-control', 'disabled' => 'disabled']) !!}
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="form-group">
                        {!! Form::label('carga_horaria', 'Carga Horária*') !!}
                        {!! Form::text('carga_horaria', $ofertaDisciplina->moduloDisciplina->disciplina->dis_carga_horaria." horas", ['class' => 'form-control', 'disabled' => 'disabled']) !!}
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="form-group">
                        {!! Form::label('creditos', 'Créditos*') !!}
                        {!! Form::text('creditos', $ofertaDisciplina->moduloDisciplina->disciplina->dis_creditos, ['class' => 'form-control', 'disabled' => 'disabled']) !!}
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        {!! Form::label('tipo_disciplina', 'Tipo da Disciplina*') !!}
                        {!! Form::text('tipo_disciplina', $ofertaDisciplina->moduloDisciplina->mdc_tipo_disciplina, ['class' => 'form-control', 'disabled' => 'disabled']) !!}
                    </div>
                </div>
            </div>
            {!! Form::model($ofertaDisciplina, ['route' => ['academico.ofertasdisciplinas.edit', $ofertaDisciplina->ofd_id], 'method' => 'PUT', 'role' => 'form']) !!}
                <div class="row">
                    <div class="col-md-4">
                        <div class="form-group @if($errors->has('ofd_tipo_avaliacao')) has-error @endif">
                            {!! Form::label('ofd_tipo_avaliacao', 'Tipo de Avaliação*') !!}
                            {!! Form::select('ofd_tipo_avaliacao', ['numerica' => 'Numérica', 'conceitual' => 'Conceitual'], old('ofd_tipo_avaliacao'), ['class' => 'form-control', 'disabled' => 'disabled']) !!}
                            @if($errors->has('ofd_tipo_avaliacao')) <p class="help-block">{{ $errors->first('ofd_tipo_avaliacao') }}</p> @endif
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group @if($errors->has('ofd_qtd_vagas')) has-error @endif">
                            {!! Form::label('ofd_qtd_vagas', 'Quantidade de Vagas*') !!}
                            {!! Form::number('ofd_qtd_vagas', old('ofd_qtd_vagas'), ['class' => 'form-control']) !!}
                            @if($errors->has('ofd_qtd_vagas')) <p class="help-block">{{ $errors->first('ofd_qtd_vagas') }}</p> @endif
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group @if($errors->has('ofd_prf_id')) has-error @endif">
                            {!! Form::label('ofd_prf_id', 'Professor*') !!}
                            {!! Form::select('ofd_prf_id', $professores, old('ofd_prf_id'), ['class' => 'form-control']) !!}
                            @if($errors->has('ofd_prf_id')) <p class="help-block">{{ $errors->first('ofd_prf_id') }}</p> @endif
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group">
                            <button type="submit" class="btn btn-primary pull-right">Salvar dados</button>
                        </div>
                    </div>
                </div>
            {!! Form::close() !!}
        </div>
    </div>
@stop

@section('scripts')
    <script src="{{asset('/js/plugins/select2.js')}}" type="text/javascript"></script>

    <script type="text/javascript">
        $(function() {
            $("select").select2();
        });
    </script>
@endsection
