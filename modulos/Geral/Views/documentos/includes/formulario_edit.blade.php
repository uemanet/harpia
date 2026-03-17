<div class="row">
    <div class="form-group col-md-4 @if ($errors->has('doc_tpd_id')) has-error @endif">
        <label for="doc_tpd_id" class="form-label">Tipo de Documento*</label>
        <div class="controls">
            <select name="doc_tpd_id" class="form-control">
    @foreach($tiposdocumentos as $key => $value)
        <option value="{{ $key }}" {{ old('doc_tpd_id') == $key ? 'selected' : '' }}>{{ $value }}</option>
    @endforeach
</select>
            @if ($errors->has('doc_tpd_id')) <p class="help-block">{{ $errors->first('doc_tpd_id') }}</p> @endif
        </div>
    </div>
    <div class="form-group col-md-4 @if ($errors->has('doc_conteudo')) has-error @endif">
      <label for="doc_conteudo" class="form-label">Conteúdo*</label>
      <div class="controls">
        <input type="text" name="doc_conteudo" value="{{ old('doc_conteudo') }}" class="form-control" >
        @if ($errors->has('doc_conteudo')) <p class="help-block">{{ $errors->first('doc_conteudo') }}</p> @endif
      </div>
    </div>
    <div class="form-group col-md-2 @if ($errors->has('doc_orgao')) has-error @endif">
        <label for="doc_orgao" class="form-label">Órgão</label>
        <div class="controls">
            <input type="text" name="doc_orgao" value="{{ old('doc_orgao') }}" class="form-control" >
            @if ($errors->has('doc_orgao')) <p class="help-block">{{ $errors->first('doc_orgao') }}</p> @endif
        </div>
    </div>
    <div class="form-group col-md-2 @if ($errors->has('doc_data_expedicao')) has-error @endif">
        <label for="doc_data_expedicao" class="form-label">Data de expedição</label>
        <div class="controls">
            <input type="text" name="doc_data_expedicao" value="{{ old('doc_data_expedicao') }}" class="form-control datepicker" data-provide="datepicker" date-date-format="dd/mm/yyyy" >
            @if ($errors->has('doc_data_expedicao')) <p class="help-block">{{ $errors->first('doc_data_expedicao') }}</p> @endif
        </div>
    </div>
</div>
<div class="row">
    <div class="form-group col-md-6 @if ($errors->has('doc_file')) has-error @endif">
        <label for="doc_file" class="form-label">Documento</label>
        <div class="controls">
            <input type="file" name="doc_file" class="form-control file" >
            @if ($errors->has('doc_file')) <p class="help-block">{{ $errors->first('doc_file') }}</p> @endif
        </div>
    </div>
    <div class="form-group col-md-6 @if ($errors->has('doc_anx_nome')) has-error @endif">
        <label for="doc_anx_nome" class="form-label">Anexo</label>
        <div class="input-group">
            @if($anexo != null)
            <input type="text" class="form-control first" placeholder="{{$anexo->anx_nome}}" disabled="">
                <div class="input-group-btn botaoDelete">
                    <button type="button" class="btn btn-danger btn-delete">Excluir</button>
                </div>
            @else
            <input type="text" class="form-control" placeholder="Sem anexos" disabled="">
                <div class="input-group-btn botaoDelete">
                    <button type="button" class="btn btn-danger btn-delete" disabled="">Excluir</button>
                </div>
            @endif
        </div>
    </div>
</div>
<div class="row">
    <div class="form-group col-md-12 @if ($errors->has('doc_observacao')) has-error @endif">
        <label for="doc_observacao" class="form-label">Observação</label>
        <div class="controls">
            <textarea name="doc_observacao" class="form-control" rows="4">{{ old('doc_observacao') }}</textarea>
            @if ($errors->has('doc_observacao')) <p class="help-block">{{ $errors->first('doc_observacao') }}</p> @endif
        </div>
    </div>
</div>
<input type="hidden" name="doc_pes_id" value="{{ $pessoa->pes_id }}" class="form-control" >

@section('scripts')
    <script type="text/javascript">
        $(function () {

            var documentoId = "{{$documento->doc_id}}"
            var csrf_token = "{{csrf_token()}}";

            $(document).on('click', '.btn-delete', function (event) {
                event.preventDefault();

                swal({
                    title: "Tem certeza que deseja excluir?",
                    text: "Você não poderá recuperar essa informação!",
                    type: "warning",
                    showCancelButton: true,
                    confirmButtonColor: "#DD6B55",
                    confirmButtonText: "Sim, pode excluir!",
                    cancelButtonText: "Não, quero cancelar!",
                    closeOnConfirm: true
                }, function(isConfirm){
                    if (isConfirm) {

                        var data = {doc_id: documentoId, _token : csrf_token};

                        $.harpia.showloading();

                        var result = false;

                        $.ajax({
                            type: 'POST',
                            url: '/geral/async/anexos/deletaranexodocumento',
                            data: data,
                            success: function (data) {
                                $.harpia.hideloading();

                                toastr.success('Anexo excluído com sucesso!', null, {progressBar: true});
                                $(".botaoDelete").remove();
                                $(".first").attr("placeholder", "Sem anexo").val("").focus().blur();
                            },
                            error: function (xhr, textStatus, error) {
                                $.harpia.hideloading();

                                switch (xhr.status) {
                                    case 400:
                                        toastr.error('Sem anexos para serem excluídos!', null, {progressBar: true});
                                        break;
                                    default:
                                        toastr.error(xhr.responseText, null, {progressBar: true});

                                        result = false;
                                }
                            }
                        });
                    }
                });

            });

        });
    </script>
@endsection
