@extends('layouts.modulos.rh')

@section('stylesheets')
    <link rel="stylesheet" href="{{asset('/css/plugins/select2.css')}}">
    <link rel="stylesheet" href="{{asset('/css/plugins/datepicker3.css')}}">
@endsection

@section('title')
    Colaboradores
@stop

@section('subtitle')
    Alterar Colaborador :: {{$colaborador->pessoa->pes_nome}}
@stop

@section('actionButton')
    {!!ActionButton::render($actionButtons)!!}
@stop

@section('content')
    <div class="box box-primary">
        <div class="box-header with-border">
            <h3 class="box-title">Afastamento/Desligamento de colaborador</h3>
        </div>
        <div class="row">
            <div class="col-md-12">
                <table class="table table-bordered table-striped table-hover">
                    <thead>
                    <th>Matrícula</th>
                    <th>Início</th>
                    <th>Fim</th>
                    </thead>
                    <tbody>
                    @foreach($matriculas as $matricula)
                        <tr>
                            <td>{{$matricula->mtc_id}}</td>
                            <td>{{$matricula->mtc_data_inicio}}</td>
                            @if($matricula->mtc_data_fim)
                                <td>{{$matricula->mtc_data_fim}}</td><td></td>
                            @else
                                <div class="row">
                                    <form method="POST" class="delete"
                                          action="{{ route('rh.colaboradores.matricula', $matricula->mtc_id ) }}">
                                        <?php echo e(csrf_field()); ?>
                                        <td>{!! Form::text('mtc_data_fim', old('mtc_data_fim'), ['class' => 'form-control datepicker', 'data-provide' => 'datepicker', 'date-date-format' => 'dd/mm/yyyy', 'placeholder' => 'Data de Fim']) !!}</td>
                                        <td>
                                            <button class="btn btn-danger"><i class="fa fa-trash"></i> Desvincular Colaborador
                                            </button>
                                        </td>
                                    </form>
                                </div>
                            @endif

                            <td>
                                {!! ActionButton::grid([
                                            'type' => 'LINE',
                                            'buttons' => [
                                           [
                                               'classButton' => 'btn-delete btn btn-danger btn-sm',
                                               'icon' => 'fa fa-trash',
                                               'route' => 'rh.colaboradores.matricula.delete-matricula',
                                               'id' => $matricula->mtc_id,
                                               'label' => '',
                                               'method' => 'post'
                                           ]
                                       ]
                               ]) !!}
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@stop

@section('scripts')

    <script src="{{asset('/js/plugins/select2.js')}}" type="text/javascript"></script>
    <script src="{{asset('/js/plugins/bootstrap-datepicker.js')}}" type="text/javascript"></script>
    <script src="{{asset('/js/plugins/bootstrap-datepicker.pt-BR.js')}}" type="text/javascript"></script>
    <script type="text/javascript">
        $(document).ready(function() {
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

