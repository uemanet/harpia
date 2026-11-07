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
        <select name="tut_id" id="tut_id" class="form-control"></select>
    </div>
    <div class="form-group col-md-1">
        <label for="" class="control-label"></label>
        <button class="btn btn-primary form-control" id="btnLocalizar"><i class="fa fa-search"></i></button>
    </div>
</div>

@section('scripts')
    <script>
        window.PageData = {
            asr_token: "{{ $monitoramento->asr_token }}",
            {{--timeclicks: "{{ $timeclicks }}",--}}
            {{--wsfunction: "{{ $wsfunction }}",--}}
            amb_url: "{{ $ambiente->amb_url }}",
        };
    </script>

    @vite('modulos/Monitoramento/Resources/js/pages/forumresponse/includes/formulario.js')
@endsection
