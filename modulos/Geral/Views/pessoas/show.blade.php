@extends('layouts.modulos.geral')

@section('title', 'Informações da Pessoa')

@section('content')
    <div class="row">
        <div class="col-md-12">
            <!-- About Me Box -->
            <div class="box box-primary">
                <div class="box-header with-border">
                    <h3 class="box-title">Dados Pessoais</h3>
                </div>
                <!-- /.box-header -->
                <div class="box-body">
                    <div class="row">
                        <div class="col-md-3">
                            <p><strong>Nome Completo: </strong> {{$pessoa->pes_nome}}</p>
                            <p><strong>Email: </strong> {{$pessoa->pes_email}}</p>
                            <p><strong>Sexo: </strong> {{$pessoa->pes_sexo}}</p>
                            <p><strong>Data de Nascimento: </strong> {{$pessoa->pes_nascimento}}</p>
                        </div>
                        <div class="col-md-3">
                            <p><strong>Nome da Mãe: </strong> {{$pessoa->pes_mae}}</p>
                            <p><strong>Nome do Pai: </strong> {{$pessoa->pes_pai}}</p>
                            <p><strong>Estado Civil: </strong> {{ucfirst($pessoa->pes_estado_civil)}}</p>
                            <p><strong>Naturalidade: </strong> {{$pessoa->pes_naturalidade}}</p>
                        </div>
                        <div class="col-md-3">
                            <p><strong>Nacionalidade: </strong> {{$pessoa->pes_nacionalidade}}</p>
                            <p><strong>Raça: </strong> {{ucfirst($pessoa->pes_raca)}}</p>
                            <p><strong>Necessidade Especial: </strong> {{$pessoa->pes_necessidade_especial}}</p>
                            <p><strong>Estrangeiro: </strong> {{$pessoa->pes_estrangeiro}}</p>
                        </div>
                    </div>
                </div>
                <!-- /.box-body -->
            </div>
            <!-- /.box -->
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <!-- About Me Box -->
            <div class="box box-primary">
                <div class="box-header with-border">
                    <h3 class="box-title">Documentos</h3>
                </div>
                <!-- /.box-header -->
                <div class="box-body">
                    @if(!empty($documentos))
                        <table class="table table-bordered">
                            <tr>
                                <th>Tipo</th>
                                <th>Numero do Documento</th>
                                <th>Orgão Emissor</th>
                                <th>Data de Emissão</th>
                                <th>Observação</th>
                            </tr>
                            @foreach($documentos as $documento)
                                <tr>
                                    <td>{{$documento->tipo}}</td>
                                    <td>{{$documento->conteudo}}</td>
                                    <td>{{$documento->orgao}}</td>
                                    <td>{{$documento->emissao}}</td>
                                    <td>{{$documento->observacao}}</td>
                                </tr>
                            @endforeach
                        </table>
                    @else
                        <p>Sem documentos para apresentar</p>
                    @endif
                </div>
                <!-- /.box-body -->
            </div>
            <!-- /.box -->
        </div>
    </div>

@endsection