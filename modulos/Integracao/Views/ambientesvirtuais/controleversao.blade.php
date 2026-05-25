@extends('layouts.modulos.default')

@section('title')
    Versão das turmas
@stop

@section('subtitle')
    {{$ambiente->amb_nome}}
@stop

@section('content')
<div class="card card-primary card-outline">
    <div class="card-header with-border">
        <h3 class="card-title">Alteração de versão das turmas</h3>
    </div>

<div class="card-body">

    <div class="row">
        <div class="col-md-12">
        @if(count($ambiente->turmas))
            <table class="table table-bordered table-striped table-hover">
                <thead>
                    <th style="width: 10px">#</th>
                    <th style="width: 10px">Curso</th>
                    <th style="width: 20px">Oferta de Curso</th>
                    <th style="width: 20px">Turma</th>
                    <th style="width: 20px">Versão</th>
                    <th style="width: 20px"></th>
                </thead>
                <tbody>
                    @foreach($ambiente->ambienteturma as $ambienteturma)
                        <tr>
                            <td>{{$ambienteturma->turma->trm_id}}</td>
                            <td>{{$ambienteturma->turma->ofertacurso->curso->crs_nome}}</td>
                            <td>{{$ambienteturma->turma->ofertacurso->ofc_ano}}</td>
                            <td>{{$ambienteturma->turma->trm_nome}}</td>
                            <td>{{$ambienteturma->turma->trm_tipo_integracao}}</td>
                            <td>
                                @if($ambienteturma->turma->trm_tipo_integracao == 'v1')

                                {!! ActionButton::grid([
                                    'type' => 'LINE',
                                    'buttons' => [
                                        [
                                            'classButton' => 'btn btn-success',
                                            'icon' => 'fa fa-circle-o-notch',
                                            'route' => 'integracao.ambientesvirtuais.controleversao',
                                            'id' => $ambienteturma->atr_id,
                                            'parameters' => ['id' => $ambienteturma->atr_id],
                                            'label' => '',
                                            'method' => 'post'
                                        ]
                                    ]
                                ]) !!}
                                @endif

                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @else
            <p>Sem turmas vinculadas ao ambiente virtual</p>
        @endif
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

    @vite('modulos/Integracao/Resources/js/pages/ambientesvirtuais/controleversao.js')
@stop