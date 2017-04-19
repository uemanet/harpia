@section('stylesheets')
    <link rel="stylesheet" href="{{asset('/css/plugins/select2.css')}}">
@stop
<div class="row">
    <div class="form-group col-md-3 @if ($errors->has('ctr_mod_id')) has-error @endif">
        {!! Form::label('ctr_mod_id', 'Módulo*', ['class' => 'control-label']) !!}
        <div class="controls">
            {!! Form::select('ctr_mod_id', $modulos, old('ctr_mod_id'), ['class' => 'form-control', 'placeholder' => 'Selecione um módulo']) !!}
            @if ($errors->has('ctr_mod_id')) <p class="help-block">{{ $errors->first('ctr_mod_id') }}</p> @endif
        </div>
    </div>
    <div class="form-group col-md-9 @if ($errors->has('ctr_nome')) has-error @endif">
        {!! Form::label('ctr_nome', 'Nome da categoria*', ['class' => 'control-label']) !!}
        <div class="controls">
            {!! Form::text('ctr_nome', old('ctr_nome'), ['class' => 'form-control']) !!}
            @if ($errors->has('ctr_nome')) <p class="help-block">{{ $errors->first('ctr_nome') }}</p> @endif
        </div>
    </div>
</div>
<div class="row">
    <div class="form-group col-md-3 @if ($errors->has('ctr_icone')) has-error @endif">
        {!! Form::label('ctr_icone', 'Ícone*', ['class' => 'control-label']) !!}
        <div class="controls">
            {!! Form::text('ctr_icone', old('ctr_icone'), ['class' => 'form-control', 'placeholder' => 'fa fa-cog']) !!}
            @if ($errors->has('ctr_icone')) <p class="help-block">{{ $errors->first('ctr_icone') }}</p> @endif
        </div>
    </div>
    <div class="form-group col-md-3 @if ($errors->has('ctr_ordem')) has-error @endif">
        {!! Form::label('ctr_ordem', 'Ordem*', ['class' => 'control-label']) !!}
        <div class="controls">
            {!! Form::number('ctr_ordem', old('ctr_ordem'), ['class' => 'form-control']) !!}
            @if ($errors->has('ctr_ordem')) <p class="help-block">{{ $errors->first('ctr_ordem') }}</p> @endif
        </div>
    </div>
    <div class="form-group col-md-3 @if ($errors->has('ctr_ativo')) has-error @endif">
        {!! Form::label('ctr_ativo', 'Ativo*', ['class' => 'control-label']) !!}
        <div class="controls">
            {!! Form::select('ctr_ativo', [1 => 'Sim', 0 => 'Não'], old('ctr_ativo'), ['class' => 'form-control']) !!}
            @if ($errors->has('ctr_ativo')) <p class="help-block">{{ $errors->first('ctr_ativo') }}</p> @endif
        </div>
    </div>
    <div class="form-group col-md-3 @if ($errors->has('ctr_visivel')) has-error @endif">
        {!! Form::label('ctr_visivel', 'Visível*', ['class' => 'control-label']) !!}
        <div class="controls">
            {!! Form::select('ctr_visivel', [1 => 'Sim', 0 => 'Não'], old('ctr_visivel'), ['class' => 'form-control']) !!}
            @if ($errors->has('ctr_visivel')) <p class="help-block">{{ $errors->first('ctr_visivel') }}</p> @endif
        </div>
    </div>
</div>
<div class="row">
    <div class="form-group col-md-9 @if ($errors->has('ctr_descricao')) has-error @endif">
        {!! Form::label('ctr_descricao', 'Descrição da categoria', ['class' => 'control-label']) !!}
        <div class="controls">
            {!! Form::text('ctr_descricao', old('ctr_descricao'), ['class' => 'form-control']) !!}
            @if ($errors->has('ctr_descricao')) <p class="help-block">{{ $errors->first('ctr_descricao') }}</p> @endif
        </div>
    </div>
    <div class="form-group col-md-3 @if ($errors->has('ctr_referencia')) has-error @endif">
        {!! Form::label('ctr_referencia', 'Categoria de referência', ['class' => 'control-label']) !!}
        <div class="controls">
            {!! Form::select('ctr_referencia', $categorias, old('ctr_referencia'), ['class' => 'form-control', 'placeholder' => 'Selecione uma categoria']) !!}
            @if ($errors->has('ctr_referencia')) <p class="help-block">{{ $errors->first('ctr_referencia') }}</p> @endif
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