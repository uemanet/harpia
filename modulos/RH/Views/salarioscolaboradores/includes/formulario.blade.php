<div class="row">
    <div class="form-group col-md-4 @if ($errors->has('scb_ccb_id')) has-error @endif">
        {!! Form::label('scb_ccb_id', 'Conta Colaborador*', ['class' => 'control-label']) !!}
        <div class="controls">
            {!! Form::select('scb_ccb_id', $contas_colaborador, old('scb_ccb_id'), ['class' => 'form-control', 'placeholder' => 'Selecione a conta do colaborador']) !!}
            @if ($errors->has('scb_ccb_id')) <p class="help-block">{{ $errors->first('scb_ccb_id') }}</p> @endif
        </div>
    </div>

    <div class="form-group col-md-4 @if ($errors->has('fpg_id')) has-error @endif">
        {!! Form::label('fpg_id', 'Fonte Pagadora*', ['class' => 'control-label']) !!}
        <div class="controls">
            {!! Form::select('fpg_id', $fontes_pagadoras, old('fpg_id'), ['class' => 'form-control', 'placeholder' => 'Selecione a fonte pagadora']) !!}
            @if ($errors->has('fpg_id')) <p class="help-block">{{ $errors->first('fpg_id') }}</p> @endif
        </div>
    </div>

    <div class="form-group col-md-4 @if ($errors->has('scb_vfp_id')) has-error @endif">
        {!! Form::label('scb_vfp_id', 'Forma de pagamento*', ['class' => 'control-label']) !!}
        <div class="controls">
            {!! Form::select('scb_vfp_id', [], old('scb_vfp_id'), ['class' => 'form-control', 'placeholder' => 'Selecione o tipo de vínculo']) !!}
            @if ($errors->has('scb_vfp_id')) <p class="help-block">{{ $errors->first('scb_vfp_id') }}</p> @endif
        </div>
    </div>

</div>

<div class="row">
    <div class=" form-group col-md-3 @if ($errors->has('scb_qtd_pagamento')) has-error @endif"  id= "unidade" >
        {!! Form::label('scb_qtd_pagamento', 'Qtd. Pagamento', ['class' => 'control-label']) !!}
        <div class="controls">
            {!! Form::number('scb_qtd_pagamento', old('scb_qtd_pagamento'), ['min' => 1, 'max' => 9999, 'maxlength' => 4 ,'class' => 'form-control',  'oninput'=>"javascript: if (this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength);"]) !!}
            @if ($errors->has('scb_qtd_pagamento')) <p class="help-block">{{ $errors->first('scb_qtd_pagamento') }}</p> @endif
        </div>
    </div>

    <div class="form-group col-md-3  @if ($errors->has('scb_valor')) has-error @endif">
        {!! Form::label('scb_valor', 'Valor Bruto (R$)*', ['class' => 'control-label']) !!}
        <div class="controls">
            {!! Form::text('scb_valor', old('scb_valor'), ['class' => 'form-control', "onkeyup" =>"k(this);"]) !!}
            @if ($errors->has('scb_valor')) <p class="help-block">{{ $errors->first('scb_valor') }}</p> @endif
        </div>
    </div>

    <div class="form-group col-md-3 @if ($errors->has('scb_data_inicio')) has-error @endif">
        {!! Form::label('scb_data_inicio', 'Data Inicio Pag.*', ['class' => 'control-label']) !!}
        <div class="controls">
            {!! Form::text('scb_data_inicio',old('scb_data_inicio'), ['class' => 'form-control datepicker', 'data-provide' => 'datepicker', 'date-date-format' => 'dd/mm/yyyy']) !!}
            @if ($errors->has('scb_data_inicio')) <p
                    class="help-block">{{ $errors->first('scb_data_inicio') }}</p> @endif
        </div>
    </div>
    <div class="form-group col-md-3 @if ($errors->has('scb_data_fim')) has-error @endif">
        {!! Form::label('scb_data_fim', 'Data Fim Pag.', ['class' => 'control-label']) !!}
        <div class="controls">
            {!! Form::text('scb_data_fim',old('scb_data_fim'), ['class' => 'form-control datepicker', 'data-provide' => 'datepicker', 'date-date-format' => 'dd/mm/yyyy']) !!}
            @if ($errors->has('scb_data_fim')) <p
                    class="help-block">{{ $errors->first('scb_data_fim') }}</p> @endif
        </div>
    </div>
</div>
<div class="row">
    <div class="form-group col-md-12">
        <label class="control-label" style="visibility: hidden">Botão</label>
        <div class="controls">
            {!! Form::submit('Salvar dados', ['class' => 'btn btn-primary pull-right']) !!}
        </div>
    </div>
</div>


@section('scripts')
    @parent


    <script type="application/javascript">
        $(document).ready(function(){
            $('#fpg_id').prop('selectedIndex',0);

            $("#unidade").hide();

            $('#fpg_id').change(function() {

                var fpgId = $("#fpg_id").val();

                if (!fpgId) {
                    return;
                }

                $.harpia.httpget('{{url('/')}}/rh/async/fontespagadoras/' + fpgId +'/vinculosfontespagadoras').done(function(result){

                    $("#scb_vfp_id").empty();

                    if ($.isEmptyObject(result)) {
                        $('#scb_vfp_id').append('<option value=#>Sem formas de pagamentos cadastradas</option>');
                    } else {
                        $("#scb_vfp_id").append("<option value='' selected>Selecione uma forma de pagamento</option>");
                        $.each(result, function(key, value) {

                            if(value.vin_descricao == "Bolsa"){

                                if(value.vfp_unidade == 1){
                                    unitario = 'Sim';
                                } else{
                                    unitario = 'Não';
                                }



                                $('#scb_vfp_id').append('<option value=' + value.vfp_id + ' data-valor=' + value.vfp_valor + ' data-uni=' + unitario + '>' + value.vin_descricao + " | Salário Base (R$"+ value.vfp_valor + ") Uni:"+ unitario +'</option>');
                            } else{
                                $('#scb_vfp_id').append('<option value=' + value.vfp_id + ' >' + value.vin_descricao + '</option>');
                            }
                        });
                    }

                    $('#scb_vfp_id').focus();
                });
            });


            $('#scb_vfp_id').change(function (e){
                e.preventDefault();

                $("#scb_qtd_pagamento").val(0);
                $("#scb_valor").val(0);

                var unidade = $(e.currentTarget).find(":selected").data('uni');
                valor = parseFloat($(e.currentTarget).find(":selected").data('valor'));
                if(unidade == 'Não'){
                    $("#unidade").hide();
                    $('#scb_valor').focus();
                    $('#scb_valor').removeAttr('readonly');
                }else{

                    $("#unidade").show();
                    $('#scb_valor').attr('readonly','readonly');
                    $('#scb_qtd_pagamento').focus();
                }
            });

            $('#scb_qtd_pagamento').change(function (e){
                var uni = parseInt($('#scb_qtd_pagamento').val());

                var valor = parseFloat($("#scb_vfp_id").find(":selected").data('valor'));

                var valorFinal = uni * valor;
                valorFinal = valorFinal.toFixed(2);
                $("#scb_valor").val(valorFinal);
            });

        });
    </script>
    <script type="application/javascript">


    </script>
@stop
