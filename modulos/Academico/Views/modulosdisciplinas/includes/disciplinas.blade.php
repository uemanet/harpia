
            {{$disciplina->dis_id}}
            {{$disciplina->dis_nome}}
            {{$disciplina->dis_carga_horaria}} horas
            {{$disciplina->dis_creditos}}

<div class="row">
      <div class="form-group col-md-4 @if ($errors->has('ofc_id')) has-error @endif">
            <div class="controls">
              Disciplina
              {!! Form::input('hidden', 'mdc_dis_id', $disciplina->dis_id, array('class'=>'form-control') ) !!}
                @if ($errors->has('ofc_id')) <p class="help-block">{{ $errors->first('ofc_id') }}</p> @endif
            </div>
      </div>
      <div class="form-group col-md-4 @if ($errors->has('ttg_tipo_tutoria')) has-error @endif">
          {!! Form::label('ttg_tipo_tutoria', 'Tipo de tutoria*', ['class' => 'control-label']) !!}
          <div class="controls">
              {!! Form::select('ttg_tipo_tutoria', array('presencial' => 'Presencial', 'distancia' => 'À Distância'),  ['class' => 'form-control', 'id' => 'ttg_tipo_tutoria', 'placeholder' => 'Selecione o tipo de tutoria']) !!}
              @if ($errors->has('ttg_tipo_tutoria')) <p class="help-block">{{ $errors->first('ttg_tipo_tutoria') }}</p> @endif
          </div>
      </div>
<div class="row">
   <div class="form-group col-md-12">
        {!! Form::submit('Salvar dados', ['class' => 'btn btn-primary pull-right']) !!}
   </div>
</div>
