@extends('layouts.modulos.default')

@section('content')
    <div class="vp-page">
        <div class="login-box">
            <div class="card">
                <div class="card-body login-card-body">
                    <p class="login-box-msg"><b>Verificação Pessoa por CPF</b></p>

                    <form id="dvCpf" method="POST" action="{{route('geral.pessoas.verificapessoa')}}">
                        @csrf
                        <div class="col-12">
                            {{ csrf_field() }}
                            <div class="form-group has-feedback @if ($errors->has('doc_conteudo')) has-error @endif">
                                <input placeholder="Digite o CPF" class="form-control" name="doc_conteudo" id="doc_conteudo" type="text" value="{{old('doc_conteudo')}}">
                                <span class="glyphicon glyphicon-credit-card form-control-feedback"></span>
                                @if ($errors->has('doc_conteudo')) <p class="help-block">{{ $errors->first('doc_conteudo') }}</p> @endif
                            </div>
                            <input type="hidden" value="{{isset($rota) ? $rota : old('rota')}}" name="rota">
                        </div>
                        <div class="col-12 text-center py-2">
                            <div class="d-grid gap-2">
                                <button type="submit" class="btn btn-primary">Localizar</button>
                            </div>
                        </div>
                    </form>
                </div>
                <!-- /.login-card-body -->
            </div>
        </div>
    </div>
@stop

@section('scripts')
    <script src="{{ asset('/js/plugins/input-mask/inputmask.js') }}"></script>
    <script src="{{ asset('/js/plugins/input-mask/date.extensions.js') }}"></script>
    <script src="{{ asset('/js/plugins/input-mask/inputmask.extensions.js') }}"></script>

    <script type="text/javascript">
        Inputmask({"mask": "999.999.999-99", "removeMaskOnSubmit": true}).mask('#doc_conteudo');
    </script>
@stop