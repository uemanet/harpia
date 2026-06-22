@extends('layouts.modulos.default')

@section('title')
    Colaboradores
@stop

@section('subtitle')
    Alterar Colaborador :: {{$colaborador->pessoa->pes_nome}}
@stop

@section('content')

    <div class="card card-success card-outline">
        <div class="card-header with-border">
            <h3 class="card-title">Gerenciamento de funções de colaborador</h3>
        </div>
        <div class="card-body">
            <div class="row my-2">
                <h3 class="card-title w-100 pb-3">
                    <span style="font-weight: bold;"><i class="fa-solid fa-caret-right"></i> Funções do Colaborador</span>
                </h3>
                <form action="{{ route('rh.colaboradores.movimentacaosetor.funcao.create', [$colaborador->col_id]) }}" method="POST" id="form">
                    @csrf
                    <div class="row">

                        <div class="form-group col-md-3">
                            <select name="cfn_set_id" class="form-control">
                                <option value="">Selecione o setor</option>
                                @foreach($setores as $key => $value)
                                    <option value="{{ $key }}" {{ [] == $key ? 'selected' : '' }}>{{ $value }}</option>
                                @endforeach
                            </select>
                            @if ($errors->has('cfn_set_id')) <p style="color: red" class="help-block">{{ $errors->first('cfn_set_id') }}</p> @endif
                        </div>

                        <div class="form-group col-md-3">
                            <select name="cfn_fun_id" class="form-control">
                                <option value="">Selecione a função</option>
                                @foreach($funcoes as $key => $value)
                                    <option value="{{ $key }}" {{ [] == $key ? 'selected' : '' }}>{{ $value }}</option>
                                @endforeach
                            </select>
                            @if ($errors->has('cfn_fun_id')) <p style="color: red"  class="help-block">{{ $errors->first('cfn_fun_id') }}</p> @endif
                        </div>

                        <div class="form-group col-md-3">
                            <input type="text" name="cfn_data_inicio" value="{{ old('cfn_data_inicio') }}" class="form-control datepicker" data-provide="datepicker" date-date-format="dd/mm/yyyy" placeholder="Data de Início" >
                            @if ($errors->has('cfn_data_inicio')) <p style="color: red" class="help-block">{{ $errors->first('cfn_data_inicio') }}</p> @endif
                        </div>

                        <div class="form-group col-md-3">
                            <button type="submit" class="btn btn-primary" id="btnAtribuir">Adicionar Função</button>
                        </div>
                    </div>
                </form>
                <div class="row my-2">
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
                                        <td>
                                            <form method="POST" class="delete d-flex align-items-center" style="gap: 10px;" action="{{{ route('rh.colaboradores.movimentacaosetor.funcao.delete', [$colaborador->col_id,$funcao->cfn_id] ) }}}">
                                                @csrf
                                                <input type="text" name="cfn_data_fim" value="{{ old('cfn_data_fim') }}" class="form-control datepicker mb-0" data-provide="datepicker" date-date-format="dd/mm/yyyy" placeholder="Data de Fim" style="max-width: 150px;">
                                                <button type="submit" class="btn btn-danger btn-desvincular text-nowrap"><i class="fa fa-trash"></i> Desvincular</button>
                                            </form>
                                        </td>
                                        @endhaspermission
                                        <td>
                                            {!! ActionButton::grid([
                                                'type' => 'LINE',
                                                'buttons' => [
                                                    [
                                                        'classButton' => 'btn btn-danger btn-delete',
                                                        'icon' => 'fa fa-warning',
                                                        'route' => 'rh.colaboradores.movimentacaosetor.funcao.remove',
                                                        'parameters' => [$colaborador->col_id,$funcao->cfn_id],
                                                        'id' => $funcao->cfn_id,
                                                        'label' => 'Remover',
                                                        'method' => 'post'
                                                    ]
                                                ]
                                            ]) !!}
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        @else
                            <p>Colaborador sem funções cadastradas</p>
                        @endif
                    </div>
                </div>
            </div>

            <div class="row my-2">
                <h3 class="card-title w-100 pb-3">
                    <span style="font-weight: bold;"><i class="fa-solid fa-caret-right"></i> Histórico de Funções</span>
                </h3>
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
    </div>
@stop

@section('scripts')
    <script>
        window.PageRoutes = {
            //
        };
    </script>

    @vite('modulos/RH/Resources/js/pages/colaboradores/movimentacaosetor.js')
@stop
