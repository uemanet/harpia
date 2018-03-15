 <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">Atualizar situação da matrícula</h4>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="form-group col-md-12">
                        {!! Form::open(["url" => url('/') . "/academico/aproveitamentoestudos/aproveitar", "method" => "POST", "id" => "form", "role" => "form"]) !!}
                    </div>
                </div>
                <div class="row">
                    <div class="form-group col-md-12">
                        {!! Form::label('mof_observacao', 'Observação', ['class' => 'control-label']) !!}
                        <div class="controls">
                            {!! Form::text('mof_observacao', old('mof_observacao'), ['class' => 'form-control select-control']) !!}
                        </div>
                    </div>
                </div>

                @if(!$turma->trm_integrada and $numerico)
                    <div class="row">
                        <div class="form-group col-md-12">
                            {!! Form::label('mof_mediafinal', 'Média Final*', ['class' => 'control-label']) !!}
                            <div class="controls">
                                {!! Form::number('mof_mediafinal', old('mof_mediafinal'), ['class' => 'form-control']) !!}
                            </div>
                        </div>
                    </div>
                @endif

                @if(!$turma->trm_integrada and !$numerico)
                    <div class="row">
                        <div class="form-group col-md-12">
                            {!! Form::label('mof_conceito', 'Conceito*', ['class' => 'control-label']) !!}
                            <div class="controls">
                                !! Form::select('mof_conceito', $conceitos, old('mof_conceito'), ['class' => 'form-control','placeholder' => 'Selecione o conceito' ]) !!}
                            </div>
                        </div>
                    </div>
                @endif

                <div class="row">
                    <div class="form-group col-md-6">
                        <button type="button" class="btn btn-default pull-left"
                                data-dismiss="modal">Cancelar
                        </button>
                    </div>
                    <div class="form-group col-md-6 text-right">
                        <button type="button" class="btn btn-primary modalSave">
                            Atualizar
                        </button>
                    </div>
                </div>
                {!! Form::close() !!}
            </div>
        </div>
 </div>
