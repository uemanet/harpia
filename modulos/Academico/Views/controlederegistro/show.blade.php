@extends('layouts.modulos.academico')

@section('title')
    Controle de Registros
@stop

@section('subtitle')
    Detalhes do registro
@stop

@section('content')
    <!--  Dados Pessoais  -->
    <div class="row">
        <div class="col-md-12">
            <!-- About Me Box -->
            <div class="box box-primary">
                <div class="box-header with-border">
                    <h3 class="box-title">{{ $registro->liv_tipo_livro }}</h3>
                    <div class="box-tools pull-right">
                        <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                        </button>
                    </div>
                    <!-- /.box-tools -->
                </div>
                <!-- /.box-header -->
                <div class="box-body">
                    <div class="row">
                        <div class="col-md-5">
                            <p><strong>Aluno: </strong> {{$registro->pes_nome}}</p>
                            <p><strong>Email: </strong> {{$registro->pes_email}}</p>
                            <p><strong>Curso: </strong> {{$registro->crs_nome}}</p>
                        </div>
                        <div class="col-md-2">
                            <p><strong>Livro: </strong> {{ $registro->reg_liv_id }}</p>
                            <p><strong>Folha: </strong> {{ $registro->reg_folha }}</p>
                            <p><strong>Registro: </strong> {{ $registro->reg_registro }}</p>
                        </div>
                        <div class="col-md-5">
                            <p><strong>Código de Autenticidade: </strong> {{ $registro->reg_codigo_autenticidade }}</p>
                            <p><strong>Data: </strong> {{ date('d/m/Y', strtotime($registro->created_at)) }}</p>
                        </div>
                    </div>
                </div>
            </div>
            <!-- /.box -->
        </div>
    </div>
@stop