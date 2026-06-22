<div class="row">
    <div class="form-group col-md-4 @if ($errors->has('scb_ccb_id')) has-error @endif">
        <label for="scb_ccb_id" class="form-label">Conta Colaborador*</label>
        <div class="controls">
            <select name="scb_ccb_id" class="form-control">
                <option value="">Selecione a conta do colaborador</option>
                @foreach($contas_colaborador as $key => $value)
                    <option value="{{ $key }}" {{ old('scb_ccb_id') == $key ? 'selected' : '' }}>{{ $value }}</option>
                @endforeach
            </select>
            @if ($errors->has('scb_ccb_id')) <p class="help-block">{{ $errors->first('scb_ccb_id') }}</p> @endif
        </div>
    </div>

    <div class="form-group col-md-4 @if ($errors->has('fpg_id')) has-error @endif">
        <label for="fpg_id" class="form-label">Fonte Pagadora*</label>
        <div class="controls">
            <select name="fpg_id" class="form-control">
                <option value="">Selecione a fonte pagadora</option>
                @foreach($fontes_pagadoras as $key => $value)
                    <option value="{{ $key }}" {{ old('fpg_id') == $key ? 'selected' : '' }}>{{ $value }}</option>
                @endforeach
            </select>
            @if ($errors->has('fpg_id')) <p class="help-block">{{ $errors->first('fpg_id') }}</p> @endif
        </div>
    </div>

    <div class="form-group col-md-4 @if ($errors->has('scb_vfp_id')) has-error @endif">
        <label for="scb_vfp_id" class="form-label">Forma de pagamento*</label>
        <div class="controls">
            <select name="scb_vfp_id" class="form-control">
                <option value="">Selecione o tipo de vínculo</option>
            </select>
            @if ($errors->has('scb_vfp_id')) <p class="help-block">{{ $errors->first('scb_vfp_id') }}</p> @endif
        </div>
    </div>

</div>

<div class="row">
    <div class=" form-group col-md-3 @if ($errors->has('scb_qtd_pagamento')) has-error @endif"  id= "unidade" >
        <label for="scb_qtd_pagamento" class="form-label">Qtd. Pagamento</label>
        <div class="controls">
            <input type="number" name="scb_qtd_pagamento" value="{{ old('scb_qtd_pagamento', $salario->scb_qtd_pagamento) }}" class="form-control" >
            @if ($errors->has('scb_qtd_pagamento')) <p class="help-block">{{ $errors->first('scb_qtd_pagamento') }}</p> @endif
        </div>
    </div>
    <div class="form-group col-md-3  @if ($errors->has('scb_valor')) has-error @endif">
        <label for="scb_valor" class="form-label">Valor Bruto (R$)*</label>
        <div class="controls">
            <input type="text" name="scb_valor" value="{{ old('scb_valor', $salario->scb_valor) }}" class="form-control" onkeyup="k(this);" >
            @if ($errors->has('scb_valor')) <p class="help-block">{{ $errors->first('scb_valor') }}</p> @endif
        </div>
    </div>

    <div class="form-group col-md-3 @if ($errors->has('scb_data_inicio')) has-error @endif">
        <label for="scb_data_inicio" class="form-label">Data Inicio Pag.*</label>
        <div class="controls">
            <input type="text" name="scb_data_inicio" value="{{ old('scb_data_inicio', $salario->scb_data_inicio) }}" class="form-control datepicker" data-provide="datepicker" date-date-format="dd/mm/yyyy" >
            @if ($errors->has('scb_data_inicio')) <p
                    class="help-block">{{ $errors->first('scb_data_inicio') }}</p> @endif
        </div>
    </div>
    <div class="form-group col-md-3 @if ($errors->has('scb_data_fim')) has-error @endif">
        <label for="scb_data_fim" class="form-label">Data Fim Pag.</label>
        <div class="controls">
            <input type="text" name="scb_data_fim" value="{{ old('scb_data_fim', $salario->scb_data_fim) }}" class="form-control datepicker" data-provide="datepicker" date-date-format="dd/mm/yyyy" >
            @if ($errors->has('scb_data_fim')) <p
                    class="help-block">{{ $errors->first('scb_data_fim') }}</p> @endif
        </div>
    </div>
</div>

@section('scripts')
    <script>
        window.PageRoutes = {
            {{--alterarsituacao: "{{ route('academico.async.matricula.alterarsituacao') }}"--}}
        };
    </script>

    @vite('modulos/RH/Resources/js/pages/salarioscolaboradores/formulario.js')
@stop
