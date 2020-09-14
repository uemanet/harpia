@section('stylesheets')
    <link rel="stylesheet" href="{{asset('/css/plugins/select2.css')}}">
@endsection
<div class="row">
    <div class="form-group col-md-4 @if ($errors->has('nome')) has-error @endif">
        {!! Form::label('nome', 'Nome da chamada*', ['class' => 'control-label']) !!}
        <div class="controls">
            {!! Form::text('nome', old('nome'), ['class' => 'form-control']) !!}
            @if ($errors->has('nome')) <p class="help-block">{{ $errors->first('nome') }}</p> @endif
        </div>
    </div>
    <div class="form-group col-md-4 @if ($errors->has('inicio_matricula')) has-error @endif">
        {!! Form::label('inicio_matricula', 'Data de Início*', ['class' => 'control-label']) !!}
        <div class="controls">
            {!! Form::text('inicio_matricula', old('inicio_matricula'), ['class' => 'form-control', 'id' => 'inicio_matricula']) !!}
            @if ($errors->has('inicio_matricula')) <p class="help-block">{{ $errors->first('inicio_matricula') }}</p> @endif
        </div>
    </div>
    <div class="form-group col-md-4 @if ($errors->has('pol_nome')) has-error @endif">
        {!! Form::label('fim_matricula', 'Data de Fim*', ['class' => 'control-label']) !!}
        <div class="controls">
            {!! Form::text('fim_matricula', old('fim_matricula'), ['class' => 'form-control', 'id' => 'fim_matricula']) !!}
            @if ($errors->has('fim_matricula')) <p class="help-block">{{ $errors->first('fim_matricula') }}</p> @endif
        </div>
    </div>
</div>
<div class="row">
    <div class="form-group col-md-6 @if ($errors->has('seletivo_id')) has-error @endif">
        {!! Form::label('seletivo_id', 'Seletivo*', ['class' => 'control-label']) !!}
        <div class="controls">
            {!! Form::select('seletivo_id', $seletivos, null, ['class' => 'form-control', 'placeholder' => 'Escolha o Seletivo']) !!}
            @if ($errors->has('seletivo_id')) <p class="help-block">{{ $errors->first('seletivo_id') }}</p> @endif
        </div>
    </div>
    <div class="form-group col-md-6 @if ($errors->has('tipo_chamada')) has-error @endif">
        {!! Form::label('tipo_chamada', 'Tipo de chamada*', ['class' => 'control-label']) !!}
        <div class="controls">
            {!! Form::select('tipo_chamada', ['normal' => 'Normal', 'excedente' => 'Excedente'], old('tipo_chamada'), ['class' => 'form-control', 'placeholder' => 'Escolha o tipo de chamada']) !!}
            @if ($errors->has('tipo_chamada')) <p class="help-block">{{ $errors->first('tipo_chamada') }}</p> @endif
        </div>
    </div>
</div>
<div class="row">
    <div class="form-group col-md-12">
        {!! Form::submit('Salvar dados', ['class' => 'btn btn-primary pull-right']) !!}
    </div>
</div>

@section('scripts')
    @parent

    <script src="{{ asset('/js/plugins/input-mask/inputmask.js') }}"></script>
    <script src="{{ asset('/js/plugins/input-mask/inputmask.date.extensions.js') }}"></script>
    <script src="{{url('/')}}/js/plugins/select2.js"></script>

    <script type="text/javascript">
        $(function () {
            $('select').select2();

            Inputmask({"mask": "99/99/9999 99:99:99"}).mask('#inicio_matricula');
            Inputmask({"mask": "99/99/9999 99:99:99"}).mask('#fim_matricula');
        });
    </script>
@endsection