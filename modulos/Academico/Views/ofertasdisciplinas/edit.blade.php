@extends('layouts.modulos.default')

@section('title')
    Editar Oferta de Disciplina
@stop

@section('subtitle')
    {{$ofertaDisciplina->moduloDisciplina->disciplina->dis_nome}}
@stop

@section('content')
    <div class="row">
        <div class="card card-primary card-outline p-0">
        <div class="card-header">
            <h4 class="card-title">Editar</h4>
        </div>
        <form action="{{ route('academico.ofertasdisciplinas.edit', [$ofertaDisciplina->ofd_id]) }}" method="POST" role="form">
            @csrf
            @method('PUT')
            <div class="card-body">
                <div class="row">
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="disciplina">Disciplina <small class="obrigatorio-dot">*</small></label>
                            <input type="text" name="disciplina" value="{{ $ofertaDisciplina->moduloDisciplina->disciplina->dis_nome }}" class="form-control" disabled="disabled" >
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            <label for="carga_horaria">Carga Horária <small class="obrigatorio-dot">*</small></label>
                            <input type="text" name="carga_horaria" value="{{ $ofertaDisciplina->moduloDisciplina->disciplina->dis_carga_horaria." horas" }}" class="form-control" disabled="disabled" >
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            <label for="creditos">Créditos <small class="obrigatorio-dot">*</small></label>
                            <input type="text" name="creditos" value="{{ $ofertaDisciplina->moduloDisciplina->disciplina->dis_creditos }}" class="form-control" disabled="disabled" >
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="tipo_disciplina">Tipo da Disciplina <small class="obrigatorio-dot">*</small></label>
                            <input type="text" name="tipo_disciplina" value="{{ $ofertaDisciplina->moduloDisciplina->mdc_tipo_disciplina }}" class="form-control" disabled="disabled" >
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-4">
                        <div class="form-group @if($errors->has('ofd_tipo_avaliacao')) has-error @endif">
                            <label for="ofd_tipo_avaliacao">Tipo de Avaliação <small class="obrigatorio-dot">*</small></label>
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
                            <label for="ofd_qtd_vagas">Quantidade de Vagas <small class="obrigatorio-dot">*</small></label>
                            <input type="number" name="ofd_qtd_vagas" value="{{ old('ofd_qtd_vagas') }}" class="form-control" >
                            @if($errors->has('ofd_qtd_vagas')) <p class="help-block">{{ $errors->first('ofd_qtd_vagas') }}</p> @endif
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group @if($errors->has('ofd_prf_id')) has-error @endif">
                            <label for="ofd_prf_id">Professor <small class="obrigatorio-dot">*</small></label>
                            <select name="ofd_prf_id" class="form-control">
                                @foreach($professores as $key => $value)
                                    <option value="{{ $key }}" {{ old('ofd_prf_id') == $key ? 'selected' : '' }}>{{ $value }}</option>
                                @endforeach
                            </select>
                            @if($errors->has('ofd_prf_id')) <p class="help-block">{{ $errors->first('ofd_prf_id') }}</p> @endif
                        </div>
                    </div>
                </div>
            </div>
            <div class="card-footer">
                <button type="submit" class="btn btn-primary" style="float: right">Salvar Dados</button>
            </div>
        </form>
    </div>
@stop
