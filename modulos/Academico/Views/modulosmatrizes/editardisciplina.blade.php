@extends('layouts.modulos.academico')

@section('stylesheets')
    <link rel="stylesheet" href="{{asset('/css/plugins/select2.css')}}">
@endsection

@section('title')
    Edição de Disciplina
@stop

@section('subtitle')
    Editar disciplina no módulo :: {{$curso->crs_nome}} :: {{$matriz->mtc_titulo}} :: {{ $modulo->mdo_nome }}
@stop

@section('content')
    <!-- Box Disciplinas Cadastradas no Módulo -->
    <div id="boxDisciplinasCadastradas" class="box box-primary">
        <div class="box-header with-border">
            <h3 class="box-title">
                Dados da disciplina
            </h3>
            <div class="box-tools pull-right">
                <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                </button>
            </div>
        </div>
        <div class="box-body">
            <div class="row">
                <div class="form-group col-md-12">
                    <label for="dis_nome" class="control-label">Nome da disciplina</label>
                    <div class="controls">
                        <input type="text" name="dis_nome" value="{{ $disciplina->disciplina->dis_nome }}" class="form-control" >
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="form-group col-md-4">
                    <label for="dis_carga_horaria" class="control-label">Carga-Horária</label>
                    <div class="controls">
                        <input type="number" name="dis_carga_horaria" value="{{ $disciplina->disciplina->dis_carga_horaria }}" class="form-control" >
                    </div>
                </div>
                <div class="form-group col-md-4">
                    <label for="dis_creditos" class="control-label">Créditos</label>
                    <div class="controls">
                        <input type="number" name="dis_creditos" value="{{ $disciplina->disciplina->dis_creditos }}" class="form-control" >
                    </div>
                </div>
                <div class="form-group col-md-4">
                    <label for="dis_nvc_id" class="control-label">Nível</label>
                    <div class="controls">
                        <input type="text" name="dis_nvc_id" value="{{ $disciplina->disciplina->nivel->nvc_nome }}" class="form-control" placeholder="Selecione o nível" >
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="form-group col-md-6">
                    <label for="tipo_disciplina" class="control-label">Tipo da Disciplina</label>
                    <div class="controls">
                        <select name="tipo_disciplina" class="form-control">
    @foreach($tipos as $key => $value)
        <option value="{{ $key }}" {{ $disciplina->getRawOriginal('mdc_tipo_disciplina') == $key ? 'selected' : '' }}>{{ $value }}</option>
    @endforeach
</select>
                    </div>
                </div>
                <div class="form-group col-md-6">
                    <label for="pre_requisitos" class="control-label">Pré-requisitos</label>
                    <div class="controls">
                        <select name="pre_requisitos" class="form-control">
    @foreach($prerequisitosdisponiveis as $key => $value)
        <option value="{{ $key }}" {{ $prerequisitos == $key ? 'selected' : '' }}>{{ $value }}</option>
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
@stop

@section('scripts')
    <script src="{{asset('/js/plugins/select2.js')}}" type="text/javascript"></script>

    <script type="application/javascript">
        $(function () {
            $(document).find('select').select2();

            document.getElementById('btnSubmit').onclick = function (event) {
                event.preventDefault();

                var btn = this;

                btn.disabled = true;

                data = {
                    mdc_id: "{{ $disciplina->mdc_id }}",
                    mdc_tipo_disciplina: $('#tipo_disciplina').val(),
                    mdc_pre_requisitos: $('#pre_requisitos').val(),
                    _token: "{{ csrf_token() }}",
                    _method: "PUT"
                };

                // Ajax request
                url = "{{{ route('academico.async.modulosdisciplinas.editardisciplina') }}}";

                $.ajax({
                    type: "POST",
                    url: url,
                    data: data,
                    success: function (response) {
                        $.harpia.hideloading();
                        document.location.href = "{{{ route('academico.cursos.matrizescurriculares.modulosmatrizes.gerenciardisciplinas', ['id' => $modulo->mdo_id]) }}}";
                    },
                    error: function (err) {
                        $.harpia.hideloading();
                        toastr.error(err.responseJSON.message, null, {progressBar: true});

                        // Reabilita o botao
                        btn.disabled = false;
                    }
                });
            };
        });
    </script>
@endsection