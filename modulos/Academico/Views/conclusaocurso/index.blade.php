@extends('layouts.modulos.default')

@section('stylesheets')
    <link rel="stylesheet" href="{{url('/')}}/css/plugins/select2.css">
@stop

@section('title')
    Conclusão de Curso
@stop

@section('content')
    <div class="row py-2">
        <div class="card card-primary card-outline p-0">
            <div class="card-header with-border">
                <h3 class="card-title">
                    <i class="fa fa-filter"></i> Filtrar Dados
                </h3>
                <!-- /.card-title -->
                <div class="card-tools pull-right">
                    <button type="button" class="btn btn-card-tool" data-widget="collapse">
                        <i class="fa fa-minus"></i>
                    </button>
                </div>
                <!-- /.card-tools -->
            </div>
            <!-- /.card-header -->
            <div class="card-body">
                <div class="row">
                    <form method="GET" action="#" class="d-flex w-100">
                        <div class="col-md-4 px-1">
                            <label for="crs_id">Curso <small class="obrigatorio-dot">*</small></label>
                            <div class="form-group">
                                <select name="crs_id" id="crs_id" class="form-control">
                                    <option value="">Escolha o curso</option>
                                    @foreach($cursos as $key => $value)
                                        <option value="{{ $key }}" {{ '' == $key ? 'selected' : '' }}>{{ $value }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-2 px-1">
                            <label for="ofc_id">Oferta de Curso <small class="obrigatorio-dot">*</small></label>
                            <div class="form-group">
                                <select name="ofc_id" id="ofc_id" class="form-control"></select>
                            </div>
                        </div>
                        <div class="col-md-2 px-1">
                            <label for="trm_id">Turma <small class="obrigatorio-dot">*</small></label>
                            <div class="form-group">
                                <select name="trm_id" id="trm_id" class="form-control"></select>
                            </div>
                        </div>
                        <div class="col-md-2 px-1">
                            <label for="pol_id">Polo <small class="obrigatorio-dot">*</small></label>
                            <div class="form-group">
                                <select name="pol_id" id="pol_id" class="form-control"></select>
                            </div>
                        </div>
                        <div class="col-md-2 px-1">
                            <label for="btn">&nbsp;</label>
                            <div class="form-group">
                                <input type="submit" id="btnBuscar" class="btn btn-primary w-100" value="Buscar">
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            <!-- /.card-body -->
        </div>
    </div>
    <div class="row py-2">
        <!-- /.card-primary -->
        <div class="card card-primary card-outline hidden" id="cardAlunos">
            <div class="card-header with-border">
                <h3 class="card-title">
                    <i class="fa fa-filter"></i> Lista de Alunos
                </h3>
                <!-- /.card-title -->
                <div class="card-tools pull-right">
                    <button type="button" class="btn btn-card-tool" data-widget="collapse">
                        <i class="fa fa-minus"></i>
                    </button>
                </div>
                <!-- /.card-tools -->
            </div>
            <!-- /.card-header -->
            <div class="card-body"></div>
            <!-- /.card-body -->
        </div>
    </div>
@stop

@section('scripts')
    <script>
        window.PageRoutes = {
            // Add Routes
        };
    </script>

    @vite('modulos/Academico/Resources/js/pages/conclusaocurso/index.js')
@stop