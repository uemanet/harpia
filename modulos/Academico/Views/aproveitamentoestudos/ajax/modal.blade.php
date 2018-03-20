 <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">Aproveitamento de disciplinas</h4>
            </div>
            <div class="modal-body">
                        {!! Form::open(["url" => url('/') . "/academico/aproveitamentoestudos/aproveitar/". $ofertaId . "/" . $matriculaId, "method" => "POST", "id" => "form", "role" => "form"]) !!}
                <div class="row">
                    <div class="form-group col-md-12">
                        {!! Form::label('mof_observacao', 'Observação*', ['class' => 'control-label']) !!}
                        <div class="controls">
                            {!! Form::textarea('mof_observacao', old('mof_observacao'), ['class' => 'form-control select-control', 'rows' => '4', 'required']) !!}
                        </div>
                    </div>
                </div>

                @if(!$turma->trm_integrada and $tipo_avaliacao == 'Numérica')
                    <div class="row">
                        <div class="form-group col-md-12">
                            {!! Form::label('mof_mediafinal', 'Média Final*', ['class' => 'control-label' ]) !!}
                            <div class="controls">
                                {!! Form::number('mof_mediafinal', old('mof_mediafinal'),['class' => 'form-control', 'min' => 0, 'max' => 10, 'step' => 0.1, 'required']) !!}
                            </div>
                        </div>
                    </div>
                @endif

                @if(!$turma->trm_integrada and $tipo_avaliacao == 'Conceitual')
                    <div class="row">
                        <div class="form-group col-md-12">
                            {!! Form::label('mof_conceito', 'Conceito*', ['class' => 'control-label']) !!}
                            <div class="controls">
                                {!! Form::select('mof_conceito', $conceitos, old('mof_conceito'), ['class' => 'form-control','placeholder' => 'Selecione o conceito', 'required']) !!}
                            </div>
                        </div>
                    </div>
                @endif

                <div class="row">
                    <div class="form-group col-md-12">
                        {!! Form::submit('Salvar dados', ['class' => 'btn btn-primary pull-right btn-aproveitar']) !!}
                    </div>
                </div>
                {!! Form::close() !!}
            </div>
        </div>
 </div>
