@extends('layouts.modulos.academico')

@section('title', 'Informações do Aluno')

@section('content')
    @include('Geral::pessoas.includes.show')

    <!-- Matriculas -->
    <div class="row">
        <div class="col-md-12">
            <div class="box box-primary">
                <div class="box-header with-border">
                    <h3 class="box-title">Cursos Matriculados</h3>

                    <div class="box-tools pull-right">
                        <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                        </button>
                    </div>
                    <!-- /.box-tools -->
                </div>
                <!-- /.box-header -->
                <div class="box-body">
                    @if(!$aluno->matriculas->isEmpty())
                        <table class="table table-bordered">
                            <tr>
                                <th>Curso</th>
                                <th>Oferta de Curso</th>
                                <th>Turma</th>
                                <th>Polo</th>
                                <th>Grupo</th>
                            </tr>
                            @foreach($aluno->matriculas as $matricula)
                                <tr>
                                    <td>{{$matricula->turma->ofertacurso->curso->crs_nome}}</td>
                                    <td>{{$matricula->turma->ofertacurso->ofc_ano}}</td>
                                    <td>{{$matricula->turma->trm_nome}}</td>
                                    <td>@if($matricula->polo) {{$matricula->polo->pol_nome}} @endif</td>
                                    <td>@if($matricula->grupo) {{$matricula->grupo->grp_nome}} @endif</td>
                                </tr>
                            @endforeach
                        </table>
                    @else
                        <p>Aluno não possui nenhuma matrícula</p>
                    @endif
                </div>
                <!-- /.box-body -->
            </div>
        </div>
    </div>

@endsection