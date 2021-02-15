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

@section('content')

    <div class="box box-primary">
        <div class="box-header with-border">
            <h3 class="box-title">Gerenciamento de funções de colaborador</h3>
        </div>
        <div class="box-body">
            <h4>Funções do Colaborador</h4>
            <div class="row">
                {!! Form::open(array('route' => ['rh.colaboradores.movimentacaosetor.funcao.create', $colaborador->col_id], 'method' => 'POST', 'id' => 'form')) !!}

                <div class="form-group col-md-3">
                    {!! Form::select('cfn_set_id', $setores, [], ['class' => 'form-control', 'placeholder' => 'Selecione o setor']) !!}
                    @if ($errors->has('cfn_set_id')) <p class="help-block">{{ $errors->first('cfn_set_id') }}</p> @endif
                </div>

                <div class="form-group col-md-3">
                    {!! Form::select('cfn_fun_id', $funcoes,[] , ['class' => 'form-control', 'placeholder' => 'Selecione a função']) !!}
                    @if ($errors->has('cfn_fun_id')) <p class="help-block">{{ $errors->first('cfn_fun_id') }}</p> @endif
                </div>

                <div class="form-group col-md-3">
                    {!! Form::text('cfn_data_inicio', old('cfn_data_inicio'), ['class' => 'form-control datepicker', 'data-provide' => 'datepicker', 'date-date-format' => 'dd/mm/yyyy', 'placeholder' => 'Data de Início']) !!}
                    @if ($errors->has('cfn_data_inicio')) <p
                            class="help-block">{{ $errors->first('cfn_data_inicio') }}</p> @endif
                </div>

                <div class="form-group col-md-3">
                    {!! Form::submit('Adicionar Função', ['class' => 'btn btn-primary', 'id' => 'btnAtribuir']) !!}
                </div>
                {!! Form::close() !!}
            </div>
            <div class="row">
                <div class="col-md-12">
                    @if(count($colaborador->funcoes))
                        <table class="table table-bordered table-striped table-hover">
                            <thead>
                            <th>Setor</th>
                            <th>Função</th>
                            <th>Início</th>
                            <th></th>
                            </thead>
                            <tbody>
                            @foreach($colaborador->funcoes as $funcao)
                                <tr>
                                    <td>{{$funcao->setor->set_descricao}}</td>
                                    <td>{{$funcao->funcao->fun_descricao}}</td>
                                    <td>{{$funcao->cfn_data_inicio}}</td>


                                    @haspermission('rh.colaboradores.movimentacaosetor.funcao.delete')
                                    <div class="row">

                                        <form method="POST" class="delete"
                                              action="{{ route('rh.colaboradores.movimentacaosetor.funcao.delete', [$colaborador->col_id,$funcao->cfn_id] ) }}">
                                            <?php echo e(csrf_field()); ?>
                                            <td>{!! Form::text('cfn_data_fim', old('cfn_data_fim'), ['class' => 'form-control datepicker', 'data-provide' => 'datepicker', 'date-date-format' => 'dd/mm/yyyy', 'placeholder' => 'Data de Fim']) !!}</td>
                                            <td>
                                                <button class="btn-danger"><i class="fa fa-trash"></i> Desvincular
                                                </button>
                                            </td>
                                        </form>

                                        @endhaspermission
                                    </div>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    @else
                        <p>Colaborador sem funções cadastradas</p>
                    @endif
                </div>
            </div>
            <h4>Histórico de Funções</h4>
            <div class="row">
                <div class="col-md-12">
                    @if(count($colaborador->funcoes_historico))
                        <table class="table table-bordered table-striped table-hover">
                            <thead>
                            <th style="width: 10px">Setor</th>
                            <th style="width: 10px">Função</th>
                            <th style="width: 20px">Início</th>
                            <th style="width: 20px">Fim</th>
                            </thead>
                            <tbody>
                            @foreach($colaborador->funcoes_historico as $funcao)
                                <tr>

                                    <td>{{$funcao->setor->set_descricao}}</td>
                                    <td>{{$funcao->funcao->fun_descricao}}</td>
                                    <td>{{$funcao->cfn_data_inicio}}</td>
                                    <td>{{$funcao->cfn_data_fim}}</td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    @else
                        <p>Colaborador sem histórico de movimentação de funções</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
@stop

@section('scripts')
    <script src="{{asset('/js/plugins/select2.js')}}" type="text/javascript"></script>
    <script src="{{asset('/js/plugins/bootstrap-datepicker.js')}}" type="text/javascript"></script>
    <script src="{{asset('/js/plugins/bootstrap-datepicker.pt-BR.js')}}" type="text/javascript"></script>
    <script type="text/javascript">
        $(document).ready(function () {
            $("select").select2();
        });

        $('form').submit(function (e) {

            if (!$(e.target).hasClass('delete')) {
                return
            }
            e.preventDefault();

            swal({
                title: "Tem certeza que deseja desvincular a função?",
                text: "Esta operação não poderá ser desfeita!",
                type: "warning",
                showCancelButton: true,
                confirmButtonColor: "#DD6B55",
                confirmButtonText: "Sim, pode excluir!",
                cancelButtonText: "Não, quero cancelar!",
                closeOnConfirm: true
            }, function (isConfirm) {
                if (isConfirm) {
                    console.log('chegou')
                    //continue submitting
                    e.currentTarget.submit();
                }
            });

        });


    </script>

    <script type="text/javascript">
        $('.datepicker').datepicker({
            format: 'dd/mm/yyyy',
            language: 'pt-BR'
        });
    </script>


@endsection


