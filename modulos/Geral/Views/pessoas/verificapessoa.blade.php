@extends('layouts.modulos.seguranca')

@section('content')
    <div class="login-box" style="padding-top:10vh">
        <div class="box box-widget widget-user" style="margin-bottom:5px;">
            <div class="box-content" style="border-top:2px solid #0083CE;">
                <div class="login-box-body">
                    <p class="login-box-msg"><b>Verificação Pessoa por CPF</b></p>
                    <div class="row">
                        <div class="col-md-12">
                            <form id="dvCpf" class="form-group has-feedback" method="GET" action="{{url('/geral/pessoas/verificapessoa')}}">
                                <input placeholder="Digite o CPF" class="form-control" name="doc_conteudo" id="doc_conteudo" type="text">
                                <input type="hidden" value="{{$rota}}" name="rota">
                                <span class="glyphicon glyphicon-credit-card form-control-feedback"></span>
                                <p class="help-block" id="msg"></p>
                            </form>
                        </div>
                        <div class="col-md-12">
                            <button type="button" class="btn btn-primary btn-block btn-flat pull-right btn-localizar">Localizar</button>
                        </div>
                    </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@stop

@section('scripts')
    <script src="{{ asset('/js/plugins/input-mask/inputmask.js') }}"></script>
    <script src="{{ asset('/js/plugins/input-mask/date.extensions.js') }}"></script>
    <script src="{{ asset('/js/plugins/input-mask/inputmask.extensions.js') }}"></script>

    <script src="{{ asset('/js/plugins/cpfcnpj.min.js') }}"></script>

    <script type="text/javascript">
        Inputmask({"mask": "999.999.999-99", "removeMaskOnSubmit": true}).mask('#doc_conteudo');

        $(function (){
            $('#doc_conteudo').cpfcnpj({
                validate: 'cpfcnpj',
                event: 'click',
                handler: '.btn',
                ifValid: function (input){
                    $('#dvCpf').removeClass("has-error");
                    $('#msg').html("");

                    var cpf = $("#doc_conteudo").val();

                    if (!cpf){
                        return;
                    }

                    $('#dvCpf').submit();
                },
                ifInvalid: function (input){
                    $('#dvCpf').addClass("has-error");
                    $('#msg').html("Cpf Inválido!");
                }
            });
        });
    </script>
@stop