<div class="row">
    <div class="form-group col-md-4 @if ($errors->has('ofc_crs_id')) has-error @endif">
        <label for="ofc_crs_id" class="form-label">Curso <small class="obrigatorio-dot">*</small></label>
        <div class="controls">
            <select name="ofc_crs_id" class="form-control" id="ofc_crs_id">
                <option value="">Selecione um curso</option>
                @foreach($cursos as $key => $value)
                    <option value="{{ $key }}">{{ $value }}</option>
                @endforeach
            </select>
            @if ($errors->has('ofc_crs_id')) <p class="help-block">{{ $errors->first('ofc_crs_id') }}</p> @endif
        </div>
    </div>
    <div class="form-group col-md-4 @if ($errors->has('ofc_mtc_id')) has-error @endif">
        <label for="ofc_mtc_id" class="form-label">Matriz Curricular <small class="obrigatorio-dot">*</small></label>
        <div class="controls">
            <select name="ofc_mtc_id" class="form-control" id="ofc_mtc_id"></select>
            @if ($errors->has('ofc_mtc_id')) <p class="help-block">{{ $errors->first('ofc_mtc_id') }}</p> @endif
        </div>
    </div>
    <div class="form-group col-md-4 @if ($errors->has('ofc_mdl_id')) has-error @endif">
        <label for="ofc_mdl_id" class="form-label">Modalidade <small class="obrigatorio-dot">*</small></label>
        <div class="controls">
            <select name="ofc_mdl_id" class="form-control">
                <option value="">Selecione a modalidade</option>
                @foreach($modalidades as $key => $value)
                    <option value="{{ $key }}" {{ old('ofc_mdl_id') == $key ? 'selected' : '' }}>{{ $value }}</option>
                @endforeach
            </select>
            @if ($errors->has('ofc_mdl_id')) <p class="help-block">{{ $errors->first('ofc_mdl_id') }}</p> @endif
        </div>
    </div>
</div>
<div class="row">
        <div class="form-group col-md-9 @if ($errors->has('polos')) has-error @endif">
            <label for="polos" class="form-label">Polos <small class="obrigatorio-dot">*</small></label>
            <div class="controls">
                <select name="polos[]" class="form-control" multiple="multiple">
                    @foreach($polos as $key => $value)
                        <option value="{{ $key }}" {{ old('polos[]') == $key ? 'selected' : '' }}>{{ $value }}</option>
                    @endforeach
                </select>
                @if ($errors->has('polos')) <p class="help-block">{{ $errors->first('polos') }}</p> @endif
            </div>
        </div>
        <div class="form-group col-md-3 @if ($errors->has('ofc_ano')) has-error @endif">
            <label for="ofc_ano" class="form-label">Ano <small class="obrigatorio-dot">*</small></label>
            <div class="controls">
                <input type="number" name="ofc_ano" value="{{ old('ofc_ano') }}" class="form-control" >
                @if ($errors->has('ofc_ano')) <p class="help-block">{{ $errors->first('ofc_ano') }}</p> @endif
            </div>
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
