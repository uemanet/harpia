<div class="row">
    <div class="form-group col-md-4 @if ($errors->has('ofc_crs_id')) has-error @endif">
        <label for="ofc_crs_id" class="form-label">Curso</label>
        <div class="controls">
            <input type="text" name="ofc_crs_id" value="{{ $ofertaCurso->curso->crs_nome }}" class="form-control" disabled="true" placeholder="Selecione um curso" id="ofc_crs_id" >
        </div>
    </div>
    <div class="form-group col-md-4 @if ($errors->has('ofc_mtc_id')) has-error @endif">
        <label for="ofc_mtc_id" class="form-label">Matriz Curricular</label>
        <div class="controls">
            <input type="text" name="ofc_mtc_id" value="{{ $ofertaCurso->matriz->mtc_titulo }}" class="form-control" disabled="true" id="ofc_mtc_id" >
        </div>
    </div>
    <div class="form-group col-md-4 @if ($errors->has('ofc_mdl_id')) has-error @endif">
        <label for="ofc_mdl_id" class="form-label">Modalidade <small class="obrigatorio-dot">*</small></label>
        <div class="controls">
            <select name="ofc_mdl_id" class="form-control" disabled="true">
                <option value="">Selecione a modalidade</option>
                @foreach($modalidades as $key => $value)
                    <option value="{{ $key }}" {{ $ofertaCurso->modalidade->mdl_id == $key ? 'selected' : '' }}>{{ $value }}</option>
                @endforeach
            </select>
        </div>
    </div>
</div>
<div class="row">
    <div class="form-group col-md-9 @if ($errors->has('polos')) has-error @endif">
        <label for="polos" class="form-label">Polos <small class="obrigatorio-dot">*</small></label>
        <div class="controls">
            <select name="polos[]" class="form-control" multiple="multiple">
                @foreach($polos as $key => $value)
                    <option value="{{ $key }}" {{ in_array($key, old('polos', isset($polosOferta) ? $polosOferta : [])) ? 'selected' : '' }}>
                        {{ $value }}
                    </option>
                @endforeach
            </select>
            @if ($errors->has('polos')) <p class="help-block">{{ $errors->first('polos') }}</p> @endif
        </div>
    </div>
    <div class="form-group col-md-3 @if ($errors->has('ofc_ano')) has-error @endif">
        <label for="ofc_ano" class="form-label">Ano <small class="obrigatorio-dot">*</small></label>
        <div class="controls">
            <input type="number" name="ofc_ano" value="{{ $ofertaCurso->ofc_ano }}" class="form-control" disabled="true" >
        </div>
    </div>
</div>

@section('scripts')
    @parent

    <script type="application/javascript">
        $(document).ready(function () {
            $('#ofc_crs_id').prop('selectedIndex', 0);
        });
    </script>
    <script type="application/javascript">

        $('#ofc_crs_id').change(function () {
            var cursoId = $("#ofc_crs_id").val();

            if (!cursoId) {
                return;
            }

            $.harpia.httpget('{{url('/')}}/academico/async/matrizescurriculares/findallbycurso/' + cursoId).done(function (result) {

                $("#ofc_mtc_id").empty();

                if ($.isEmptyObject(result)) {
                    $('#ofc_mtc_id').append('<option value=#>Sem matrizes curriculares cadastradas</option>');
                } else {
                    $("#ofc_mtc_id").append("<option value='' selected>Selecione uma matriz curricular</option>");
                    $.each(result, function (key, value) {
                        $('#ofc_mtc_id').append('<option value=' + value.mtc_id + ' >' + value.mtc_titulo + '</option>');
                    });
                }

                $('#ofc_mtc_id').focus();
            });
        });

    </script>
@stop
