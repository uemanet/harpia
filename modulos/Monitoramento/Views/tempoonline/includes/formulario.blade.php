<div class="row">
    <div class="form-group col-md-3">
        <label for="crs_id" class="control-label">Curso*</label>
        <select name="crs_id" id="crs_id" class="form-control">
            <option value="">Escolha um curso</option>
            @foreach($cursos as $key => $value)
                <option value="{{ $key }}">{{ $value }}</option>
            @endforeach
        </select>
    </div>
    <div class="form-group col-md-3">
        <label for="ofc_id" class="control-label">Oferta do Curso*</label>
        <select name="ofc_id" id="ofc_id" class="form-control"></select>
    </div>
    <div class="form-group col-md-3">
        <label for="trm_id" class="control-label">Turma*</label>
        <select name="trm_id" id="trm_id" class="form-control"></select>
    </div>
    <div class="form-group col-md-3">
        <label for="grp_id" class="control-label">Tipo de Tutoria*</label>
        <select name="grp_id" id="grp_id" class="form-control"></select>
    </div>
</div>
<div class="row">
    <div class="form-group col-md-3">
        <label for="tut_id" class="control-label">Tutor*</label>
        <select name="tut_id" id="tut_id" class="form-control" multiple="multiple"></select>
    </div>
    <div class="form-group col-md-3">
        <label for="date_ini" class="control-label">Data de início*</label>
        <input type="text" name="date_ini" id="date_ini" value="{{ old('date_ini') }}" class="form-control datepicker" >
    </div>
    <div class="form-group col-md-3">
        <label for="date_fim" class="control-label">Data de fim*</label>
        <input type="text" name="date_fim" id="date_fim" value="{{ old('date_fim') }}" class="form-control datepicker"  >
    </div>
    <div class="form-group col-md-3">
        <label class="control-label" style="visibility: hidden">Botão</label>
        <div class="controls">
            <button type="submit" class="btn btn-primary">Visualizar informações</button>
        </div>
    </div>
</div>

@section('scripts')
    <script>
        window.PageData = {
            asr_token: "{{ $monitoramento->asr_token }}",
            timeclicks: "{{ $timeclicks }}",
            wsfunction: "{{ $wsfunction }}",
            amb_url: "{{ $ambiente->amb_url }}",
        };
    </script>

    @vite('modulos/Monitoramento/Resources/js/pages/tempoonline/includes/formulario.js')
@endsection
