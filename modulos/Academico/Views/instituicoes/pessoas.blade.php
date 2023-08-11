@extends('layouts.modulos.integracao')

@section('title')
    Pessoas da Instituição
@stop

@section('stylesheets')
  <link rel="stylesheet" href="{{asset('/css/plugins/select2.css')}}">
@endsection

@section('subtitle')
    {{$instituicao->itt_nome}}
@stop

@section('content')
<div class="box box-primary">
    <div class="box-header with-border">
        <h3 class="box-title">Vincular Pessoas a Instituição</h3>
    </div>


<div class="box-body">
    <div class="row">
      {!! Form::open(array('route' => ['academico.instituicoes.pessoas', $instituicao->itt_id], 'method' => 'POST', 'id' => 'formAtribuirPerfil')) !!}

          <div class="form-group col-md-3">
                  {!! Form::select('pes_id', $pessoas, old('pes_id'), ['class' => 'form-control', 'id' => 'pes_id', 'placeholder' => 'Selecione a pessoa']) !!}
                  @if ($errors->has('pes_id')) <p class="help-block">{{ $errors->first('pes_id') }}</p> @endif
          </div>

          <div class="form-group col-md-3">
              {!! Form::submit('Vincular', ['class' => 'btn btn-primary', 'id' => 'btnAtribuir']) !!}
          </div>
      {!! Form::close() !!}
    </div>

    <div class="row">
        <div class="col-md-12">
        @if(count($instituicao->pessoas))
            <table class="table table-bordered table-striped table-hover">
                <thead>
                    <th style="width: 10px">#</th>
                    <th style="width: 10px">Nome</th>
                    <th style="width: 10px">Papel</th>
                    <th style="width: 10px">Permissoes</th>
                </thead>
                <tbody>
                    @foreach($instituicao->pessoas as $pessoa)
                        <tr>
                            <td>{{$pessoa->pes_id}}</td>
                            <td>{{$pessoa->pes_nome}}</td>
                            <td>
                                @if($pessoa->professor)
                                    <span class="label label-primary">Professor</span>
                                @endif
                                @if($pessoa->aluno)
                                    <span class="label label-primary">Aluno</span>
                                @endif
                                @if($pessoa->tutor)
                                    <span class="label label-primary">Tutor</span>
                                @endif
                                @if($pessoa->usuario)
                                    <span class="label label-danger">Usuário</span>
                                @endif
                            </td>
                            <td>
                                @foreach($pessoa->perfis as $perfil)
                                    <span class="label label-default">{{ $perfil->prf_nome }}</span>
                                @endforeach
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @else
            <p>Sem pessoas vinculadas a instituição</p>
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
