@extends('layouts.modulos.integracao')

@section('title')
    Serviços do Ambiente Virtual
@stop

@section('subtitle')
    {{$ambiente->amb_nome}}
@stop

@section('content')
<div class="box box-primary">
    <div class="box-header with-border">
        <h3 class="box-title">Adicionar Serviços ao Ambiente Virtual</h3>
    </div>

    <div class="box-body">
        <div class="row">
          {!! Form::open(array('route' => ['integracao.ambientesvirtuais.postAdicionarServico', $ambiente->amb_id], 'method' => 'POST', 'id' => 'formAtribuirPerfil')) !!}
              <div class="form-group col-md-3">
                  {!! Form::label('asr_ser_id', 'Serviço*', ['class' => 'control-label']) !!}
                  <div class="controls">
                      {!! Form::select('asr_ser_id', $servicos, old('asr_ser_id'), ['class' => 'form-control', 'id' => 'asr_ser_id', 'placeholder' => 'Selecione o serviço']) !!}
                      @if ($errors->has('asr_ser_id')) <p class="help-block">{{ $errors->first('asr_ser_id') }}</p> @endif
                  </div>
              </div>
              <div class="form-group col-md-3">
                  {!! Form::label('asr_token', 'Token*', ['class' => 'control-label']) !!}
                  <div class="controls">
                      {!! Form::text('asr_token', old('asr_token'), ['id' => 'asr_token','class' => 'form-control select-control', 'placeholder' => 'Digite o token']) !!}
                      @if ($errors->has('asr_token')) <p class="help-block">{{ $errors->first('asr_token') }}</p> @endif
                  </div>
              </div>
              <div class="form-group col-md-3" style="margin-top: 1.8em">
                  <div class="controls">
                      {!! Form::submit('Adicionar', ['class' => 'btn btn-primary', 'id' => 'btnAtribuir']) !!}
                  </div>
              </div>
          {!! Form::close() !!}
        </div>
        <div class="row">
            <div class="col-md-12">
            @if(count($ambiente->servicos))
                <table class="table table-bordered table-striped table-hover">
                    <thead>
                        <th style="width: 10px">#</th>
                        <th style="width: 10px">Serviço</th>
                        <th style="width: 20px">Slug</th>
                        <th style="width: 20px">Token</th>
                        <th style="width: 20px"></th>
                    </thead>
                    <tbody>
                        @foreach($ambiente->ambienteservico as $ambienteservico)
                            <tr>
                                <td>{{$ambienteservico->servico->ser_id}}</td>
                                <td>{{$ambienteservico->servico->ser_nome}}</td>
                                <td>{{$ambienteservico->servico->ser_slug}}</td>
                                <td>{{$ambienteservico->asr_token}}</td>
                                <td>
                                    {!! ActionButton::grid([
                                        'type' => 'LINE',
                                        'buttons' => [
                                            [
                                                'classButton' => 'btn btn-danger btn-delete',
                                                'icon' => 'fa fa-trash',
                                                'action' => '/integracao/ambientesvirtuais/deletarservico/'.$ambiente->amb_id,
                                                'id' => $ambienteservico->asr_id,
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
                <p>Sem serviços adicionados ao ambiente virtual</p>
            @endif
            </div>
        </div>
    </div>
</div>

@stop

@section('scripts')


    <script type="application/javascript">
    $(document).on('click', '.btn-primary', function (event) {
        event.preventDefault();
        var btn = $(this);
        var url = "{{$ambiente->amb_url}}";
        var token = $('#asr_token').val();
        var servico = $('#asr_ser_id').find(":selected").val();

        var request = $.ajax({
                url: url+"webservice/rest/server.php?wstoken="+token+"&wsfunction=ping&moodlewsrestformat=json",
                type: "POST",
                dataType: "json",
                success: function (data) {
                  //$.harpia.hideloading();
                  if (data.response === true){
                    btn.closest('form').trigger("submit");
                  }

                  if (data.response != true){
                    toastr.error('Este token é inválido.', null, {progressBar: true});
                  }
                },
                error: function (error) {

                    toastr.error('Erro ao tentar se comunicar com o Ambiente Virtual.', null, {progressBar: true});

                }
            });

    });

    </script>

@stop
