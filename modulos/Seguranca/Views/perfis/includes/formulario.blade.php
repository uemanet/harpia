<div class="row">
    <div class="form-group col-md-3 @if ($errors->has('prf_mod_id')) has-error @endif">
        {!! Form::label('prf_mod_id', 'Módulo*', ['class' => 'control-label']) !!}
        <div class="controls">
            {!! Form::select('prf_mod_id', $modulos, old('prf_mod_id'), ['class' => 'form-control', 'id' => 'mod_id', 'placeholder' => 'Selecione um módulo']) !!}
            @if ($errors->has('prf_mod_id')) <p class="help-block">{{ $errors->first('prf_mod_id') }}</p> @endif
        </div>
    </div>
    <div class="form-group col-md-9 @if ($errors->has('prf_nome')) has-error @endif">
        {!! Form::label('prf_nome', 'Nome do perfil*', ['class' => 'control-label']) !!}
        <div class="controls">
            {!! Form::text('prf_nome', old('prf_nome'), ['class' => 'form-control']) !!}
            @if ($errors->has('prf_nome')) <p class="help-block">{{ $errors->first('prf_nome') }}</p> @endif
        </div>
    </div>
</div>
<div class="form-group @if ($errors->has('prf_descricao')) has-error @endif">
    {!! Form::label('prf_descricao', 'Descrição do perfil', ['class' => 'control-label']) !!}
    <div class="controls">
        {!! Form::text('prf_descricao', old('prf_descricao'), ['class' => 'form-control']) !!}
        @if ($errors->has('prf_descricao')) <p class="help-block">{{ $errors->first('prf_descricao') }}</p> @endif
    </div>
</div>
<div class="row">
    <div class="form-group col-md-12">
        {!! Form::submit('Salvar dados', ['class' => 'btn btn-primary pull-right']) !!}
    </div>
</div>

@section('scripts')
    <script>
        $('#mod_id').change(function() {
            var moduloId = $("#mod_id").val();

            if (!moduloId) {
                return;
            }

            $.harpia.showloading();

            $.get('{{url('/')}}/seguranca/ajax/categorias/findallbymodulo/' + moduloId, function (categorias) {
                $("#rcs_ctr_id").empty().append("<option value='' selected>Selecione uma categoria</option>");

                console.log(categorias);

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