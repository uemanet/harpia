<div class="modal-dialog">
    <div class="modal-content">
        <div class="modal-header">
            <h4 class="modal-title">Aproveitamento de disciplinas</h4>
            <!-- Corrigido para o padrão BS5 com btn-close e data-bs-dismiss -->
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
            <form action="#" method="POST" id="form" role="form">
                @csrf
                <div class="row">
                    <div class="form-group col-md-12">
                        <label for="mof_observacao" class="form-label">Observação <small class="text-danger">*</small></label>
                        <div class="controls">
                            <textarea name="mof_observacao" class="form-control select-control" rows="4">{{ old('mof_observacao') }}</textarea>
                        </div>
                    </div>
                </div>

                @if(!$turma->trm_integrada and $tipo_avaliacao == 'Numérica')
                    <div class="row">
                        <div class="form-group col-md-12">
                            <label for="mof_mediafinal" class="form-label">Média Final <small class="text-danger">*</small></label>
                            <div class="controls">
                                <input type="number" name="mof_mediafinal" value="{{ old('mof_mediafinal') }}" class="form-control select-control" min="0" max="10" step="0.1" >
                            </div>
                        </div>
                    </div>
                @endif

                @if(!$turma->trm_integrada and $tipo_avaliacao == 'Conceitual')
                    <div class="row">
                        <div class="form-group col-md-12">
                            <label for="mof_conceito" class="form-label">Conceito <small class="text-danger">*</small></label>
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

                <div class="row mt-3">
                    <div class="form-group col-md-12 text-end">
                        <button type="submit" class="btn btn-primary btn-aproveitar">Salvar dados</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>