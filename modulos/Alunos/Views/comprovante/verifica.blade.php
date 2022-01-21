@extends('layouts.site')

@section('content')
    <div class="login-box" style="padding-top:10vh">
        <div class="box box-widget widget-user" style="margin-bottom:5px;">
            <div class="box-content" style="border-top:2px solid #0083CE;">
                <div class="login-box-body">
                    <p class="login-box-msg"><b>Digite o código de verificação:</b></p>
                    <div class="row">
                        <form method="POST" action="{{route('alunos.comprovante.verifica')}}">
                            <div class="col-md-12">
                                {{ csrf_field() }}
                                <div class="form-group has-feedback @if ($errors->has('aln_codigo')) has-error @endif">
                                    <input placeholder="Digite o Código de Verificação" class="form-control" name="aln_codigo" id="aln_codigo" type="text" value="{{old('aln_codigo')}}">
                                    <span class="glyphicon glyphicon-credit-card form-control-feedback"></span>
                                    @if ($errors->has('aln_codigo')) <p class="help-block">{{ $errors->first('aln_codigo') }}</p> @endif
                                </div>
                                <input type="hidden" value="{{isset($rota) ? $rota : old('rota')}}" name="rota">
                            </div>
                            <div class="col-md-12">
                                <button type="submit" class="btn btn-primary btn-block btn-flat pull-right btn-localizar">Localizar</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@stop

@section('scripts')
    <script src="{{ asset('/js/plugins/input-mask/inputmask.js') }}"></script>
    <script src="{{ asset('/js/plugins/input-mask/date.extensions.js') }}"></script>
    <script src="{{ asset('/js/plugins/input-mask/inputmask.extensions.js') }}"></script>

    <script type="text/javascript">
        Inputmask({"mask": "########-####-####-####-############", "removeMaskOnSubmit": false}).mask('#aln_codigo');
    </script>
@stop