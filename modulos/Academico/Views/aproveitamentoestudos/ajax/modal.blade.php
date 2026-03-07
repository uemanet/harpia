 <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">Aproveitamento de disciplinas</h4>
            </div>
            <div class="modal-body">
                        <form action="url(" method="POST" id="form" role="form">
    @csrf
                <div class="row">
                    <div class="form-group col-md-12">
                        <label for="mof_observacao" class="control-label">Observação*</label>
                        <div class="controls">
                            <textarea name="mof_observacao" class="form-control select-control" rows="4">{{ old('mof_observacao') }}</textarea>
                        </div>
                    </div>
                </div>

                @if(!$turma->trm_integrada and $tipo_avaliacao == 'Numérica')
                    <div class="row">
                        <div class="form-group col-md-12">
                            <label for="mof_mediafinal" class="control-label">Média Final*</label>
                            <div class="controls">
                                <input type="number" name="mof_mediafinal" value="{{ old('mof_mediafinal') }}" class="form-control" min="$mediaminima" max="10" step="0.1" >
                            </div>
                        </div>
                    </div>
                @endif

                @if(!$turma->trm_integrada and $tipo_avaliacao == 'Conceitual')
                    <div class="row">
                        <div class="form-group col-md-12">
                            <label for="mof_conceito" class="control-label">Conceito*</label>
                            <div class="controls">
                                <select name="mof_conceito" class="form-control">
    <option value="">Selecione o conceito</option>
    @foreach($conceitos as $key => $value)
        <option value="{{ $key }}" {{ old('mof_conceito') == $key ? 'selected' : '' }}>{{ $value }}</option>
    @endforeach
</select>
                            </div>
                        </div>
                    </div>
                @endif

                <div class="row">
                    <div class="form-group col-md-12">
                        <button type="submit" class="btn btn-primary pull-right btn-aproveitar">Salvar dados</button>
                    </div>
                </div>
                </form>
            </div>
        </div>
 </div>
