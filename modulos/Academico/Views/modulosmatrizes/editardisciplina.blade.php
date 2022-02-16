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
                    {!! Form::label('dis_nome', 'Nome da disciplina', ['class' => 'control-label']) !!}
                    <div class="controls">
                        {!! Form::text('dis_nome', $disciplina->disciplina->dis_nome, ['class' => 'form-control', 'disabled']) !!}
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="form-group col-md-4">
                    {!! Form::label('dis_carga_horaria', 'Carga-Horária', ['class' => 'control-label']) !!}
                    <div class="controls">
                        {!! Form::number('dis_carga_horaria', $disciplina->disciplina->dis_carga_horaria, ['class' => 'form-control', 'disabled']) !!}
                    </div>
                </div>
                <div class="form-group col-md-4">
                    {!! Form::label('dis_creditos', 'Créditos', ['class' => 'control-label']) !!}
                    <div class="controls">
                        {!! Form::number('dis_creditos', $disciplina->disciplina->dis_creditos, ['class' => 'form-control', 'disabled']) !!}
                    </div>
                </div>
                <div class="form-group col-md-4">
                    {!! Form::label('dis_nvc_id', 'Nível', ['class' => 'control-label']) !!}
                    <div class="controls">
                        {!! Form::text('dis_nvc_id', $disciplina->disciplina->nivel->nvc_nome, ['class' => 'form-control', 'disabled', 'placeholder' => 'Selecione o nível']) !!}
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="form-group col-md-6">
                    {!! Form::label('tipo_disciplina', 'Tipo da Disciplina', ['class' => 'control-label']) !!}
                    <div class="controls">
                        {!! Form::select('tipo_disciplina', $tipos, $disciplina->getRawOriginal('mdc_tipo_disciplina'), ['class' => 'form-control']) !!}
                    </div>
                </div>
                <div class="form-group col-md-6">
                    {!! Form::label('pre_requisitos', 'Pré-requisitos', ['class' => 'control-label']) !!}
                    <div class="controls">
                        {!! Form::select('pre_requisitos', $prerequisitosdisponiveis, $prerequisitos, ['class' => 'form-control', 'multiple']) !!}
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="form-group col-md-12">
                    {!! Form::submit('Atualizar dados', ['class' => 'btn btn-primary pull-right', 'id' => 'btnSubmit']) !!}
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
                url = "{{ route('academico.async.modulosdisciplinas.editardisciplina') }}";

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
                        toastr.error(err.responseJSON.message, null, {progressBar: true});

                        // Reabilita o botao
                        btn.disabled = false;
                    }
                });
            };
        });
    </script>
@endsection