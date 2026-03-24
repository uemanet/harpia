@extends('layouts.modulos.default')

@section('title')
    Edição de Disciplina
@stop

@section('subtitle')
    Editar disciplina no módulo :: {{$curso->crs_nome}} :: {{$matriz->mtc_titulo}} :: {{ $modulo->mdo_nome }}
@stop

@section('content')
    <!-- Box Disciplinas Cadastradas no Módulo -->
    <div class="row">
        <div id="cardDisciplinasCadastradas" class="card card-primary card-outline">
            <div class="card-header with-border">
                <h3 class="card-title">
                    Dados da disciplina
                </h3>
                <div class="card-tools">
                    <button type="button" class="btn btn-tool" data-lte-toggle="card-collapse">
                        <i class="fa fa-minus"></i>
                    </button>
                </div>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="form-group col-md-12">
                        <label for="dis_nome" class="form-label">Nome da disciplina</label>
                        <div class="controls">
                            <input type="text" name="dis_nome" value="{{ $disciplina->disciplina->dis_nome }}" class="form-control" >
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="form-group col-md-4">
                        <label for="dis_carga_horaria" class="form-label">Carga-Horária</label>
                        <div class="controls">
                            <input type="number" name="dis_carga_horaria" value="{{ $disciplina->disciplina->dis_carga_horaria }}" class="form-control" >
                        </div>
                    </div>
                    <div class="form-group col-md-4">
                        <label for="dis_creditos" class="form-label">Créditos</label>
                        <div class="controls">
                            <input type="number" name="dis_creditos" value="{{ $disciplina->disciplina->dis_creditos }}" class="form-control" >
                        </div>
                    </div>
                    <div class="form-group col-md-4">
                        <label for="dis_nvc_id" class="form-label">Nível</label>
                        <div class="controls">
                            <input type="text" name="dis_nvc_id" value="{{ $disciplina->disciplina->nivel->nvc_nome }}" class="form-control" placeholder="Selecione o nível" >
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="form-group col-md-6">
                        <label for="mdc_tipo_disciplina" class="form-label">Tipo da Disciplina</label>
                        <div class="controls">
                            <select name="mdc_tipo_disciplina" id="mdc_tipo_disciplina" class="form-control">
                                @foreach($tipos as $key => $value)
                                    <option value="{{ $key }}" {{ $disciplina->getRawOriginal('mdc_tipo_disciplina') == $key ? 'selected' : '' }}>{{ $value }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="form-group col-md-6">
                        <label for="mdc_pre_requisitos" class="form-label">Pré-requisitos</label>
                        <div class="controls">
                            <select name="mdc_pre_requisitos" id="mdc_pre_requisitos" class="form-control" multiple>
                                @foreach($prerequisitosdisponiveis as $key => $value)
                                    <option value="{{ $key }}" {{ in_array($key, old('prerequisitos', isset($prerequisitos) ? $prerequisitos->toArray() : [])) ? 'selected' : '' }}>
                                        {{ $value }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="form-group col-md-12">
                        <button type="submit" class="btn btn-primary pull-right" id="btnSubmit">Atualizar dados</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
@stop

@section('scripts')
    <script type="application/javascript">
        $(function () {
            document.getElementById('btnSubmit').onclick = function (event) {
                event.preventDefault();

                var btn = this;
                btn.disabled = true;

                // Corrigido: buscando pelos IDs corretos do HTML
                var data = {
                    mdc_id: "{{ $disciplina->mdc_id }}",
                    mdc_tipo_disciplina: $('#mdc_tipo_disciplina').val(),
                    mdc_pre_requisitos: $('#mdc_pre_requisitos').val(),
                    _token: "{{ csrf_token() }}",
                    _method: "PUT"
                };

                // Corrigido: removido as 3 chaves do Blade (que era usado no Laravel 4)
                var url = "{{ route('academico.async.modulosdisciplinas.editardisciplina') }}";

                $.harpia.showloading();

                $.ajax({
                    type: "POST",
                    url: url,
                    data: data,
                    success: function (response) {
                        $.harpia.hideloading();
                        document.location.href = "{{ route('academico.cursos.matrizescurriculares.modulosmatrizes.gerenciardisciplinas', ['id' => $modulo->mdo_id]) }}";
                    },
                    error: function (err) {
                        $.harpia.hideloading();

                        var message = err.responseJSON ? err.responseJSON.message : "Erro ao atualizar a disciplina.";
                        toastr.error(message, null, {progressBar: true});

                        // Reabilita o botao
                        btn.disabled = false;
                    }
                });
            };
        });
    </script>
@endsection