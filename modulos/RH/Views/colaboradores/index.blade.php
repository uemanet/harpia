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
                    <form method="GET" class="d-flex w-100 flex-column" action="{{{ route('rh.colaboradores.index') }}}">
                        <div class="row">
                            <div class="col-md-4 px-1">
                                <label for="pes_nome" class="form-label">Nome</label>
                                <input type="text" class="form-control" name="pes_nome" id="pes_nome" value="{{Request::input('pes_nome')}}">
                            </div>
                            <div class="col-md-3 px-1">
                                <label for="pes_email" class="form-label">Email</label>
                                <input type="email" class="form-control" name="pes_email" id="pes_email" value="{{Request::input('pes_email')}}">
                            </div>
                            <div class="col-md-2 px-1">
                                <label for="pes_cpf" class="form-label">CPF</label>
                                <input type="text" class="form-control cpf-mask" name="pes_cpf" id="pes_cpf" value="{{Request::input('pes_cpf')}}">
                            </div>
                            <div class="col-md-3">
                                <label for="cfn_set_id" class="form-label">Setor</label>
                                <select name="cfn_set_id" class="form-control">
                                    <option value="">Selecione o setor</option>
                                    @foreach($setores as $key => $value)
                                        <option value="{{ $key }}" {{ [] == $key ? 'selected' : '' }}>{{ $value }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="row py-1">
                            <div class="col-md-2 px-1">
                                <label for="funcoes" class="form-label">Função</label>
                                <select name="funcoes[]" class="form-control" multiple="multiple">
                                    <option value="">Selecione a função</option>
                                    @foreach($funcoes as $key => $value)
                                        <option value="{{ $key }}" {{ old('funcoes[]') == $key ? 'selected' : '' }}>{{ $value }}</option>
                                    @endforeach
                                </select>
                                @if ($errors->has('funcoes')) <p class="help-block">{{ $errors->first('funcoes') }}</p> @endif
                            </div>

                            <!-- Filtro de Status -->
                            <div class="col-md-2 px-1">
                                <label for="col_status" class="form-label">Status</label>
                                <select name="col_status" id="col_status" class="form-control">
                                    <option value="">Todos</option>
                                    <option value="ativo" {{ Request::input('col_status') == 'ativo' ? 'selected' : '' }}>Ativo</option>
                                    <option value="desligado" {{ Request::input('col_status') == 'desligado' ? 'selected' : '' }}>Desligado</option>
                                </select>
                            </div>

                            <!-- Filtro de Foto -->
                            <div class="col-md-2 px-1">
                                <label for="com_foto" class="form-label">Foto</label>
                                <select name="com_foto" id="com_foto" class="form-control">
                                    <option value="">Todos</option>
                                    <option value="1" {{ Request::input('com_foto') === '1' ? 'selected' : '' }}>Com Foto</option>
                                    <option value="0" {{ Request::input('com_foto') === '0' ? 'selected' : '' }}>Sem Foto</option>
                                </select>
                            </div>

                            <div class="col-md-1 px-1">
                                <label for="" class="form-label">&nbsp;</label>
                                <input type="submit" class="btn btn-primary w-100" value="Buscar">
                            </div>

                            <div class="col-md-2 px-1 d-flex flex-column justify-content-end">
                                <label for="" class="form-label">&nbsp;</label>
                                <!-- request()->query() repassa todos os GET params da URL atual -->
                                <a href="{{ route('rh.colaboradores.export', request()->query()) }}" class="btn btn-info w-100 text-center">
                                    <i class="fa-regular fa-file-excel"></i> Exportar Tabela
                                </a>
                            </div>

                            <div class="col-md-3 px-1 d-flex flex-column justify-content-end">
                                <label for="" class="form-label">&nbsp;</label>
                                <a href="{{{ route('rh.ferias.export') }}}" class="btn btn-success">
                                    <i class="fa-regular fa-file-excel"></i> Planilha de Controle de Férias
                                </a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <!-- /.card-body -->
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

