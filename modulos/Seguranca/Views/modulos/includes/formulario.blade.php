@section('stylesheets')
    <link rel="stylesheet" href="{{asset('/css/plugins/select2.css')}}">
@stop
<div class="row">
    <div class="form-group col-md-3 @if ($errors->has('mod_nome')) has-error @endif">
        {!! Form::label('mod_nome', 'Nome do módulo*', ['class' => 'control-label']) !!}
        <div class="controls">
            {!! Form::text('mod_nome', old('mod_nome'), ['class' => 'form-control']) !!}
            @if ($errors->has('mod_nome')) <p class="help-block">{{ $errors->first('mod_nome') }}</p> @endif
        </div>
    </div>
    <div class="form-group col-md-3 @if ($errors->has('mod_rota')) has-error @endif">
        {!! Form::label('mod_rota', 'Rota do módulo*', ['class' => 'control-label']) !!}
        <div class="controls">
            {!! Form::text('mod_rota', old('mod_rota'), ['class' => 'form-control']) !!}
            @if ($errors->has('mod_rota')) <p class="help-block">{{ $errors->first('mod_rota') }}</p> @endif
        </div>
    </div>
    <div class="form-group col-md-3 @if ($errors->has('mod_icone')) has-error @endif">
        {!! Form::label('mod_icone', 'Ícone*', ['class' => 'control-label']) !!}
        <div class="controls">
            {!! Form::text('mod_icone', old('mod_icone'), ['class' => 'form-control', 'placeholder' => 'fa fa-icone*']) !!}
            @if ($errors->has('mod_icone')) <p class="help-block">{{ $errors->first('mod_icone') }}</p> @endif
        </div>
    </div>
    <div class="form-group col-md-3 @if ($errors->has('mod_class')) has-error @endif">
        {!! Form::label('mod_class', 'Classe*', ['class' => 'control-label']) !!}
        <div class="controls">
            {!! Form::select('mod_class',
                             ['bg-aqua' => 'Aqua',
                              'bg-red' => 'Red',
                              'bg-yellow' => 'Yellow',
                              'bg-blue' => 'Blue',
                              'bg-light-blue' => 'Light-blue',
                              'bg-green' => 'Green',
                              'bg-navy' => 'Navy',
                              'bg-teal' => 'Teal',
                              'bg-olive' => 'Olive',
                              'bg-lime' => 'Lime',
                              'bg-orange' => 'Orange',
                              'bg-fuchsia' => 'Fuchsia',
                              'bg-purple' => 'Purple',
                              'bg-maroon' => 'Maroon',
                              'bg-black' => 'black'],
                              old('mod_class'),
                              ['class' => 'form-control']) !!}
            @if ($errors->has('mod_class')) <p class="help-block">{{ $errors->first('mod_class') }}</p> @endif
        </div>
    </div>
</div>
<div class="row">
    <div class="form-group col-md-3 @if ($errors->has('mod_ativo')) has-error @endif">
        {!! Form::label('mod_ativo', 'Status*', ['class' => 'control-label']) !!}
        <div class="controls">
            {!! Form::select('mod_ativo', [1 => 'Ativo', 0 => 'Inativo'], old('mod_ativo'), ['class' => 'form-control']) !!}
            @if ($errors->has('mod_ativo')) <p class="help-block">{{ $errors->first('mod_ativo') }}</p> @endif
        </div>
    </div>
    <div class="form-group col-md-3 @if ($errors->has('mod_style')) has-error @endif">
        {!! Form::label('mod_style', 'Estilo', ['class' => 'control-label']) !!}
        <div class="controls">
            {!! Form::text('mod_style', old('mod_style'), ['class' => 'form-control']) !!}
            @if ($errors->has('mod_style')) <p class="help-block">{{ $errors->first('mod_style') }}</p> @endif
        </div>
    </div>
    <div class="form-group col-md-6 @if ($errors->has('mod_descricao')) has-error @endif">
        {!! Form::label('mod_descricao', 'Descrição do Módulo', ['class' => 'control-label']) !!}
        <div class="controls">
            {!! Form::text('mod_descricao', old('mod_descricao'), ['class' => 'form-control']) !!}
            @if ($errors->has('mod_descricao')) <p class="help-block">{{ $errors->first('mod_descricao') }}</p> @endif
        </div>
    </div>
</div>
<div class="row">
    <div class="form-group col-md-12">
        {!! Form::submit('Salvar dados', ['class' => 'btn btn-primary pull-right']) !!}
    </div>
</div>

@section('scripts')
    <script src="{{asset('/js/plugins/select2.js')}}" type="text/javascript"></script>

    <script type="text/javascript">
        $(document).ready(function () {
            $("select").select2();
        });
    </script>
@stop