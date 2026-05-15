@extends('layouts.modulos.default')

@section('title')
    Colaboradores
@stop

@section('subtitle')
    Gerenciamento de colaboradores
@stop

@section('actionButton')
    {!!ActionButton::render($actionButton)!!}
@stop

@section('content')
    <div class="row">
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
                <div class="row">
                    <form method="GET" class="d-flex w-100" action="{{{ route('rh.colaboradores.index') }}}">
                        <div class="col-md-2 px-1">
                            <input type="text" class="form-control" name="pes_cpf" id="pes_cpf"
                                   value="{{Request::input('pes_cpf')}}" placeholder="CPF">
                        </div>
                        <div class="col-md-3 px-1">
                            <input type="text" class="form-control" name="pes_nome" id="pes_nome"
                                   value="{{Request::input('pes_nome')}}" placeholder="Nome">
                        </div>
                        <div class="col-md-2 px-1">
                            <input type="email" class="form-control" name="pes_email" id="pes_email"
                                   value="{{Request::input('pes_email')}}" placeholder="Email">
                        </div>

                        <div class="form-group col-md-2">
                            <select name="cfn_set_id" class="form-control">
                                <option value="">Selecione o setor</option>
                                @foreach($setores as $key => $value)
                                    <option value="{{ $key }}" {{ [] == $key ? 'selected' : '' }}>{{ $value }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="form-group col-md-2 px-1">
                            <select name="funcoes[]" class="form-control" multiple="multiple">
                                <option value="">Selecione a função</option>
                                @foreach($funcoes as $key => $value)
                                    <option value="{{ $key }}" {{ old('funcoes[]') == $key ? 'selected' : '' }}>{{ $value }}</option>
                                @endforeach
                            </select>
                            @if ($errors->has('funcoes')) <p class="help-block">{{ $errors->first('funcoes') }}</p> @endif
                        </div>

                        <div class="col-md-1 px-1">
                            <input type="submit" class="btn btn-primary w-100" value="Buscar">
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <!-- /.card-body -->
    </div>

    <div class="row my-2 p-0">
        <div class="">
            <a href="{{{ route('rh.ferias.export') }}}" class="btn btn-success" style="float: right">
                Gerar Planilha de Controle de Férias
            </a>
        </div>
    </div>

    <div class="row">
        <div class="card card-primary card-outline my-2 p-0">
            @if(!is_null($tabela))
                <div class="card-body p-0">
                    {!! $tabela->render() !!}
                </div>
                <div class="card-footer clearfix">
                    {!! $paginacao->links('pagination::bootstrap-4') !!}
                </div>
            @else
                <div class="card-body">
                    Sem registros para apresentar
                </div>
            @endif
        </div>
    </div>
@stop

