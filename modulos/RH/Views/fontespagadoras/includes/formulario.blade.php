<div class="row">
    <div class="form-group col-md-4 @if ($errors->has('fpg_razao_social')) has-error @endif">
        {!! Form::label('fpg_razao_social', 'Razão social *', ['class' => 'control-label']) !!}
        <div class="controls">
            {!! Form::text('fpg_razao_social', old('fpg_razao_social'), ['class' => 'form-control']) !!}
            @if ($errors->has('fpg_razao_social')) <p class="help-block">{{ $errors->first('fpg_razao_social') }}</p> @endif
        </div>
    </div>
    <div class="form-group col-md-4 @if ($errors->has('fpg_nome_fantasia')) has-error @endif">
        {!! Form::label('fpg_nome_fantasia', 'Nome fantasia *', ['class' => 'control-label']) !!}
        <div class="controls">
            {!! Form::text('fpg_nome_fantasia', old('fpg_nome_fantasia'), ['class' => 'form-control']) !!}
            @if ($errors->has('fpg_nome_fantasia')) <p class="help-block">{{ $errors->first('fpg_nome_fantasia') }}</p> @endif
        </div>
    </div>
    <div class="form-group col-md-4 @if ($errors->has('fpg_cnpj')) has-error @endif">
        {!! Form::label('fpg_cnpj', 'CNPJ *', ['class' => 'control-label']) !!}
        <div class="controls">
            {!! Form::text('fpg_cnpj', old('fpg_cnpj'), ['class' => 'form-control']) !!}
            @if ($errors->has('fpg_cnpj')) <p class="help-block">{{ $errors->first('fpg_cnpj') }}</p> @endif
        </div>
    </div>
</div>
<div class="row">
    <div class="form-group col-md-4 @if ($errors->has('fpg_cep')) has-error @endif">
        {!! Form::label('fpg_cep', 'CEP', ['class' => 'control-label']) !!}
        <div class="controls">
            {!! Form::text('fpg_cep', old('fpg_cep'), ['class' => 'form-control']) !!}
            @if ($errors->has('fpg_cep')) <p class="help-block">{{ $errors->first('fpg_cep') }}</p> @endif
        </div>
    </div>
    <div class="form-group col-md-4 @if ($errors->has('fpg_endereco')) has-error @endif">
        {!! Form::label('fpg_endereco', 'Endereço', ['class' => 'control-label']) !!}
        <div class="controls">
            {!! Form::text('fpg_endereco', old('fpg_endereco'), ['class' => 'form-control']) !!}
            @if ($errors->has('fpg_endereco')) <p class="help-block">{{ $errors->first('fpg_endereco') }}</p> @endif
        </div>
    </div>
    <div class="form-group col-md-4 @if ($errors->has('fpg_bairro')) has-error @endif">
        {!! Form::label('fpg_bairro', 'Bairro', ['class' => 'control-label']) !!}
        <div class="controls">
            {!! Form::text('fpg_bairro', old('fpg_bairro'), ['class' => 'form-control']) !!}
            @if ($errors->has('fpg_bairro')) <p class="help-block">{{ $errors->first('fpg_bairro') }}</p> @endif
        </div>
    </div>
</div>
<div class="row">
    <div class="form-group col-md-1 @if ($errors->has('fpg_numero')) has-error @endif">
        {!! Form::label('fpg_numero', 'Número', ['class' => 'control-label']) !!}
        <div class="controls">
            {!! Form::text('fpg_numero', old('fpg_numero'), ['class' => 'form-control']) !!}
            @if ($errors->has('fpg_numero')) <p class="help-block">{{ $errors->first('fpg_numero') }}</p> @endif
        </div>
    </div>
    <div class="form-group col-md-5 @if ($errors->has('fpg_complemento')) has-error @endif">
        {!! Form::label('fpg_complemento', 'Complemento', ['class' => 'control-label']) !!}
        <div class="controls">
            {!! Form::text('fpg_complemento', old('fpg_complemento'), ['class' => 'form-control']) !!}
            @if ($errors->has('fpg_complemento')) <p class="help-block">{{ $errors->first('fpg_complemento') }}</p> @endif
        </div>
    </div>
    <div class="form-group col-md-5 @if ($errors->has('fpg_cidade')) has-error @endif">
        {!! Form::label('fpg_cidade', 'Cidade', ['class' => 'control-label']) !!}
        <div class="controls">
            {!! Form::text('fpg_cidade', old('fpg_cidade'), ['class' => 'form-control']) !!}
            @if ($errors->has('fpg_cidade')) <p class="help-block">{{ $errors->first('fpg_cidade') }}</p> @endif
        </div>
    </div>
    <div class="form-group col-md-1 @if ($errors->has('fpg_uf')) has-error @endif">
        {!! Form::label('fpg_uf', 'UF', ['class' => 'control-label']) !!}
        <div class="controls">
            {!! Form::text('fpg_uf', old('fpg_uf'), ['class' => 'form-control']) !!}
            @if ($errors->has('fpg_uf')) <p class="help-block">{{ $errors->first('fpg_uf') }}</p> @endif
        </div>
    </div>
</div>
<div class="row">
    <div class="form-group col-md-4 @if ($errors->has('fpg_email')) has-error @endif">
        {!! Form::label('fpg_email', 'Email', ['class' => 'control-label']) !!}
        <div class="controls">
            {!! Form::text('fpg_email', old('fpg_email'), ['class' => 'form-control']) !!}
            @if ($errors->has('fpg_email')) <p class="help-block">{{ $errors->first('fpg_email') }}</p> @endif
        </div>
    </div>
    <div class="form-group col-md-4 @if ($errors->has('fpg_telefone')) has-error @endif">
        {!! Form::label('fpg_telefone', 'Telefone', ['class' => 'control-label']) !!}
        <div class="controls">
            {!! Form::text('fpg_telefone', old('fpg_telefone'), ['class' => 'form-control']) !!}
            @if ($errors->has('fpg_telefone')) <p class="help-block">{{ $errors->first('fpg_telefone') }}</p> @endif
        </div>
    </div>
    <div class="form-group col-md-4 @if ($errors->has('fpg_celular')) has-error @endif">
        {!! Form::label('fpg_celular', 'Celular', ['class' => 'control-label']) !!}
        <div class="controls">
            {!! Form::text('fpg_celular', old('fpg_celular'), ['class' => 'form-control']) !!}
            @if ($errors->has('fpg_celular')) <p class="help-block">{{ $errors->first('fpg_celular') }}</p> @endif
        </div>
    </div>
</div>
<div class="row">
    <div class="form-group col-md-12 @if ($errors->has('fpg_observacao')) has-error @endif">
        {!! Form::label('fpg_observacao', 'Observação', ['class' => 'control-label']) !!}
        <div class="controls">
            {!! Form::textarea('fpg_observacao', old('fpg_observacao'), ['class' => 'form-control', 'rows' => 4]) !!}
            @if ($errors->has('fpg_observacao')) <p class="help-block">{{ $errors->first('fpg_observacao') }}</p> @endif
        </div>
    </div>
</div>
<div class="row">

    <div class="form-group col-md-12">
        {!! Form::submit('Salvar dados', ['class' => 'btn btn-primary pull-right']) !!}
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