@extends('layouts.modulos.integracao')

@section('title')
    Versão das turmas
@stop

@section('stylesheets')
  <link rel="stylesheet" href="{{asset('/css/plugins/select2.css')}}">
@endsection

@section('subtitle')
    {{$ambiente->amb_nome}}
@stop

@section('content')
<div class="box box-primary">
    <div class="box-header with-border">
        <h3 class="box-title">Alteração de versão das turmas</h3>
    </div>

<div class="box-body">

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
    @parent

    <script type="application/javascript">
        $(document).ready(function(){
            $('#crs_id').prop('selectedIndex',0);
        });
    </script>
    <script type="application/javascript">

        $('#crs_id').change(function (e) {
            var crsId = $(this).val();

            var selectOfertas = $('#ofc_id');
            var selectTurmas = $('#atr_trm_id');
            if(crsId) {

                // Populando o select de ofertas de cursos
                selectOfertas.empty();
                selectTurmas.empty();

                $.harpia.httpget("{{url('/')}}/academico/async/ofertascursos/findallbycursowithoutpresencial/" + crsId)
                        .done(function (data) {
                            if(!$.isEmptyObject(data)) {
                                selectOfertas.append("<option>Selecione a oferta</option>");
                                $.each(data, function (key, value) {
                                    selectOfertas.append('<option value="'+value.ofc_id+'">'+value.ofc_ano+' ('+value.mdl_nome+')</option>');
                                });
                            } else {
                                selectOfertas.append("<option>Sem ofertas cadastradas</option>");

                            }
                        });
            }
        });


        $('#ofc_id').change(function (e) {
            var ofertaId = $(this).val();

            var selectTurmas = $('#atr_trm_id');

            if (ofertaId) {
                selectTurmas.empty();

                $.harpia.httpget('{{url("/")}}/academico/async/turmas/findallbyofertacursowithoutambiente/' + ofertaId)
                        .done(function (data) {
                            if (!$.isEmptyObject(data)){
                                selectTurmas.append('<option>Selecione a turma</option>');
                                $.each(data, function (key, obj) {
                                    selectTurmas.append('<option value="'+obj.trm_id+'">'+obj.trm_nome+'</option>')
                                });
                            }else {
                                selectTurmas.append('<option>Sem turmas cadastradas</option>')
                            }
                        });
            }

        })

        $(document).on('click', '.btn-success', function (event) {
            event.preventDefault();

            var button = $(this);

            swal({
                title: "Tem certeza que deseja alterar a versão dessa turma?",
                text: "Essa alteração é irreversível!",
                type: "warning",
                showCancelButton: true,
                confirmButtonColor: "#DD6B55",
                confirmButtonText: "Sim!",
                cancelButtonText: "Não!",
                closeOnConfirm: true
            }, function(isConfirm){
                if (isConfirm) {
                    button.closest("form").submit();
                }
            });
        });

    </script>

    <script src="{{asset('/js/plugins/select2.js')}}" type="text/javascript"></script>

        <script type="text/javascript">
            $(document).ready(function() {
                $("select").select2();
            });
    </script>
@stop
