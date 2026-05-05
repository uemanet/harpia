@php
    $anexo = $matrizCurricular->projeto()->get()->pop();
@endphp
<div class="row">
    <div class="form-group col-md-6 @if ($errors->has('mtc_crs_id')) has-error @endif">
        <label for="mtc_crs_id" class="form-label">Curso <small class="obrigatorio-dot">*</small></label>
        <div class="controls">
            <select name="mtc_crs_id" class="form-control">
                @foreach($curso as $key => $value)
                    <option value="{{ $key }}" {{ old('mtc_crs_id', $matrizCurricular->mtc_crs_id) == $key ? 'selected' : '' }}>{{ $value }}</option>
                @endforeach
            </select>
            @if ($errors->has('mtc_crs_id')) <p class="help-block">{{ $errors->first('mtc_crs_id') }}</p> @endif
        </div>
    </div>
    <div class="form-group col-md-3 @if ($errors->has('mtc_titulo')) has-error @endif">
        <label for="mtc_titulo" class="form-label">Título <small class="obrigatorio-dot">*</small></label>
        <div class="controls">
            <input type="text" name="mtc_titulo" value="{{ old('mtc_titulo', $matrizCurricular->mtc_titulo) }}" class="form-control select-control" >
            @if ($errors->has('mtc_titulo')) <p class="help-block">{{ $errors->first('mtc_titulo') }}</p> @endif
        </div>
    </div>
    <div class="form-group col-md-3 @if ($errors->has('mtc_data')) has-error @endif">
        <label for="mtc_data" class="form-label">Data <small class="obrigatorio-dot">*</small></label>
        <div class="controls">
            <input type="text" name="mtc_data" value="{{ old('mtc_data', $matrizCurricular->mtc_data) }}" class="form-control datepicker" data-provide="datepicker" date-date-format="dd/mm/yyyy" >
            @if ($errors->has('mtc_data')) <p class="help-block">{{ $errors->first('mtc_data') }}</p> @endif
        </div>
    </div>
</div>
<div class="row">
    <div class="form-group col-md-6 @if ($errors->has('mtc_file')) has-error @endif">
        <label for="mtc_file" class="form-label">Projeto Pedagógico</label>
        <div class="controls">
            <input type="file" name="mtc_file" class="form-control file" >
            @if ($errors->has('mtc_file')) <p class="help-block">{{ $errors->first('mtc_file') }}</p> @endif
        </div>
    </div>
    <div class="form-group col-md-6 @if ($errors->has('doc_anx_nome')) has-error @endif">
        <label for="doc_anx_nome" class="form-label">Anexo</label>
        <div class="input-group">
            @if($anexo != null)
                <input type="text" class="form-control first" placeholder="{{$anexo->anx_nome}}" disabled="">
                <span class="input-group-btn">
                    <button type="button" class="btn btn-danger btn-delete">Excluir</button>
                </span>
            @else
                <input type="text" class="form-control first" placeholder="Sem anexos" disabled="">
                <span class="input-group-btn">
                      <button type="button" class="btn btn-danger btn-delete" disabled="">Excluir</button>
                </span>
            @endif
        </div>
    </div>
</div>
<div class="row">
    <div class="form-group col-md-4 @if ($errors->has('mtc_horas')) has-error @endif">
        <label for="mtc_horas" class="form-label">Horas <small class="obrigatorio-dot">*</small></label>
        <div class="controls">
            <input type="number" name="mtc_horas" value="{{ old('mtc_horas', $matrizCurricular->mtc_horas) }}" class="form-control select-control" >
            @if ($errors->has('mtc_horas')) <p class="help-block">{{ $errors->first('mtc_horas') }}</p> @endif
        </div>
    </div>
    <div class="form-group col-md-4 @if ($errors->has('mtc_creditos')) has-error @endif">
        <label for="mtc_creditos" class="form-label">Créditos</label>
        <div class="controls">
            <input type="number" name="mtc_creditos" value="{{ old('mtc_creditos', $matrizCurricular->mtc_creditos) }}" class="form-control select-control" >
            @if ($errors->has('mtc_creditos')) <p class="help-block">{{ $errors->first('mtc_creditos') }}</p> @endif
        </div>
    </div>
    <div class="form-group col-md-4 @if ($errors->has('mtc_horas_praticas')) has-error @endif">
        <label for="mtc_horas_praticas" class="form-label">Horas Práticas</label>
        <div class="controls">
            <input type="number" name="mtc_horas_praticas" value="{{ old('mtc_horas_praticas', $matrizCurricular->mtc_horas_praticas) }}" class="form-control select-control" >
            @if ($errors->has('mtc_horas_praticas')) <p class="help-block">{{ $errors->first('mtc_horas_praticas') }}</p> @endif
        </div>
    </div>
</div>
<div class="row">
    <div class="form-group col-md-12 @if ($errors->has('mtc_descricao')) has-error @endif">
        <label for="mtc_descricao" class="form-label">Descrição</label>
        <div class="controls">
            <input type="text" name="mtc_descricao" value="{{ old('mtc_descricao', $matrizCurricular->mtc_descricao) }}" class="form-control select-control" >
            @if ($errors->has('mtc_descricao')) <p class="help-block">{{ $errors->first('mtc_descricao') }}</p> @endif
        </div>
    </div>
</div>

@section('scripts')
    <script>
        window.PageRoutes = {
            // Add Routes
        };
    </script>

    @vite('modulos/Academico/Resources/js/pages/matrizescurriculares/includes/formulario_edit.js')
@stop
