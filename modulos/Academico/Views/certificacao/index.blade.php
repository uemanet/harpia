@extends('layouts.modulos.default')

@section('title')
    Certificação
@stop

@section('subtitle')
    Gerenciamento de registros
@stop

@section('content')
    <div class="row py-2">
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
                    <div class="form-group col-md-3 px-1">
                        <label for="crs_id" class="form-label">Curso <small class="obrigatorio-dot">*</small></label>
                        <select name="crs_id" id="crs_id" class="form-control">
                            <option value="">Escolha um curso</option>
                            @foreach($cursos as $key => $value)
                                <option value="{{ $key }}">{{ $value }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group col-md-2 px-1">
                        <label for="ofc_id" class="form-label">Oferta <small class="obrigatorio-dot">*</small></label>
                        <select name="ofc_id" class="form-control" id="ofc_id" value="Request">                    </select>
                    </div>
                    <div class="form-group col-md-2 px-1">
                        <label for="trm_id" class="form-label">Turma <small class="obrigatorio-dot">*</small></label>
                        <select name="trm_id" class="form-control" id="trm_id" value="Request"></select>
                    </div>
                    <div class="form-group col-md-2 px-1">
                        <label for="mdo_id" class="form-label">Módulo <small class="obrigatorio-dot">*</small></label>
                        <select name="mdo_id" class="form-control" id="mdo_id" value="Request"></select>
                    </div>
                    <div class="form-group col-md-2 px-1">
                        <label for="pol_id" class="form-label">Polo</label>
                        <select name="pol_id" class="form-control" id="pol_id" value="Request"></select>
                    </div>
                    <div class="form-group col-md-1 px-1">
                        <label for="" class="form-label"></label>
                        <button class="btn btn-primary w-100" id="btnLocalizar"><i class="fa fa-search"></i></button>
                    </div>
                </div>
            </div>
            <!-- /.card-body -->
        </div>
    </div>

    <div class="row py-2">
        <div class="card card-primary card-outline hidden" id="cardAlunos">
            <div class="card-header with-border">
                <h3 class="card-title">Lista de Alunos</h3>

                <div class="card-tools">
                    <button type="button" class="btn btn-tool" data-lte-toggle="card-collapse">
                        <i class="fa fa-minus"></i>
                    </button>
                </div>
                <!-- /.card-tools -->
            </div>
            <!-- /.card-header -->
            <div class="card-body"></div>
        </div>
    </div>
@stop

@section('scripts')
    <script>
        window.PageRoutes = {
            // Add Routes
        };
    </script>

    @vite('modulos/Academico/Resources/js/pages/certificacao/index.js')
@stop
