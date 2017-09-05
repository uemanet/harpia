@extends('layouts.modulos.academico')

@section('stylesheets')
    <link rel="stylesheet" href="{{asset('/css/plugins/select2.css')}}">
@endsection

@section('title')
    Gerenciamento de Matriculas
@stop

@section('subtitle')
    {{$lista->lst_nome}} - {{$lista->lst_descricao}}
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
                <form method="GET" action="{{ route('academico.carteirasestudantis.showmatriculas', $lista->lst_id) }}">
                    <div class="col-md-5">
                        <input type="text" class="form-control" name="pes_nome" id="pes_nome" value="{{Input::get('pes_nome')}}" placeholder="Nome do Aluno">
                    </div>
                    <div class="col-md-3">
                        {!! Form::select('mat_trm_id', $turmas, Input::get('mat_trm_id'), ['class' => 'form-control', 'placeholder' => 'Turma']) !!}
                    </div>
                    <div class="col-md-3">
                        {!! Form::select('mat_pol_id', $polos, Input::get('mat_pol_id'), ['class' => 'form-control', 'placeholder' => 'Polo']) !!}
                    </div>
                    <div class="col-md-1">
                        <input type="submit" class="form-control btn-primary" value="Buscar">
                    </div>
                </form>
            </div>
        </div>
        <!-- /.box-body -->
    </div>
    @if(!is_null($tabela))
        <div class="box box-primary">
            <div class="box-header">
                {!! $tabela->render() !!}
            </div>
        </div>

        <div class="text-center">{!! $paginacao->links() !!}</div>

    @else
        <div class="box box-primary">
            <div class="box-body">Sem registros para apresentar</div>
        </div>
    @endif
@stop

@section('scripts')
    <script src="{{asset('/js/plugins/select2.js')}}" type="text/javascript"></script>

    <script type="text/javascript">
        $(function () {
            $('select').select2();
        });
    </script>
@stop
