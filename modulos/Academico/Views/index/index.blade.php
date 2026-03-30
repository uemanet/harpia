@extends('layouts.modulos.default')

@section('title')
    Módulo Acadêmico
@stop

@section('subtitle')
    Módulo de gerenciamento acadêmico
@stop

@section('content')
    <section class="content">
        <div class="row">
            <div class="col-lg-3 col-xs-6">
                <div class="small-box text-bg-info">
                    <div class="inner">
                        <h3>{{ $alunos }}</h3>
                        <p>Alunos</p>
                    </div>
                    <i class="small-box-icon fa fa-users" aria-hidden="true"></i>
                </div>
            </div>
            <div class="col-lg-3 col-xs-6">
                <div class="small-box text-bg-success">
                    <div class="inner">
                        <h3>{{ $matriculas }}</h3>
                        <p>Matrículas</p>
                    </div>
                    <i class="small-box-icon fa fa-graduation-cap" aria-hidden="true"></i>
                </div>
            </div>
            <div class="col-lg-3 col-xs-6">
                <div class="small-box text-bg-warning">
                    <div class="inner">
                        <h3>{{ $cursos }}</h3>
                        <p>Cursos</p>
                    </div>
                    <i class="small-box-icon fa fa-university" aria-hidden="true"></i>
                </div>
            </div>
            <div class="col-lg-3 col-xs-6">
                <div class="small-box text-bg-danger">
                    <div class="inner">
                        <h3>{{ $turmas }}</h3>
                        <p>Turmas</p>
                    </div>
                    <i class="small-box-icon fa fa-book" aria-hidden="true"></i>
                </div>
            </div>
        </div>

        <div class="row py-2">
            <div class="col-md-6">
                <div class="card card-primary card-outline card-outline">
                    <div class="card-header with-border">
                        <h3 class="card-title">Matrículas nos últimos 6 meses</h3>

                        <div class="card-tools">
                            <button type="button" class="btn btn-tool" data-lte-toggle="card-collapse">
                                <i class="fa fa-minus"></i>
                            </button>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="chart matriculasmes">
                            <canvas id="matriculasmes" width="undefined" height="undefined"></canvas>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card card-primary card-outline card-outline">
                    <div class="card-header with-border">
                        <h3 class="card-title">Cursos por nível</h3>

                        <div class="card-tools">
                            <button type="button" class="btn btn-tool" data-lte-toggle="card-collapse">
                                <i class="fa fa-minus"></i>
                            </button>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="chart curso">
                            <canvas id="curso" width="undefined" height="undefined"></canvas>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row py-2">
            <div class="col-md-12 text-center">
                <div class="card card-primary card-outline card-outline">
                    <div class="card-header with-border">
                        <h3 class="card-title">Matrículas por status</h3>

                        <div class="card-tools">
                            <button type="button" class="btn btn-tool" data-lte-toggle="card-collapse">
                                <i class="fa fa-minus"></i>
                            </button>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="chart matricula">
                            <canvas id="matricula" width="undefined" height="undefined"></canvas>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

@section('scripts')
    <script>
        window.PageRoutes = {
            cursopornivel: "{{ route("academico.async.dashboard.cursopornivel") }}",
            matriculasstatus: "{{ route("academico.async.dashboard.matriculasstatus") }}",
            matriculasmes: "{{ route("academico.async.dashboard.matriculasmes") }}"
        };
    </script>

    @vite('modulos/Academico/Resources/js/pages/index/index.js')
@endsection
