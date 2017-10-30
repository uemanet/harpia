<div class="row">
    <div class="form-group col-md-4 @if ($errors->has('ofc_crs_id')) has-error @endif">
        {!! Form::label('ofc_crs_id', 'Curso', ['class' => 'control-label']) !!}
        <div class="controls">
            {!! Form::text('ofc_crs_id', $ofertaCurso->curso->crs_nome, ['class' => 'form-control', 'disabled' => true, 'placeholder' => 'Selecione um curso', 'id' => 'ofc_crs_id']) !!}
        </div>
    </div>
    <div class="form-group col-md-4 @if ($errors->has('ofc_mtc_id')) has-error @endif">
        {!! Form::label('ofc_mtc_id', 'Matriz Curricular', ['class' => 'control-label']) !!}
        <div class="controls">
            {!! Form::text('ofc_mtc_id', $ofertaCurso->matriz->mtc_titulo, ['class' => 'form-control', 'disabled' => true, 'id' => 'ofc_mtc_id']) !!}
        </div>
    </div>
    <div class="form-group col-md-4 @if ($errors->has('ofc_mdl_id')) has-error @endif">
        {!! Form::label('ofc_mdl_id', 'Modalidade*', ['class' => 'control-label']) !!}
        <div class="controls">
            {!! Form::select('ofc_mdl_id', $modalidades, [$ofertaCurso->modalidade->mdl_id], ['class' => 'form-control', 'placeholder' => 'Selecione a modalidade']) !!}
        </div>
    </div>
</div>
<div class="row">
    <div class="form-group col-md-9 @if ($errors->has('polos')) has-error @endif">
        {!! Form::label('polos', 'Polos*', ['class' => 'control-label']) !!}
        <div class="controls">
            {!! Form::select('polos[]', $polos, $polosOferta, ['class' => 'form-control', 'multiple' => 'multiple']) !!}
        </div>
    </div>
    <div class="form-group col-md-3 @if ($errors->has('ofc_ano')) has-error @endif">
        {!! Form::label('ofc_ano', 'Ano*', ['class' => 'control-label']) !!}
        <div class="controls">
            {!! Form::number('ofc_ano', $ofertaCurso->ofc_ano, ['class' => 'form-control']) !!}
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
                        $('#ofc_mtc_id').append('<option value=' + value.mtc_id + ' >' + value.mtc_titulo + '</option>');
                    });
                }

                $('#ofc_mtc_id').focus();
            });
        });

    </script>
@stop
