<div class="row">
    <div class="form-group col-md-4 @if ($errors->has('ofc_crs_id')) has-error @endif">
        {!! Form::label('ofc_crs_id', 'Curso*', ['class' => 'control-label']) !!}
        <div class="controls">
            {!! Form::select('ofc_crs_id', $cursos, null, ['class' => 'form-control', 'placeholder' => 'Selecione um curso', 'id' => 'ofc_crs_id']) !!}
            @if ($errors->has('ofc_crs_id')) <p class="help-block">{{ $errors->first('ofc_crs_id') }}</p> @endif
        </div>
    </div>
    <div class="form-group col-md-4 @if ($errors->has('ofc_mtc_id')) has-error @endif">
        {!! Form::label('ofc_mtc_id', 'Matriz Curricular*', ['class' => 'control-label']) !!}
        <div class="controls">
            {!! Form::select('ofc_mtc_id', [], null, ['class' => 'form-control', 'id' => 'ofc_mtc_id']) !!}
            @if ($errors->has('ofc_mtc_id')) <p class="help-block">{{ $errors->first('ofc_mtc_id') }}</p> @endif
        </div>
    </div>
    <div class="form-group col-md-4 @if ($errors->has('ofc_mdl_id')) has-error @endif">
        {!! Form::label('ofc_mdl_id', 'Modalidade*', ['class' => 'control-label']) !!}
        <div class="controls">
            {!! Form::select('ofc_mdl_id', $modalidades, old('ofc_mdl_id'), ['class' => 'form-control']) !!}
            @if ($errors->has('ofc_mdl_id')) <p class="help-block">{{ $errors->first('ofc_mdl_id') }}</p> @endif
        </div>
    </div>
</div>
<div class="row">
    <div class="form-group col-md-12 @if ($errors->has('ofc_ano')) has-error @endif">
        {!! Form::label('ofc_ano', 'Ano*', ['class' => 'control-label']) !!}
        <div class="controls">
            {!! Form::number('ofc_ano', old('ofc_ano'), ['class' => 'form-control']) !!}
            @if ($errors->has('ofc_ano')) <p class="help-block">{{ $errors->first('ofc_ano') }}</p> @endif
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

    <script type="application/javascript">
        $(document).ready(function(){
            $('#ofc_crs_id').prop('selectedIndex',0);
        });
    </script>
    <script type="application/javascript">

        $('#ofc_crs_id').change(function() {
            var cursoId = $("#ofc_crs_id").val();

            if (!cursoId) {
                return;
            }

            $.harpia.httpget('{{url('/')}}/academico/async/matrizescurriculares/findallbycurso/' + cursoId).done(function(result){

                $("#ofc_mtc_id").empty();

                if ($.isEmptyObject(result)) {
                    $('#ofc_mtc_id').append('<option value=#>Sem matrizes curriculares cadastradas</option>');
                } else {
                    $("#ofc_mtc_id").append("<option value='' selected>Selecione uma matriz curricular</option>");
                    $.each(result, function(key, value) {
                        $('#ofc_mtc_id').append('<option value=' + value.mtc_id + ' >' + value.mtc_descricao + '</option>');
                    });
                }

                $('#ofc_mtc_id').focus();
            });
        });

    </script>
@stop
