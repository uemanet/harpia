@extends('layouts.modulos.integracao')

@section('title')
    Turmas do Ambiente Virtual
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
        <h3 class="box-title">Vincular Turmas ao Ambiente Virtual</h3>
    </div>


<div class="box-body">
    <div class="row">
      <form action="{{ route('integracao.ambientesvirtuais.adicionarturma', [$ambiente->amb_id]) }}" method="POST" id="formAtribuirPerfil">
    @csrf

          <div class="form-group col-md-3">
                  <select name="crs_id" class="form-control" id="crs_id">
    <option value="">Selecione o curso</option>
    @foreach($cursos as $key => $value)
        <option value="{{ $key }}" {{ old('crs_id') == $key ? 'selected' : '' }}>{{ $value }}</option>
    @endforeach
</select>
                  @if ($errors->has('crs_id')) <p class="help-block">{{ $errors->first('crs_id') }}</p> @endif
          </div>
          <div class="form-group col-md-3">
                  <select name="ofc_id" class="form-control" id="ofc_id">
    <option value="">Selecione a oferta</option>
</select>
                  @if ($errors->has('ofc_id')) <p class="help-block">{{ $errors->first('ofc_id') }}</p> @endif
          </div>
          <div class="form-group col-md-3">
                  <select name="atr_trm_id" class="form-control" id="atr_trm_id">
    <option value="">Selecione a turma</option>
</select>
                  @if ($errors->has('atr_trm_id')) <p class="help-block">{{ $errors->first('atr_trm_id') }}</p> @endif
          </div>

          <div class="form-group col-md-3">
              <button type="submit" class="btn btn-primary" id="btnAtribuir">Vincular</button>
          </div>
      </form>
    </div>

    <div class="row">
        <div class="col-md-12">
        @if(count($ambiente->turmas))
            <table class="table table-bordered table-striped table-hover">
                <thead>
                    <th style="width: 10px">#</th>
                    <th style="width: 10px">Curso</th>
                    <th style="width: 20px">Oferta de Curso</th>
                    <th style="width: 20px">Turma</th>
                    <th style="width: 20px"></th>
                </thead>
                <tbody>
                    @foreach($ambiente->ambienteturma as $ambienteturma)
                        <tr>
                            <td>{{$ambienteturma->turma->trm_id}}</td>
                            <td>{{$ambienteturma->turma->ofertacurso->curso->crs_nome}}</td>
                            <td>{{$ambienteturma->turma->ofertacurso->ofc_ano}}</td>
                            <td>{{$ambienteturma->turma->trm_nome}}</td>
                            <td>
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

    </script>

    <script src="{{asset('/js/plugins/select2.js')}}" type="text/javascript"></script>

        <script type="text/javascript">
            $(document).ready(function() {
                $("select").select2();
            });
    </script>
@stop
