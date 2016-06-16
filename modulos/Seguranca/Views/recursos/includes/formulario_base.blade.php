<div class="row">
    <div class="form-group col-md-3 @if ($errors->has('rcs_nome')) has-error @endif">
        {!! Form::label('rcs_nome', 'Nome do recurso*', ['class' => 'control-label']) !!}
        <div class="controls">
            {!! Form::text('rcs_nome', old('rcs_nome'), ['class' => 'form-control']) !!}
            @if ($errors->has('rcs_nome')) <p class="help-block">{{ $errors->first('rcs_nome') }}</p> @endif
        </div>
    </div>
    <div class="form-group col-md-3 @if ($errors->has('rcs_rota')) has-error @endif">
        {!! Form::label('rcs_rota', 'Rota*', ['class' => 'control-label']) !!}
        <div class="controls">
            {!! Form::text('rcs_rota', old('rcs_rota'), ['class' => 'form-control']) !!}
            @if ($errors->has('rcs_rota')) <p class="help-block">{{ $errors->first('rcs_rota') }}</p> @endif
        </div>
    </div>
    <div class="form-group col-md-2 @if ($errors->has('rcs_icone')) has-error @endif">
        {!! Form::label('rcs_icone', 'Ícone*', ['class' => 'control-label']) !!}
        <div class="controls">
            {!! Form::text('rcs_icone', old('rcs_icone'), ['class' => 'form-control', 'placeholder' => 'fa fa-cog']) !!}
            @if ($errors->has('rcs_icone')) <p class="help-block">{{ $errors->first('rcs_icone') }}</p> @endif
        </div>
    </div>
    <div class="form-group col-md-2 @if ($errors->has('rcs_ordem')) has-error @endif">
        {!! Form::label('rcs_ordem', 'Ordem*', ['class' => 'control-label']) !!}
        <div class="controls">
            {!! Form::number('rcs_ordem', old('rcs_ordem'), ['class' => 'form-control']) !!}
            @if ($errors->has('rcs_ordem')) <p class="help-block">{{ $errors->first('rcs_ordem') }}</p> @endif
        </div>
    </div>
    <div class="form-group col-md-2 @if ($errors->has('rcs_ativo')) has-error @endif">
        {!! Form::label('rcs_ativo', 'Ativo*', ['class' => 'control-label']) !!}
        <div class="controls">
            {!! Form::select('rcs_ativo', [1 => "Sim", 0 => "Não"], old('rcs_ctr_id'), ['class' => 'form-control']) !!}
            @if ($errors->has('rcs_ativo')) <p class="help-block">{{ $errors->first('rcs_ativo') }}</p> @endif
        </div>
    </div>
</div>
<div class="form-group @if ($errors->has('rcs_descricao')) has-error @endif">
    {!! Form::label('rcs_descricao', 'Descrição do recurso', ['class' => 'control-label']) !!}
    <div class="controls">
        {!! Form::text('rcs_descricao', old('rcs_descricao'), ['class' => 'form-control']) !!}
        @if ($errors->has('rcs_descricao')) <p class="help-block">{{ $errors->first('rcs_descricao') }}</p> @endif
    </div>
</div>
<div class="row">
    <div class="form-group col-md-12">
        {!! Form::submit('Salvar dados', ['class' => 'btn btn-primary pull-right']) !!}
    </div>
</div>

@section('scripts')
    <script type="application/javascript">

        $('#mod_id').change(function() {
            var moduloId = $("#mod_id").val();

            if (!moduloId) {
                return;
            }

            $.harpia.showloading();

            $.get('{{url('/')}}/seguranca/ajax/categorias/findallbymodulo/' + moduloId, function (categorias) {
                $("#rcs_ctr_id").empty().append("<option value='' selected>Selecione uma categoria</option>");

                if($.isEmptyObject(categorias)) {
                    $('#rcs_ctr_id').append('<option value=#>Sem categorias disponíveis</option>');
                } else {
                    $.each(categorias, function(key, value) {
                        $('#rcs_ctr_id').append('<option value=' + value.ctr_id + ' >' + value.ctr_nome + '</option>');
                    });
                }

                $('#rcs_ctr_id').focus();

                $.harpia.hideloading();
            }).error(function(e){
                sweetAlert("Oops...", "Um erro aconteceu! Se o problema persistir, entre em contato com a administração do sistema.", "error");
                $.harpia.hideloading();
            });
        });

    </script>
@stop