@extends('layouts.modulos.default')

@section('title')
    Aproveitamento de Estudos
@endsection

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
                    <form method="GET" action="{{{ route('academico.aproveitamentoestudos.index') }}}" class="d-flex w-100">
                        <div class="col-md-3 px-1">
                            <input type="text" class="form-control" name="pes_cpf" id="pes_cpf" value="{{Request::input('pes_cpf')}}" placeholder="CPF">
                        </div>
                        <div class="col-md-4 px-1">
                            <input type="text" class="form-control" name="pes_nome" id="pes_nome" value="{{Request::input('pes_nome')}}" placeholder="Nome">
                        </div>
                        <div class="col-md-3 px-1">
                            <input type="email" class="form-control" name="pes_email" id="pes_email" value="{{Request::input('pes_email')}}" placeholder="Email">
                        </div>
                        <div class="col-md-2 px-1">
                            <input type="submit" class="btn btn-primary w-100" value="Buscar">
                        </div>
                    </form>
                </div>
            </div>
            <!-- /.card-body -->
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
@endsection
