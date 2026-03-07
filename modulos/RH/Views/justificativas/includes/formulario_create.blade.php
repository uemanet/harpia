@section('stylesheets')
  <link rel="stylesheet" href="{{asset('/css/plugins/select2.css')}}">
@endsection
<div class="row">
    <div class="form-group col-md-2 @if ($errors->has('jus_horas')) has-error @endif">
        <label for="jus_horas" class="control-label">Quantidade de Horas*</label>
        <div class="controls">
            <input type="number" name="jus_horas" value="{{ old('jus_horas') }}" class="form-control" >
            @if ($errors->has('jus_horas')) <p class="help-block">{{ $errors->first('jus_horas') }}</p> @endif
        </div>
    </div>

    <div class="form-group col-md-2 @if ($errors->has('jus_data')) has-error @endif">
        <label for="jus_data" class="control-label">Data Inicial*</label>
        <div class="controls">
            <input type="text" name="jus_data" value="{{ old('jus_data') }}" class="form-control datepicker" data-provide="datepicker" date-date-format="dd/mm/yyyy" >
            @if ($errors->has('jus_data')) <p class="help-block">{{ $errors->first('jus_data') }}</p> @endif
        </div>
    </div>

    <div class="form-group col-md-2 @if ($errors->has('jus_data_fim')) has-error @endif">
        <label for="jus_data_fim" class="control-label">Data Final*</label>
        <div class="controls">
            <input type="text" name="jus_data_fim" value="{{ old('jus_data_fim') }}" class="form-control datepicker" data-provide="datepicker" date-date-format="dd/mm/yyyy" >
            @if ($errors->has('jus_data_fim')) <p class="help-block">{{ $errors->first('jus_data_fim') }}</p> @endif
        </div>
    </div>

    <div class="form-group col-md-4 @if ($errors->has('jus_file')) has-error @endif">
        <label for="jus_file" class="control-label">Anexo</label>
        <div class="controls">
            <input type="file" name="jus_file" class="form-control file" >
            @if ($errors->has('jus_file')) <p class="help-block">{{ $errors->first('jus_file') }}</p> @endif
        </div>
    </div>

</div>

<div class="row">

    <div class="form-group col-md-8 @if ($errors->has('jus_descricao')) has-error @endif">
        <label for="jus_descricao" class="control-label">Descrição</label>
        <div class="controls">
            <textarea name="jus_descricao" class="form-control" rows="4">{{ old('jus_descricao') }}</textarea>
            @if ($errors->has('jus_descricao')) <p class="help-block">{{ $errors->first('jus_descricao') }}</p> @endif
        </div>
    </div>

    <div class="form-group col-md-2 @if ($errors->has('jus_tipo_id')) has-error @endif">
        <label for="jus_tipo_id" class="control-label">Tipo de Justificativa*</label>
        <div class="controls">
            <select name="jus_tipo_id" class="form-control select2">
    @foreach($tipos as $key => $value)
        <option value="{{ $key }}">{{ $value }}</option>
    @endforeach
</select>
            @if ($errors->has('jus_tipo_id')) <p class="help-block">{{ $errors->first('jus_tipo_id') }}</p> @endif
        </div>
    </div>




</div>
<input type="hidden" name="jus_htr_id" value="{{ $horaTrabalhada->htr_id }}" class="form-control" >
<div class="row">
    <div class="form-group col-md-12">
        <button type="submit" class="btn btn-primary pull-right">Salvar dados</button>
    </div>
</div>
@section('scripts')
    @parent
    <script src="{{asset('/js/plugins/select2.js')}}" type="text/javascript"></script>

    <script type="text/javascript">
        $(document).ready(function() {
            $("select").select2();
        });
    </script>
@stop
