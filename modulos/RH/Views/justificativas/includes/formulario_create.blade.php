@section('stylesheets')
  <link rel="stylesheet" href="{{asset('/css/plugins/select2.css')}}">
@endsection
<div class="row">
    <div class="form-group col-md-2 @if ($errors->has('jus_horas')) has-error @endif">
        {!! Form::label('jus_horas', 'Quantidade de Horas*', ['class' => 'control-label']) !!}
        <div class="controls">
            {!! Form::number('jus_horas', old('jus_horas'), ['class' => 'form-control']) !!}
            @if ($errors->has('jus_horas')) <p class="help-block">{{ $errors->first('jus_horas') }}</p> @endif
        </div>
    </div>

    <div class="form-group col-md-2 @if ($errors->has('jus_data')) has-error @endif">
        {!! Form::label('jus_data', 'Data Inicial*', ['class' => 'control-label']) !!}
        <div class="controls">
            {!! Form::text('jus_data', old('jus_data'), ['class' => 'form-control datepicker', 'data-provide' => 'datepicker', 'date-date-format' => 'dd/mm/yyyy']) !!}
            @if ($errors->has('jus_data')) <p class="help-block">{{ $errors->first('jus_data') }}</p> @endif
        </div>
    </div>

    <div class="form-group col-md-2 @if ($errors->has('jus_data_fim')) has-error @endif">
        {!! Form::label('jus_data_fim', 'Data Final*', ['class' => 'control-label']) !!}
        <div class="controls">
            {!! Form::text('jus_data_fim', old('jus_data_fim'), ['class' => 'form-control datepicker', 'data-provide' => 'datepicker', 'date-date-format' => 'dd/mm/yyyy']) !!}
            @if ($errors->has('jus_data_fim')) <p class="help-block">{{ $errors->first('jus_data_fim') }}</p> @endif
        </div>
    </div>

    <div class="form-group col-md-4 @if ($errors->has('jus_file')) has-error @endif">
        {!! Form::label('jus_file', 'Anexo', ['class' => 'control-label']) !!}
        <div class="controls">
            {!! Form::file('jus_file', ['class' => 'form-control file']) !!}
            @if ($errors->has('jus_file')) <p class="help-block">{{ $errors->first('jus_file') }}</p> @endif
        </div>
    </div>

</div>

<div class="row">

    <div class="form-group col-md-8 @if ($errors->has('jus_descricao')) has-error @endif">
        {!! Form::label('jus_descricao', 'Descrição', ['class' => 'control-label']) !!}
        <div class="controls">
            {!! Form::textarea('jus_descricao', old('jus_descricao'), ['class' => 'form-control', 'rows' => '4']) !!}
            @if ($errors->has('jus_descricao')) <p class="help-block">{{ $errors->first('jus_descricao') }}</p> @endif
        </div>
    </div>

    <div class="form-group col-md-2 @if ($errors->has('jus_tipo_id')) has-error @endif">
        {!! Form::label('jus_tipo_id', 'Tipo de Justificativa*', ['class' => 'control-label']) !!}
        <div class="controls">
            {!! Form::select('jus_tipo_id', $tipos, null, ['class' => 'form-control select2']) !!}
            @if ($errors->has('jus_tipo_id')) <p class="help-block">{{ $errors->first('jus_tipo_id') }}</p> @endif
        </div>
    </div>




</div>
{!! Form::input('hidden' , 'jus_htr_id', $horaTrabalhada->htr_id ,  ['class' => 'form-control']) !!}
<div class="row">
    <div class="form-group col-md-12">
        {!! Form::submit('Salvar dados', ['class' => 'btn btn-primary pull-right']) !!}
    </div>
</div>
@section('scripts')
    @parent
    <script src="{{asset('/js/plugins/select2.js')}}" type="text/javascript"></script>

    <script type="text/javascript">
        $(document).ready(function() {
            $("select").select2();
        });
    </script>
@stop
