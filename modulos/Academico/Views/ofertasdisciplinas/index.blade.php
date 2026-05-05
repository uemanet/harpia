@extends('layouts.modulos.default')

@section('title')
    Ofertas de Disciplinas
@stop

@section('subtitle')
    Gerenciamento de ofertas de disciplinas
@stop

@section('actionButton')
    {!!ActionButton::render($actionButton)!!}
@stop

@section('stylesheets')
    <link rel="stylesheet" href="{{url('/')}}/css/plugins/select2.css">
@stop

@section('content')
    <div class="row py-2">
        <div class="card card-primary card-outline p-0">
            <div class="card-header with-border">
                <h3 class="card-title"><i class="fa fa-search"></i> Buscar Ofertas de Disciplinas</h3>

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
                    <div class="form-group col-md-3">
                        <label for="crs_id" class="form-label">Curso <small class="obrigatorio-dot">*</small></label>
                        <select name="crs_id" id="crs_id" class="form-control select2">
                            <option value="">Escolha um curso</option>
                            @foreach($cursos as $key => $value)
                                <option value="{{ $key }}">{{ $value }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group col-md-3">
                        <label for="ofc_id" class="form-label">Oferta do Curso <small class="obrigatorio-dot">*</small></label>
                        <select name="ofc_id" id="ofc_id" class="form-control select2"></select>
                    </div>
                    <div class="form-group col-md-3">
                        <label for="trm_id" class="form-label">Turma <small class="obrigatorio-dot">*</small></label>
                        <select name="trm_id" id="trm_id" class="form-control select2"></select>
                    </div>
                    <div class="form-group col-md-2">
                        <label for="per_id" class="form-label">Período Letivo <small class="obrigatorio-dot">*</small></label>
                        <select name="per_id" id="per_id" class="form-control select2"></select>
                    </div>
                    <div class="form-group col-md-1">
                        <label for="" class="form-label">&nbsp;</label>
                        <button class="btn btn-primary w-100" id="btnLocalizar"><i class="fa fa-search"></i></button>
                    </div>
                </div>
            </div>
            <!-- /.card-body -->
        </div>
    </div>

    <div class="row py-2" id="table-ofertas"></div>
@stop

@section('scripts')
    <script>
        window.PageRoutes = {
            // Add Routes
        };
    </script>

    @vite('modulos/Academico/Resources/js/pages/ofertasdisciplinas/index.js')
@stop