@extends('layouts.modulos.default')

@section('title')
    Matriculas em Lote
@endsection

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
                <form method="GET" action="">
                    <div class="row">
                        <div class="col-md-6">
                            <label for="crs_id">Curso <small class="obrigatorio-dot">*</small></label>
                            <div class="form-group">
                                <select name="crs_id" id="crs_id" class="form-control">
                                    <option value="">Escolha o Curso</option>
                                    @foreach($cursos as $key => $value)
                                        <option value="{{ $key }}">{{ $value }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <label for="ofc_id">Oferta de Curso <small class="obrigatorio-dot">*</small></label>
                            <div class="form-group">
                                <select name="ofc_id" id="ofc_id" class="form-control"></select>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <label for="trm_id">Turma <small class="obrigatorio-dot">*</small></label>
                            <div class="form-group">
                                <select name="trm_id" id="trm_id" class="form-control"></select>
                            </div>
                        </div>
                    </div>
                    <div class="row pt-2">
                        <div class="col-md-3">
                            <label for="per_id">Período Letivo <small class="obrigatorio-dot">*</small></label>
                            <div class="form-group">
                                <select name="per_id" id="per_id" class="form-control"></select>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <label for="ofd_id">Disciplinas Ofertadas <small class="obrigatorio-dot">*</small></label>
                            <div class="form-group">
                                <select name="ofd_id" id="ofd_id" class="form-control"></select>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <label for="pol_id">Polo</label>
                            <div class="form-group">
                                <select name="pol_id" id="pol_id" class="form-control"></select>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <label for="">&nbsp</label>
                            <div class="form-group">
                                <input type="submit" id="btnBuscar" class="btn btn-primary w-100" value="Buscar">
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <!-- /.card-body -->
        </div>
    </div>

    <div class="row py-2" id="alunos">
    </div>
@endsection

@section('scripts')
    <script>
        window.PageRoutes = {
            // Add Routes
        };
    </script>

    @vite('modulos/Academico/Resources/js/pages/matriculaslote/index.js')
@stop
