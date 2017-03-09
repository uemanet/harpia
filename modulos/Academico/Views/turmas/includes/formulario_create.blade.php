@section('stylesheets')
  <link rel="stylesheet" href="{{asset('/css/plugins/select2.css')}}">
@endsection
<div class="row">
    <div class="form-group col-md-4 @if ($errors->has('crs_id')) has-error @endif">
        {!! Form::label('crs_id', 'Curso*', ['class' => 'control-label']) !!}
        <div class="controls">
            {!! Form::select('crs_id', $curso, null, ['disabled', 'class' => 'form-control', 'id' => 'crs_id']) !!}
            @if ($errors->has('crs_id')) <p class="help-block">{{ $errors->first('crs_id') }}</p> @endif
        </div>
    </div>
    <div class="form-group col-md-4 @if ($errors->has('trm_ofc_id')) has-error @endif">
        {!! Form::label('trm_ofc_id', 'Oferta de Curso*', ['class' => 'control-label']) !!}
        <div class="controls">
            <select class="form-control" name="trm_ofc_id" id="trm_ofc_id">
                <option value="{{$oferta->ofc_id}}">{{$oferta->ofc_ano}} ({{$oferta->modalidade->mdl_nome}})</option>
            </select>
            @if ($errors->has('trm_ofc_id')) <p class="help-block">{{ $errors->first('trm_ofc_id') }}</p> @endif
        </div>
    </div>
    <div class="form-group col-md-4 @if ($errors->has('trm_per_id')) has-error @endif">
      {!! Form::label('trm_per_id', 'Período Letivo*', ['class' => 'control-label']) !!}
      <div class="controls">

          @if ($periodosletivos)
              {!! Form::select('trm_per_id', $periodosletivos, old('trm_per_id'), ['class' => 'form-control','placeholder' => 'Selecione o período' ]) !!}
              @else {!! Form::select('trm_per_id', $periodosletivos, old('trm_per_id'), ['class' => 'form-control','placeholder' => 'Sem Períodos Letivos Disponíveis' ]) !!}@endif
        @if ($errors->has('trm_per_id')) <p class="help-block">{{ $errors->first('trm_per_id') }}</p> @endif
      </div>
    </div>
</div>
<div class="row">
    <div class="form-group col-md-4 @if ($errors->has('trm_nome')) has-error @endif">
        {!! Form::label('trm_nome', 'Nome*', ['class' => 'control-label']) !!}
        <div class="controls">
            {!! Form::text('trm_nome', old('trm_nome'), ['class' => 'form-control']) !!}
            @if ($errors->has('trm_nome')) <p class="help-block">{{ $errors->first('trm_nome') }}</p> @endif
        </div>
    </div>
    <div class="form-group col-md-4 @if ($errors->has('trm_qtd_vagas')) has-error @endif">
        {!! Form::label('trm_qtd_vagas', 'Quantidade de Vagas*', ['class' => 'control-label']) !!}
        <div class="controls">
            {!! Form::number('trm_qtd_vagas', old('trm_qtd_vagas'), ['class' => 'form-control']) !!}
            @if ($errors->has('trm_qtd_vagas')) <p class="help-block">{{ $errors->first('trm_qtd_vagas') }}</p> @endif
        </div>
    </div>
    <div class="form-group col-md-4 @if ($errors->has('trm_integrada')) has-error @endif">
        {!! Form::label('trm_integrada', 'É integrada?*', ['class' => 'control-label']) !!}
        <div class="controls">
            {!! Form::select('trm_integrada', array('0' => 'Não', '1' => 'Sim'), null, ['class' => 'form-control', 'placeholder' => 'Selecione']) !!}
            @if ($errors->has('trm_integrada')) <p class="help-block">{{ $errors->first('trm_integrada') }}</p> @endif
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
        $(document).ready(function() {
            $("select").select2();
        });
    </script>
@stop
