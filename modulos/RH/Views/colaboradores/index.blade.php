@extends('layouts.modulos.rh')

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

@section('actionButton')
    {!!ActionButton::render($actionButton)!!}
@stop

@section('content')
    <div class="box box-primary">
        <div class="box-header with-border">
            <h3 class="box-title"><i class="fa fa-filter"></i> Filtrar dados</h3>

            <div class="box-tools pull-right">
                <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                </button>
            </div>
            <!-- /.box-tools -->
        </div>
        <!-- /.box-header -->
        <div class="box-body">
            <div class="row">
                <form method="GET" action="{{ route('rh.colaboradores.index') }}">
                    <div class="col-md-2">
                        <input type="text" class="form-control" name="pes_cpf" id="pes_cpf"
                               value="{{Request::input('pes_cpf')}}" placeholder="CPF">
                    </div>
                    <div class="col-md-2">
                        <input type="text" class="form-control" name="pes_nome" id="pes_nome"
                               value="{{Request::input('pes_nome')}}" placeholder="Nome">
                    </div>
                    <div class="col-md-2">
                        <input type="email" class="form-control" name="pes_email" id="pes_email"
                               value="{{Request::input('pes_email')}}" placeholder="Email">
                    </div>

                    <div class="form-group col-md-2">
                        {!! Form::select('cfn_set_id', $setores, [], ['class' => 'form-control', 'placeholder' => 'Selecione o setor']) !!}
                    </div>

                    <div class="form-group col-md-2">
                        {!! Form::select('funcoes[]', $funcoes, old('funcoes[]'), ['class' => 'form-control', 'multiple' => 'multiple']) !!}
                        @if ($errors->has('funcoes')) <p class="help-block">{{ $errors->first('funcoes') }}</p> @endif
                    </div>

                    <div class="col-md-1">
                        <input type="submit" class="form-control btn-primary" value="Buscar">
                    </div>
                </form>
            </div>
        </div>
        <!-- /.box-body -->
    </div>

    <div class="text-right mb-3">
        <a href="{{ route('rh.ferias.export') }}" class="btn btn-success">
            Gerar Planilha de Controle de Férias
        </a>
    </div>

    @if(!is_null($tabela))
        <div class="box box-primary">
            <div class="box-header">
                {!! $tabela->render() !!}
            </div>
        </div>

        <div class="text-center">{!! $paginacao->links('pagination::bootstrap-4') !!}</div>
    @else
        <div class="box box-primary">
            <div class="box-body">Sem registros para apresentar</div>
        </div>
    @endif

    <style>
        .select2-container .select2-selection--single {
            height: 34px;
        }

        .select2-container .select2-selection--multiple {
            min-height: 34px;
        }

        .select2-container--default .select2-selection--multiple .select2-selection__choice {
            background-color: #3c8dbc;
        }
    </style>
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

