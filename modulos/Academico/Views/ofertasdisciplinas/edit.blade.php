@extends('layouts.modulos.default')

@section('stylesheets')
    <link rel="stylesheet" href="{{asset('/css/plugins/select2.css')}}">
@endsection

@section('title')
    Editar Oferta de Disciplina
@stop

@section('subtitle')
    {{$ofertaDisciplina->moduloDisciplina->disciplina->dis_nome}}
@stop

@section('content')
    <div class="box box-primary">
        <div class="box-header">
            <h4 class="box-title">Editar</h4>
        </div>
        <div class="box-body">
            <div class="row">
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="disciplina">Disciplina*</label>
                        <input type="text" name="disciplina" value="{{ $ofertaDisciplina->moduloDisciplina->disciplina->dis_nome }}" class="form-control" disabled="disabled" >
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="form-group">
                        <label for="carga_horaria">Carga Horária*</label>
                        <input type="text" name="carga_horaria" value="{{ $ofertaDisciplina->moduloDisciplina->disciplina->dis_carga_horaria." horas" }}" class="form-control" disabled="disabled" >
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="form-group">
                        <label for="creditos">Créditos*</label>
                        <input type="text" name="creditos" value="{{ $ofertaDisciplina->moduloDisciplina->disciplina->dis_creditos }}" class="form-control" disabled="disabled" >
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="tipo_disciplina">Tipo da Disciplina*</label>
                        <input type="text" name="tipo_disciplina" value="{{ $ofertaDisciplina->moduloDisciplina->mdc_tipo_disciplina }}" class="form-control" disabled="disabled" >
                    </div>
                </div>
            </div>
            <form action="{{ route('academico.ofertasdisciplinas.edit', [$ofertaDisciplina->ofd_id]) }}" method="POST" role="form">
    @csrf
    @method('PUT')
    {{-- Form model: $ofertaDisciplina - inputs devem usar old('campo', $ofertaDisciplina->campo) --}}
                <div class="row">
                    <div class="col-md-4">
                        <div class="form-group @if($errors->has('ofd_tipo_avaliacao')) has-error @endif">
                            <label for="ofd_tipo_avaliacao">Tipo de Avaliação*</label>
                            <select name="ofd_tipo_avaliacao" class="form-control">
    <option value="">Selecione uma opção</option>
    <option value="numerica" {{ old('ofd_tipo_avaliacao') == 'numerica' ? 'selected' : '' }}>Numérica</option>
    <option value="conceitual" {{ old('ofd_tipo_avaliacao') == 'conceitual' ? 'selected' : '' }}>Conceitual</option>
</select>
                            @if($errors->has('ofd_tipo_avaliacao')) <p class="help-block">{{ $errors->first('ofd_tipo_avaliacao') }}</p> @endif
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group @if($errors->has('ofd_qtd_vagas')) has-error @endif">
                            <label for="ofd_qtd_vagas">Quantidade de Vagas*</label>
                            <input type="number" name="ofd_qtd_vagas" value="{{ old('ofd_qtd_vagas') }}" class="form-control" >
                            @if($errors->has('ofd_qtd_vagas')) <p class="help-block">{{ $errors->first('ofd_qtd_vagas') }}</p> @endif
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group @if($errors->has('ofd_prf_id')) has-error @endif">
                            <label for="ofd_prf_id">Professor*</label>
                            <select name="ofd_prf_id" class="form-control">
    @foreach($professores as $key => $value)
        <option value="{{ $key }}" {{ old('ofd_prf_id') == $key ? 'selected' : '' }}>{{ $value }}</option>
    @endforeach
</select>
                            @if($errors->has('ofd_prf_id')) <p class="help-block">{{ $errors->first('ofd_prf_id') }}</p> @endif
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group">
                            <button type="submit" class="btn btn-primary pull-right">Salvar dados</button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
@stop

@section('scripts')
    <script src="{{asset('/js/plugins/select2.js')}}" type="text/javascript"></script>

    <script type="text/javascript">
        $(function() {
            $("select").select2();
        });
    </script>
@endsection
