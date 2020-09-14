@extends('layouts.modulos.matriculas-alunos')

@section('title')
    Matrícula Online
@stop

@section('subtitle')
    Matrícula Online
@stop

@section('content')

    <div class="row">
        <div class="col-md-12">
            <!-- About Me Box -->
            <div class="box box-primary">
                <div class="box-header with-border">
                    <h3 class="box-title">Seletivos</h3>
                    <div class="box-tools pull-right">
                        <button type="button" class="btn btn-box-tool" data-widget="collapse"><i
                                    class="fa fa-minus"></i>
                        </button>
                    </div>
                    <!-- /.box-tools -->
                </div>
                <!-- /.box-header -->
                <div class="box-body">
                    <table class="table table-bordered">
                        <tbody>
                        <tr>
                            <th style="width: 35%">Seletivo</th>
                            <th>Período para confirmação</th>
                            <th>Chamada</th>
                            <th>Tipo de Chamada</th>
                            <th></th>
                        </tr>
                        @foreach($user->seletivos_matriculas as $item)
                            <tr>
                                <td>{{$item->chamada->seletivo->nome}}</td>
                                <td>{{date("d/m/Y h:i:s", strtotime($item->chamada->inicio_matricula)).' a '.date("d/m/Y h:i:s", strtotime($item->chamada->fim_matricula))}}</td>
                                <td>{{$item->chamada->nome}}</td>
                                <td>{{$item->chamada->tipo_chamada}}</td>
                                <td>
                                    <table>
                                        <tbody>
                                        <tr>
                                            <td style="padding-right: 5px">
                                                @if(!$item->matriculado and strtotime($item->chamada->inicio_matricula) < strtotime("now") and  strtotime($item->chamada->fim_matricula) > strtotime("now"))
                                                    <div class="btn-group"><a
                                                                href="{{ route('matriculas-alunos.seletivo-matricula.confirmar', $item->id) }}"
                                                                class="btn btn-success btn-sm"> Confirmar matrícula
                                                        </a>
                                                    </div>
                                                @elseif ($item->matriculado)
                                                    <div class="btn-group"><a
                                                                href="{{ route('matriculas-alunos.seletivo-matricula.comprovante', $item->id) }}"
                                                                class="btn btn-success btn-sm"><i class="fa fa-download"></i> Comprovante de Matrícula
                                                        </a>
                                                    </div>
                                                @elseif (!$item->matriculado and   strtotime($item->chamada->fim_matricula) < strtotime("now"))
                                                    <span class="label label-warning">Período para confirmação de matrícula encerrado</span>
                                                @endif
                                            </td>
                                        </tr>
                                        </tbody>
                                    </table>
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                    <!-- /.box-body -->

                </div>
            </div>
            <!-- /.box -->
        </div>
    </div>
@stop