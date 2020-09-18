@extends('layouts.modulos.matriculas-alunos')

@section('title')
    Matrícula Online
@stop

@section('subtitle')
    Matrícula Online
@stop

@section('content')

@section('stylesheets')
    <style>
        .title-box {
            display: inline-block;
            font-size: 18px;
            margin: 0;
            line-height: 1;
            font-family: 'Source Sans Pro', sans-serif;
        }
    </style>
@stop


<!--  Dados do Seletivo  -->
<div class="row">
    <div class="col-md-12">
        <!-- About Me Box -->
        <div class="box box-primary">
            <div class="box-header with-border">
                <h3 class="box-title">Dados do Seletivo</h3>

                <div class="box-tools pull-right">
                    <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                    </button>
                </div>
                <!-- /.box-tools -->
            </div>
            <!-- /.box-header -->
            <div class="box-body">
                <div class="row">
                    <div class="col-md-8">
                        <p><strong>Nome: </strong> {{$seletivo_matricula->chamada->seletivo->nome}}</p>
                        <p><strong>Período para
                                confirmação: </strong> {{date("d/m/Y h:i:s", strtotime($seletivo_matricula->chamada->inicio_matricula)).' a '.date("d/m/Y h:i:s", strtotime($seletivo_matricula->chamada->fim_matricula))}}
                        </p>
                        <p><strong>Chamada: </strong> {{ $seletivo_matricula->chamada->nome }}</p>
                        <p><strong>Tipo de chamada: </strong> {{ $seletivo_matricula->chamada->tipo_chamada}}</p>

                    </div>

                </div>

            </div>
            <!-- /.box-body -->
        </div>
        <!-- /.box -->
    </div>

</div>

<!--  Dados Pessoais  -->
<div class="row">
    <div class="col-md-12">
        <!-- About Me Box -->
        <div class="box box-primary">
            <div class="box-header with-border">
                <h3 class="box-title">Dados Pessoais</h3>

                <div class="box-tools pull-right">
                    <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                    </button>
                </div>
                <!-- /.box-tools -->
            </div>
            <!-- /.box-header -->
            <div class="box-body">
                <div class="row">
                    <div class="col-md-4">
                        <p><strong>Nome Completo: </strong> {{$user->nome}}</p>
                        <p><strong>Email: </strong> {{$user->email}}</p>
                        <p><strong>Telefone: </strong> {{Format::mask($user->telefone, '(##) #####-####')}}</p>
                        <p><strong>Celular: </strong> {{Format::mask($user->celular, '(##) #####-####')}}</p>
                        <p><strong>Sexo: </strong> {{($user->sexo == 'M') ? 'Masculino' : 'Feminino' }}</p>
                        <p><strong>Estado Civil: </strong> {{ucfirst($user->estado_civil)}}</p>
                    </div>
                    <div class="col-md-4">
                        <p><strong>Data de Nascimento: </strong> {{$user->nascimento}}</p>
                        <p><strong>Nome da Mãe: </strong> {{$user->mae}}</p>
                        <p><strong>Nome do Pai: </strong> {{$user->pai}}</p>
                    </div>
                    <div class="col-md-4">
                        <p><strong>Estrangeiro: </strong> {{($user->pes_estrangeiro) ? 'Sim' : 'Não'}}</p>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <div class="box box-primary">
                            <div class="box-header with-border">
                                <h3 class="box-title">Endereço</h3>
                            </div>
                            <div class="box-body">
                                <div class="row">
                                    <div class="col-md-4">
                                        <p><strong>Endereço: </strong> {{$user->endereco}}</p>
                                    </div>
                                    <div class="col-md-4">
                                        <p><strong>Complemento: </strong> {{$user->complemento}}</p>
                                        <p><strong>Número: </strong> {{$user->numero}}</p>
                                        <p><strong>Bairro: </strong> {{$user->bairro}}</p>
                                    </div>
                                    <div class="col-md-4">
                                        <p><strong>CEP: </strong> {{$user->cep}}</p>
                                        <p><strong>Cidade: </strong> {{$user->cidade}}</p>
                                        <p><strong>Estado: </strong> {{$user->estado}}</p>
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- /.box-body -->
        </div>
        <!-- /.box -->
    </div>

</div>
<div class="box box-primary">
    <div class="box-header with-border">
        <h3 class="box-title">Confirme seus dados</h3>
    </div>
    <div class="box-body">

        {!! Form::open(["route" => ['matriculas-alunos.seletivo-matricula.confirmar', $seletivo_matricula->id], "method" => "POST", "id" => "form", "role" => "form"]) !!}
        <div class="row">

            <div class="form-group col-md-3 @if ($errors->has('celular')) has-error @endif">
                {!! Form::label('celular', 'Celular*', ['class' => 'control-label']) !!}
                <div class="controls">
                    {!! Form::text('celular', isset($user->celular) ? $user->celular : old('celular'), ['class' => 'form-control']) !!}
                    @if ($errors->has('celular')) <p class="help-block">{{ $errors->first('celular') }}</p> @endif
                </div>
            </div>

            <div class="form-group col-md-3 @if ($errors->has('telefone')) has-error @endif">
                {!! Form::label('telefone', 'Telefone*', ['class' => 'control-label']) !!}
                <div class="controls">
                    {!! Form::text('telefone', isset($user->telefone) ? $user->telefone : old('telefone'), ['class' => 'form-control']) !!}
                    @if ($errors->has('telefone')) <p class="help-block">{{ $errors->first('telefone') }}</p> @endif
                </div>
            </div>
        </div>

        <div class="row">
            <div class="form-group col-md-12">
                {!! Form::submit('Confirmar dados', ['class' => 'btn btn-primary pull-right']) !!}
            </div>
        </div>
        {!! Form::close() !!}

    </div>
@stop