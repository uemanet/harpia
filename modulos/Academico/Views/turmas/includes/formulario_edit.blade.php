@section('stylesheets')
  <link rel="stylesheet" href="{{asset('/css/plugins/select2.css')}}">
@endsection
<div class="row">
  <div class="form-group col-md-4 @if ($errors->has('crs_id')) has-error @endif">
      <label for="crs_id" class="control-label">Curso*</label>
      <div class="controls">
          <select name="crs_id" class="form-control" id="crs_id">
    @foreach($curso as $key => $value)
        <option value="{{ $key }}">{{ $value }}</option>
    @endforeach
</select>
          @if ($errors->has('crs_id')) <p class="help-block">{{ $errors->first('crs_id') }}</p> @endif
      </div>
  </div>
  <div class="form-group col-md-4 @if ($errors->has('trm_ofc_id')) has-error @endif">
      <label for="trm_ofc_id" class="control-label">Oferta de Curso*</label>
      <div class="controls">
          <select class="form-control" name="trm_ofc_id" id="trm_ofc_id">
              <option value="{{$oferta->ofc_id}}">{{$oferta->ofc_ano}} ({{$oferta->modalidade->mdl_nome}})</option>
          </select>
          @if ($errors->has('trm_ofc_id')) <p class="help-block">{{ $errors->first('trm_ofc_id') }}</p> @endif
      </div>
  </div>
    <div class="form-group col-md-4 @if ($errors->has('trm_per_id')) has-error @endif">
      <label for="trm_per_id" class="control-label">Período Letivo*</label>
      <div class="controls">
        <select name="trm_per_id" class="form-control">
    @foreach($periodosletivos as $key => $value)
        <option value="{{ $key }}" {{ old('trm_per_id') == $key ? 'selected' : '' }}>{{ $value }}</option>
    @endforeach
</select>
        @if ($errors->has('trm_per_id')) <p class="help-block">{{ $errors->first('trm_per_id') }}</p> @endif
      </div>
    </div>
</div>
<div class="row">
    <div class="form-group col-md-4 @if ($errors->has('trm_nome')) has-error @endif">
        <label for="trm_nome" class="control-label">Nome*</label>
        <div class="controls">
            <input type="text" name="trm_nome" value="{{ old('trm_nome') }}" class="form-control" >
            @if ($errors->has('trm_nome')) <p class="help-block">{{ $errors->first('trm_nome') }}</p> @endif
        </div>
    </div>
    <div class="form-group col-md-4 @if ($errors->has('trm_qtd_vagas')) has-error @endif">
        <label for="trm_qtd_vagas" class="control-label">Quantidade de Vagas*</label>
        <div class="controls">
            <input type="number" name="trm_qtd_vagas" value="{{ old('trm_qtd_vagas') }}" class="form-control" >
            @if ($errors->has('trm_qtd_vagas')) <p class="help-block">{{ $errors->first('trm_qtd_vagas') }}</p> @endif
        </div>
    </div>
    <div class="form-group col-md-4 @if ($errors->has('trm_integrada')) has-error @endif">
        <label for="trm_integrada" class="control-label">É integrada?*</label>
        <div class="controls">
            <select name="trm_integrada" class="form-control">
    <option value="">Selecione</option>
    @foreach(array('0' => 'Não', '1' => 'Sim') as $key => $value)
        <option value="{{ $key }}">{{ $value }}</option>
    @endforeach
</select>
            @if ($errors->has('trm_integrada')) <p class="help-block">{{ $errors->first('trm_integrada') }}</p> @endif
        </div>
    </div>
</div>
<div class="row">
    <div class="form-group col-md-12">
        <button type="submit" class="btn btn-primary pull-right">Salvar dados</button>
    </div>
</div>
@section('scripts')
    <script src="{{asset('/js/plugins/select2.js')}}" type="text/javascript"></script>

        <script type="text/javascript">
            $(document).ready(function() {
                $("select").select2();
            });
    </script>
@stop


