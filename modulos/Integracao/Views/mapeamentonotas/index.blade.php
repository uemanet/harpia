@extends('layouts.modulos.default')

@section('title')
    Mapeamento de Notas
@stop

@section('content')
    <div class="row">
        <div class="card card-primary card-outline p-0">
            <div class="card-header with-border">
                <h3 class="card-title"><i class="fa fa-filter"></i> Filtrar dados</h3>

                <div class="card-tools">
                    <button type="button" class="btn btn-tool" data-lte-toggle="card-collapse">
                        <i class="fa fa-minus"></i>
                    </button>
                </div>
                <!-- /.card-tools -->
            </div>
            <!-- /.card-header -->
            <div class="card-body">
                <div class="row">
                <form method="POST" class="d-flex w-100" action="">
                    @csrf
                    <div class="col-md-3 px-1 form-group @if($errors->has('crs_id'))has-error @endif">
                        <label for="crs_id">Curso*</label>
                        <select name="crs_id" id="crs_id" class="form-control">
                            <option value="">Escolha o curso</option>
                            @foreach($cursos as $key => $value)
                                <option value="{{ $key }}" {{ '' == $key ? 'selected' : '' }}>{{ $value }}</option>
                            @endforeach
                        </select>
                        @if ($errors->has('crs_id')) <p class="help-block">{{ $errors->first('crs_id') }}</p> @endif
                    </div>
                    <div class="col-md-3 px-1 form-group @if($errors->has('ofc_id'))has-error @endif">
                        <label for="ofc_id">Oferta de Curso*</label>
                        <select name="ofc_id" id="ofc_id" class="form-control"></select>
                        @if ($errors->has('ofc_id')) <p class="help-block">{{ $errors->first('ofc_id') }}</p> @endif
                    </div>
                    <div class="col-md-3 px-1 form-group @if($errors->has('trm_id'))has-error @endif">
                        <label for="trm_id">Turma*</label>
                        <select name="trm_id" id="trm_id" class="form-control"></select>
                        @if ($errors->has('trm_id')) <p class="help-block">{{ $errors->first('trm_id') }}</p> @endif
                    </div>
                    <div class="col-md-3 px-1">
                        <label for="btn">&nbsp;</label>
                        <div class="form-group">
                            <input type="submit" id="btnBuscar" class="btn btn-primary w-100" value="Buscar">
                        </div>
                    </div>
                </form>
            </div>
        </div>
        <!-- /.box-body -->
    </div>
    </div>

    <div class="row" id="disciplinas"></div>
@stop

@section('scripts')
    <script>
        window.PageRoutes = {
            mapeamentonotas: "{{ route('integracao.mapeamentonotas.index') }}"
        };
    </script>

    @vite('modulos/Integracao/Resources/js/pages/mapeamentonotas/index.js')
@stop