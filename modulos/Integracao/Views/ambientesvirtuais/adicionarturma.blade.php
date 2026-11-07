@extends('layouts.modulos.default')

@section('title')
    Turmas do Ambiente Virtual
@stop

@section('subtitle')
    {{$ambiente->amb_nome}}
@stop

@section('content')
    <div class="row">
        <div class="card card-primary card-outline">
            <div class="card-header with-border">
                <h3 class="card-title">Vincular Turmas ao Ambiente Virtual</h3>
            </div>

            <div class="card-body">
                <div class="row">
                    <form class="d-flex w-100" action="{{ route('integracao.ambientesvirtuais.adicionarturma', [$ambiente->amb_id]) }}" method="POST" id="formAtribuirPerfil">
                        @csrf
                        <div class="col-md-4 px-1">
                            <select name="crs_id" class="form-control select2" id="crs_id">
                                <option value="">Selecione o curso</option>
                                @foreach($cursos as $key => $value)
                                    <option value="{{ $key }}" {{ old('crs_id') == $key ? 'selected' : '' }}>{{ $value }}</option>
                                @endforeach
                            </select>
                            @if ($errors->has('crs_id')) <p class="help-block">{{ $errors->first('crs_id') }}</p> @endif
                        </div>
                        <div class="col-md-3 px-1">
                            <select name="ofc_id" class="form-control select2" id="ofc_id">
                                <option value="">Selecione a oferta</option>
                            </select>
                            @if ($errors->has('ofc_id')) <p class="help-block">{{ $errors->first('ofc_id') }}</p> @endif
                        </div>
                        <div class="col-md-3 px-1">
                            <select name="atr_trm_id" class="form-control select2" id="atr_trm_id">
                                <option value="">Selecione a turma</option>
                            </select>
                            @if ($errors->has('atr_trm_id')) <p class="help-block">{{ $errors->first('atr_trm_id') }}</p> @endif
                        </div>

                        <div class="col-md-2 px-1">
                            <button type="submit" class="btn btn-primary w-100" id="btnAtribuir">Vincular</button>
                        </div>
                    </form>
                </div>
            </div>

            <div class="row">
                <div class="col-md-12">
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="card card-primary card-outline my-2 p-0">
            @if(count($ambiente->turmas))
                <div class="card-body p-0">
                    <table class="table table-bordered table-striped table-hover">
                        <thead>
                            <th class="text-center">#</th>
                            <th class="col-sm-7 text-center">Curso</th>
                            <th class="col-sm-2 text-center">Oferta de Curso</th>
                            <th class="col-sm-2 text-center">Turma</th>
                            <th></th>
                        </thead>
                        <tbody>
                        @foreach($ambiente->ambienteturma as $ambienteturma)
                            <tr>
                                <td class="text-center">{{$ambienteturma->turma->trm_id}}</td>
                                <td>{{$ambienteturma->turma->ofertacurso->curso->crs_nome}}</td>
                                <td>{{$ambienteturma->turma->ofertacurso->ofc_ano}}</td>
                                <td>{{$ambienteturma->turma->trm_nome}}</td>
                                <td class="text-center">
                                    {!! ActionButton::grid([
                                        'type' => 'LINE',
                                        'buttons' => [
                                            [
                                                'classButton' => 'btn btn-danger btn-delete',
                                                'icon' => 'fa fa-trash',
                                                'route' => 'integracao.ambientesvirtuais.deletarturma',
                                                'id' => $ambienteturma->atr_id,
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
            @else
                <div class="card-body">
                    <p>Sem turmas vinculadas ao ambiente virtual</p>
                </div>
            @endif
        </div>
    </div>
@stop

@section('scripts')
    <script>
        window.PageRoutes = {
            //
        };
    </script>

    @vite('modulos/Integracao/Resources/js/pages/ambientesvirtuais/adicionarturma.js')
@stop