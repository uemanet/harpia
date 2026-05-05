@extends('layouts.modulos.default')

@section('title')
    Adicionar Matriculas
@endsection

@section('subtitle')
    {{$lista->lst_nome}} - {{$lista->lst_descricao}}
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
                <form method="GET" action="#">
                    <div class="row">
                        <input type="hidden" name="lst_id" id="lst_id" value="{{$lista->lst_id}}">
                        <div class="col-md-3">
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
                        <div class="col-md-2">
                            <label for="trm_id">Turma <small class="obrigatorio-dot">*</small></label>
                            <div class="form-group">
                                <select name="trm_id" id="trm_id" class="form-control"></select>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <label for="pol_id">Polo <small class="obrigatorio-dot">*</small></label>
                            <div class="form-group">
                                <select name="pol_id" id="pol_id" class="form-control"></select>
                            </div>
                        </div>
                        <div class="col-md-1">
                            <label for="">&nbsp;</label>
                            <div class="form-group">
                                <button type="submit" id="btnBuscar" class="btn btn-primary w-100">
                                    <i class="fa fa-search"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <!-- /.card-body -->
        </div>
    </div>

    <div class="row py-2" id="listas"></div>
@endsection

@section('scripts')
    <script>
        window.PageRoutes = {
            // Add Routes
        };
    </script>

    @vite('modulos/Academico/Resources/js/pages/carteirasestudantis/addmatriculas.js')
@stop
