@section('stylesheets')
    @parent
    <link rel="stylesheet" href="{{asset('/css/plugins/select2.css')}}">
@stop

<div class="row">
    <div class="form-group col-md-3 @if ($errors->has('modulo')) has-error @endif">
        {!! Form::label('modulo', 'Módulo*', ['class' => 'control-label']) !!}
        <div class="controls">
            {!! Form::select('modulo', $modulos, old('modulo'), ['class' => 'form-control', 'placeholder' => 'Selecione um módulo']) !!}
            @if ($errors->has('modulo')) <p class="help-block">{{ $errors->first('modulo') }}</p> @endif
        </div>
    </div>
    <div class="form-group col-md-3 @if ($errors->has('recurso')) has-error @endif">
        {!! Form::label('recurso', 'Recurso', ['class' => 'control-label']) !!}
        <div class="controls">
            {!! Form::select('recurso', isset($recursos) ? $recursos : [], old('recurso'), ['class' => 'form-control']) !!}
            @if ($errors->has('recurso')) <p class="help-block">{{ $errors->first('recurso') }}</p> @endif
        </div>
    </div>
    <div class="form-group col-md-2">
        <label class="control-label"></label>
        <div class="checkbox">
            <label for="">
                <input type="checkbox" id="cadastro_recurso"><span style="font-weight: 700;">Cadastrar recurso</span>
            </label>
        </div>
    </div>
    <div class="form-group col-md-4 @if ($errors->has('recurso_new')) has-error @endif">
        {!! Form::label('recurso_new', 'Novo Recurso', ['class' => 'control-label']) !!}
        <div class="controls">
            <input type="text" name="recurso" id="recurso_new" value="{{old('recurso')}}" class="form-control" disabled="disabled">
            @if ($errors->has('recurso_new')) <p class="help-block">{{ $errors->first('recurso_new') }}</p> @endif
        </div>
    </div>
</div>
<div class="row">
    <div class="form-group col-md-6 @if ($errors->has('prm_nome')) has-error @endif">
        {!! Form::label('prm_nome', 'Nome*', ['class' => 'control-label']) !!}
        <div class="controls">
            {!! Form::text('prm_nome', old('prm_nome'), ['class' => 'form-control']) !!}
            @if ($errors->has('prm_nome')) <p class="help-block">{{ $errors->first('prm_nome') }}</p> @endif
        </div>
    </div>
    <div class="form-group col-md-6 @if ($errors->has('prm_descricao')) has-error @endif">
        {!! Form::label('prm_descricao', 'Descrição', ['class' => 'control-label']) !!}
        <div class="controls">
            {!! Form::text('prm_descricao', old('prm_descricao'), ['class' => 'form-control']) !!}
            @if ($errors->has('prm_descricao')) <p class="help-block">{{ $errors->first('prm_descricao') }}</p> @endif
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
    <script src="{{asset('/js/plugins/select2.js')}}" type="text/javascript"></script>

    <script type="text/javascript">
        $(document).ready(function () {
            $("select").select2();

            $("#modulo").change(function() {

                var modulo = $(this).val();

                var url = "{{route('seguranca.async.permissoes.getrecursos')}}";

                $.harpia.httpget(url+"?modulo="+modulo).done(function (response) {
                    $('#recurso').empty();

                    if(!$.isEmptyObject(response)) {
                        $('#recurso').append("<option value=''>Selecione o recurso</option>");

                        $.each(response, function (key, value) {
                            $('#recurso').append('<option value="'+key+'">'+value+'</option>');
                        });
                    }else {
                        $('#recurso').append("<option>Não há recurso cadastrados</option>");
                    }
                });
            });

            $(document).on('click', '#cadastro_recurso', function() {
                if ($(this).is(':checked')) {
                    $('#recurso').attr('disabled', 'disabled');
                    $('#recurso_new').attr('disabled', false);
                } else {
                    $('#recurso').attr('disabled', false);
                    $('#recurso_new').attr('disabled', 'disabled');
                }
            });
        });
    </script>
@stop