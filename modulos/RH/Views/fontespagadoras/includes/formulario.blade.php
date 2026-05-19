<div class="row">
    <div class="form-group col-md-4 @if ($errors->has('fpg_razao_social')) has-error @endif">
        <label for="fpg_razao_social" class="control-label">Razão social *</label>
        <div class="controls">
            <input type="text" name="fpg_razao_social" value="{{ old('fpg_razao_social') }}" class="form-control" >
            @if ($errors->has('fpg_razao_social')) <p class="help-block">{{ $errors->first('fpg_razao_social') }}</p> @endif
        </div>
    </div>
    <div class="form-group col-md-4 @if ($errors->has('fpg_nome_fantasia')) has-error @endif">
        <label for="fpg_nome_fantasia" class="control-label">Nome fantasia *</label>
        <div class="controls">
            <input type="text" name="fpg_nome_fantasia" value="{{ old('fpg_nome_fantasia') }}" class="form-control" >
            @if ($errors->has('fpg_nome_fantasia')) <p class="help-block">{{ $errors->first('fpg_nome_fantasia') }}</p> @endif
        </div>
    </div>
    <div class="form-group col-md-4 @if ($errors->has('fpg_cnpj')) has-error @endif">
        <label for="fpg_cnpj" class="control-label">CNPJ *</label>
        <div class="controls">
            <input type="text" name="fpg_cnpj" value="{{ old('fpg_cnpj') }}" class="form-control" >
            @if ($errors->has('fpg_cnpj')) <p class="help-block">{{ $errors->first('fpg_cnpj') }}</p> @endif
        </div>
    </div>
</div>
<div class="row">
    <div class="form-group col-md-4 @if ($errors->has('fpg_cep')) has-error @endif">
        <label for="fpg_cep" class="control-label">CEP</label>
        <div class="controls">
            <input type="text" name="fpg_cep" value="{{ old('fpg_cep') }}" class="form-control" >
            @if ($errors->has('fpg_cep')) <p class="help-block">{{ $errors->first('fpg_cep') }}</p> @endif
        </div>
    </div>
    <div class="form-group col-md-4 @if ($errors->has('fpg_endereco')) has-error @endif">
        <label for="fpg_endereco" class="control-label">Endereço</label>
        <div class="controls">
            <input type="text" name="fpg_endereco" value="{{ old('fpg_endereco') }}" class="form-control" >
            @if ($errors->has('fpg_endereco')) <p class="help-block">{{ $errors->first('fpg_endereco') }}</p> @endif
        </div>
    </div>
    <div class="form-group col-md-4 @if ($errors->has('fpg_bairro')) has-error @endif">
        <label for="fpg_bairro" class="control-label">Bairro</label>
        <div class="controls">
            <input type="text" name="fpg_bairro" value="{{ old('fpg_bairro') }}" class="form-control" >
            @if ($errors->has('fpg_bairro')) <p class="help-block">{{ $errors->first('fpg_bairro') }}</p> @endif
        </div>
    </div>
</div>
<div class="row">
    <div class="form-group col-md-1 @if ($errors->has('fpg_numero')) has-error @endif">
        <label for="fpg_numero" class="control-label">Número</label>
        <div class="controls">
            <input type="text" name="fpg_numero" value="{{ old('fpg_numero') }}" class="form-control" >
            @if ($errors->has('fpg_numero')) <p class="help-block">{{ $errors->first('fpg_numero') }}</p> @endif
        </div>
    </div>
    <div class="form-group col-md-5 @if ($errors->has('fpg_complemento')) has-error @endif">
        <label for="fpg_complemento" class="control-label">Complemento</label>
        <div class="controls">
            <input type="text" name="fpg_complemento" value="{{ old('fpg_complemento') }}" class="form-control" >
            @if ($errors->has('fpg_complemento')) <p class="help-block">{{ $errors->first('fpg_complemento') }}</p> @endif
        </div>
    </div>
    <div class="form-group col-md-5 @if ($errors->has('fpg_cidade')) has-error @endif">
        <label for="fpg_cidade" class="control-label">Cidade</label>
        <div class="controls">
            <input type="text" name="fpg_cidade" value="{{ old('fpg_cidade') }}" class="form-control" >
            @if ($errors->has('fpg_cidade')) <p class="help-block">{{ $errors->first('fpg_cidade') }}</p> @endif
        </div>
    </div>
    <div class="form-group col-md-1 @if ($errors->has('fpg_uf')) has-error @endif">
        <label for="fpg_uf" class="control-label">UF</label>
        <div class="controls">
            <input type="text" name="fpg_uf" value="{{ old('fpg_uf') }}" class="form-control" >
            @if ($errors->has('fpg_uf')) <p class="help-block">{{ $errors->first('fpg_uf') }}</p> @endif
        </div>
    </div>
</div>
<div class="row">
    <div class="form-group col-md-4 @if ($errors->has('fpg_email')) has-error @endif">
        <label for="fpg_email" class="control-label">Email</label>
        <div class="controls">
            <input type="text" name="fpg_email" value="{{ old('fpg_email') }}" class="form-control" >
            @if ($errors->has('fpg_email')) <p class="help-block">{{ $errors->first('fpg_email') }}</p> @endif
        </div>
    </div>
    <div class="form-group col-md-4 @if ($errors->has('fpg_telefone')) has-error @endif">
        <label for="fpg_telefone" class="control-label">Telefone</label>
        <div class="controls">
            <input type="text" name="fpg_telefone" value="{{ old('fpg_telefone') }}" class="form-control" >
            @if ($errors->has('fpg_telefone')) <p class="help-block">{{ $errors->first('fpg_telefone') }}</p> @endif
        </div>
    </div>
    <div class="form-group col-md-4 @if ($errors->has('fpg_celular')) has-error @endif">
        <label for="fpg_celular" class="control-label">Celular</label>
        <div class="controls">
            <input type="text" name="fpg_celular" value="{{ old('fpg_celular') }}" class="form-control" >
            @if ($errors->has('fpg_celular')) <p class="help-block">{{ $errors->first('fpg_celular') }}</p> @endif
        </div>
    </div>
</div>
<div class="row">
    <div class="form-group col-md-12 @if ($errors->has('fpg_observacao')) has-error @endif">
        <label for="fpg_observacao" class="control-label">Observação</label>
        <div class="controls">
            <textarea name="fpg_observacao" class="form-control" rows="4">{{ old('fpg_observacao') }}</textarea>
            @if ($errors->has('fpg_observacao')) <p class="help-block">{{ $errors->first('fpg_observacao') }}</p> @endif
        </div>
    </div>
</div>
<div class="row">

    <div class="form-group col-md-12">
        <button type="submit" class="btn btn-primary float-end">Salvar dados</button>
    </div>
</div>



@section('scripts')
    <script src="{{ asset('/js/plugins/input-mask/inputmask.js') }}"></script>
    <script src="{{ asset('/js/plugins/input-mask/inputmask.date.extensions.js') }}"></script>
    <script src="{{ asset('/js/plugins/input-mask/jquery.inputmask.js') }}"></script>
    <script src="{{ asset('/js/plugins/bootstrap-datepicker.js') }}"></script>
    <script src="{{asset('/js/plugins/bootstrap-datepicker.pt-BR.js')}}"></script>
    <script src="{{ asset('/js/plugins/cpfcnpj.min.js') }}"></script>
    <script src="{{asset('/js/plugins/select2.js')}}" type="text/javascript"></script>

    <script>

        $(function (){

            $("select").select2();

            $('.datepicker').datepicker({
                format: "dd/mm/yyyy",
                language: 'pt-BR',
                autoclose: true
            });

            $('#doc_conteudo').inputmask({"mask": "999.999.999-99", "removeMaskOnSubmit": true});
            $('#fpg_cnpj').inputmask({"mask": "99.999.999/9999-99", "removeMaskOnSubmit": true});
            $('#fpg_celular').inputmask({"mask": "(99) 99999-9999", "removeMaskOnSubmit": true});
            $('#fpg_telefone').inputmask({"mask": "(99) 99999-9999", "removeMaskOnSubmit": true});
            $('#fpg_cep').inputmask({"mask": "99999-999", "removeMaskOnSubmit": true});

            $("#fpg_cep").focusout(function(e){

                function limpaFormCep() {

                    $("#fpg_cidade").val("");
                    $("#fpg_estado").val("");
                    $("#fpg_bairro").val("");
                    $("#fpg_endereco").val("");
                }

                var str = e.target.value;

                var cep = str.replace(/\D/g, '');

                if (str != "") {
                    //Expressão regular para validar o CEP.
                    var validacep = /^[0-9]{8}$/;

                    if(validacep.test(cep)) {

                        $("#fpg_cidade").val("Buscando...");
                        $("#fpg_estado").val("Buscando...");
                        $("#fpg_bairro").val("Buscando...");
                        $("#fpg_endereco").val("Buscando...");

                        $.harpia.httpget('https://viacep.com.br/ws/' + cep + '/json/').done(function (data) {
                            if (!data.erro) {
                                $("#fpg_cidade").val(data.localidade);
                                $("#fpg_uf").val(data.uf).change();
                                $("#fpg_bairro").val(data.bairro);
                                $("#fpg_endereco").val(data.logradouro);
                            } else {
                                limpaFormCep();
                                toastr.error("CEP não encontrado", null, {progressBar: true});
                            }
                        });
                    } else {
                        limpaFormCep();
                        toastr.warning("Formato do CEP inválido", null, {progressBar: true});
                    }
                } else {
                    limpaFormCep();
                }
            });
        });
    </script>
@endsection