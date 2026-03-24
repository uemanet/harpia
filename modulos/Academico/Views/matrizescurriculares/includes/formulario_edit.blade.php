@php
    $anexo = $matrizCurricular->projeto()->get()->pop();
@endphp
<div class="row">
    <div class="form-group col-md-6 @if ($errors->has('mtc_crs_id')) has-error @endif">
        <label for="mtc_crs_id" class="form-label">Curso <small class="obrigatorio-dot">*</small></label>
        <div class="controls">
            <select name="mtc_crs_id" class="form-control">
                @foreach($curso as $key => $value)
                    <option value="{{ $key }}" {{ $cursoId == $key ? 'selected' : '' }}>{{ $value }}</option>
                @endforeach
            </select>
            @if ($errors->has('mtc_crs_id')) <p class="help-block">{{ $errors->first('mtc_crs_id') }}</p> @endif
        </div>
    </div>
    <div class="form-group col-md-3 @if ($errors->has('mtc_titulo')) has-error @endif">
        <label for="mtc_titulo" class="form-label">Título <small class="obrigatorio-dot">*</small></label>
        <div class="controls">
            <input type="text" name="mtc_titulo" value="{{ old('mtc_titulo') }}" class="form-control select-control" >
            @if ($errors->has('mtc_titulo')) <p class="help-block">{{ $errors->first('mtc_titulo') }}</p> @endif
        </div>
    </div>
    <div class="form-group col-md-3 @if ($errors->has('mtc_data')) has-error @endif">
        <label for="mtc_data" class="form-label">Data <small class="obrigatorio-dot">*</small></label>
        <div class="controls">
            <input type="text" name="mtc_data" value="{{ old('mtc_data') }}" class="form-control datepicker" data-provide="datepicker" date-date-format="dd/mm/yyyy" >
            @if ($errors->has('mtc_data')) <p class="help-block">{{ $errors->first('mtc_data') }}</p> @endif
        </div>
    </div>
</div>
<div class="row">
    <div class="form-group col-md-6 @if ($errors->has('mtc_file')) has-error @endif">
        <label for="mtc_file" class="form-label">Projeto Pedagógico</label>
        <div class="controls">
            <input type="file" name="mtc_file" class="form-control file" >
            @if ($errors->has('mtc_file')) <p class="help-block">{{ $errors->first('mtc_file') }}</p> @endif
        </div>
    </div>
    <div class="form-group col-md-6 @if ($errors->has('doc_anx_nome')) has-error @endif">
        <label for="doc_anx_nome" class="form-label">Anexo</label>
        <div class="input-group">
            @if($anexo != null)
                <input type="text" class="form-control first" placeholder="{{$anexo->anx_nome}}" disabled="">
                <span class="input-group-btn">
                    <button type="button" class="btn btn-danger btn-delete">Excluir</button>
                </span>
            @else
                <input type="text" class="form-control first" placeholder="Sem anexos" disabled="">
                <span class="input-group-btn">
                      <button type="button" class="btn btn-danger btn-delete" disabled="">Excluir</button>
                </span>
            @endif
        </div>
    </div>
</div>
<div class="row">
    <div class="form-group col-md-4 @if ($errors->has('mtc_horas')) has-error @endif">
        <label for="mtc_horas" class="form-label">Horas <small class="obrigatorio-dot">*</small></label>
        <div class="controls">
            <input type="number" name="mtc_horas" value="{{ old('mtc_horas') }}" class="form-control select-control" >
            @if ($errors->has('mtc_horas')) <p class="help-block">{{ $errors->first('mtc_horas') }}</p> @endif
        </div>
    </div>
    <div class="form-group col-md-4 @if ($errors->has('mtc_creditos')) has-error @endif">
        <label for="mtc_creditos" class="form-label">Créditos</label>
        <div class="controls">
            <input type="number" name="mtc_creditos" value="{{ old('mtc_creditos') }}" class="form-control select-control" >
            @if ($errors->has('mtc_creditos')) <p class="help-block">{{ $errors->first('mtc_creditos') }}</p> @endif
        </div>
    </div>
    <div class="form-group col-md-4 @if ($errors->has('mtc_horas_praticas')) has-error @endif">
        <label for="mtc_horas_praticas" class="form-label">Horas Práticas</label>
        <div class="controls">
            <input type="number" name="mtc_horas_praticas" value="{{ old('mtc_horas_praticas') }}" class="form-control select-control" >
            @if ($errors->has('mtc_horas_praticas')) <p class="help-block">{{ $errors->first('mtc_horas_praticas') }}</p> @endif
        </div>
    </div>
</div>
<div class="row">
    <div class="form-group col-md-12 @if ($errors->has('mtc_descricao')) has-error @endif">
        <label for="mtc_descricao" class="form-label">Descrição</label>
        <div class="controls">
            <input type="text" name="mtc_descricao" value="{{ old('mtc_descricao') }}" class="form-control select-control" >
            @if ($errors->has('mtc_descricao')) <p class="help-block">{{ $errors->first('mtc_descricao') }}</p> @endif
        </div>
    </div>
</div>
@section('scripts')
    <script type="text/javascript">
        $(function () {
            var matrizId = "{{$matrizCurricular->mtc_id}}"
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

                        var data = {mat_id: matrizId, _token : csrf_token};

                        $.harpia.showloading();

                        var result = false;

                        $.ajax({
                            type: 'POST',
                            url: "{{{ route('academico.async.matrizescurriculares.removeanexo') }}}",
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

