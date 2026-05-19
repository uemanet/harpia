@extends('layouts.modulos.default')

@section('stylesheets')
    <link rel="stylesheet" href="{{asset('/css/plugins/select2.css')}}">
    <link rel="stylesheet" href="{{asset('/css/plugins/datepicker3.css')}}">
@endsection

@section('title')
    Colaboradores
@stop

@section('subtitle')
    Gerenciamento de colaboradores
@stop

@section('content')
    <div class="card card-primary card-outline">
        <div class="card-header">
            <h3 class="card-title m-0"><i class="fa fa-filter"></i> Filtrar dados</h3>

            <div class="card-tools float-end">
                <button type="button" class="btn btn-tool" data-lte-toggle="card-collapse"><i class="fa fa-minus"></i>
                </button>
            </div>
        </div>
        <div class="card-body">
            <form method="GET" action="{{{ route('rh.relatorios.periodosaquisitivos') }}}">
                <div class="row">
                    <div class="col-md-2 px-1">
                        <input type="text" class="form-control" name="pes_cpf" id="pes_cpf"
                               value="{{Request::input('pes_cpf')}}" placeholder="CPF">
                    </div>
                    <div class="col-md-2 px-1">
                        <input type="text" class="form-control" name="pes_nome" id="pes_nome"
                               value="{{Request::input('pes_nome')}}" placeholder="Nome">
                    </div>
                    <div class="col-md-2 px-1">
                        <input type="email" class="form-control" name="pes_email" id="pes_email"
                               value="{{Request::input('pes_email')}}" placeholder="Email">
                    </div>

                    <div class="col-md-2 px-1">
                        <select name="cfn_set_id" class="form-control">
    <option value="">Selecione o setor</option>
    @foreach($setores as $key => $value)
        <option value="{{ $key }}" {{ [] == $key ? 'selected' : '' }}>{{ $value }}</option>
    @endforeach
</select>
                    </div>

                    <div class="col-md-2 px-1">
                        <select name="funcoes[]" class="form-control" multiple="multiple">
    @foreach($funcoes as $key => $value)
        <option value="{{ $key }}" {{ old('funcoes[]') == $key ? 'selected' : '' }}>{{ $value }}</option>
    @endforeach
</select>
                        @if ($errors->has('funcoes')) <p class="help-block">{{ $errors->first('funcoes') }}</p> @endif
                    </div>

                    <div class="col-md-2 px-1">
                        <button type="submit" class="btn btn-primary w-100">Buscar</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
    @if(!is_null($tabela))
        <div class="card card-primary card-outline">
            <div class="card-header">
                {!! $tabela->render() !!}
            </div>
        </div>

        <div class="text-center">{!! $paginacao->links('pagination::bootstrap-4') !!}</div>
    @else
        <div class="card card-primary card-outline">
            <div class="card-body">Sem registros para apresentar</div>
        </div>
    @endif
@stop



@section('scripts')
    <script src="{{asset('/js/plugins/select2.js')}}" type="text/javascript"></script>
    <script src="{{asset('/js/plugins/bootstrap-datepicker.js')}}" type="text/javascript"></script>
    <script src="{{asset('/js/plugins/bootstrap-datepicker.pt-BR.js')}}" type="text/javascript"></script>
    <script type="text/javascript">
        $(document).ready(function () {
            $("select").select2();
        });
    </script>

    <script type="text/javascript">
        $('.datepicker').datepicker({
            format: 'dd/mm/yyyy',
            language: 'pt-BR'
        });
    </script>
@endsection
