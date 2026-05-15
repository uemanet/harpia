<div class="row">
    <div class="form-group col-md-4 @if ($errors->has('fpg_razao_social')) has-error @endif">
        <label for="fpg_razao_social" class="form-label">Razão social *</label>
        <div class="controls">
            <input type="text" name="fpg_razao_social" value="{{ old('fpg_razao_social') }}" class="form-control" >
            @if ($errors->has('fpg_razao_social')) <p class="help-block">{{ $errors->first('fpg_razao_social') }}</p> @endif
        </div>
    </div>
    <div class="form-group col-md-4 @if ($errors->has('fpg_nome_fantasia')) has-error @endif">
        <label for="fpg_nome_fantasia" class="form-label">Nome fantasia *</label>
        <div class="controls">
            <input type="text" name="fpg_nome_fantasia" value="{{ old('fpg_nome_fantasia') }}" class="form-control" >
            @if ($errors->has('fpg_nome_fantasia')) <p class="help-block">{{ $errors->first('fpg_nome_fantasia') }}</p> @endif
        </div>
    </div>
    <div class="form-group col-md-4 @if ($errors->has('fpg_cnpj')) has-error @endif">
        <label for="fpg_cnpj" class="form-label">CNPJ *</label>
        <div class="controls">
            <input type="text" name="fpg_cnpj" value="{{ old('fpg_cnpj') }}" class="form-control" >
            @if ($errors->has('fpg_cnpj')) <p class="help-block">{{ $errors->first('fpg_cnpj') }}</p> @endif
        </div>
    </div>
</div>
<div class="row">
    <div class="form-group col-md-4 @if ($errors->has('fpg_cep')) has-error @endif">
        <label for="fpg_cep" class="form-label">CEP</label>
        <div class="controls">
            <input type="text" name="fpg_cep" value="{{ old('fpg_cep') }}" class="form-control" >
            @if ($errors->has('fpg_cep')) <p class="help-block">{{ $errors->first('fpg_cep') }}</p> @endif
        </div>
    </div>
    <div class="form-group col-md-4 @if ($errors->has('fpg_endereco')) has-error @endif">
        <label for="fpg_endereco" class="form-label">Endereço</label>
        <div class="controls">
            <input type="text" name="fpg_endereco" value="{{ old('fpg_endereco') }}" class="form-control" >
            @if ($errors->has('fpg_endereco')) <p class="help-block">{{ $errors->first('fpg_endereco') }}</p> @endif
        </div>
    </div>
    <div class="form-group col-md-4 @if ($errors->has('fpg_bairro')) has-error @endif">
        <label for="fpg_bairro" class="form-label">Bairro</label>
        <div class="controls">
            <input type="text" name="fpg_bairro" value="{{ old('fpg_bairro') }}" class="form-control" >
            @if ($errors->has('fpg_bairro')) <p class="help-block">{{ $errors->first('fpg_bairro') }}</p> @endif
        </div>
    </div>
</div>
<div class="row">
    <div class="form-group col-md-1 @if ($errors->has('fpg_numero')) has-error @endif">
        <label for="fpg_numero" class="form-label">Número</label>
        <div class="controls">
            <input type="text" name="fpg_numero" value="{{ old('fpg_numero') }}" class="form-control" >
            @if ($errors->has('fpg_numero')) <p class="help-block">{{ $errors->first('fpg_numero') }}</p> @endif
        </div>
    </div>
    <div class="form-group col-md-5 @if ($errors->has('fpg_complemento')) has-error @endif">
        <label for="fpg_complemento" class="form-label">Complemento</label>
        <div class="controls">
            <input type="text" name="fpg_complemento" value="{{ old('fpg_complemento') }}" class="form-control" >
            @if ($errors->has('fpg_complemento')) <p class="help-block">{{ $errors->first('fpg_complemento') }}</p> @endif
        </div>
    </div>
    <div class="form-group col-md-5 @if ($errors->has('fpg_cidade')) has-error @endif">
        <label for="fpg_cidade" class="form-label">Cidade</label>
        <div class="controls">
            <input type="text" name="fpg_cidade" value="{{ old('fpg_cidade') }}" class="form-control" >
            @if ($errors->has('fpg_cidade')) <p class="help-block">{{ $errors->first('fpg_cidade') }}</p> @endif
        </div>
    </div>
    <div class="form-group col-md-1 @if ($errors->has('fpg_uf')) has-error @endif">
        <label for="fpg_uf" class="form-label">UF</label>
        <div class="controls">
            <input type="text" name="fpg_uf" value="{{ old('fpg_uf') }}" class="form-control" >
            @if ($errors->has('fpg_uf')) <p class="help-block">{{ $errors->first('fpg_uf') }}</p> @endif
        </div>
    </div>
</div>
<div class="row">
    <div class="form-group col-md-4 @if ($errors->has('fpg_email')) has-error @endif">
        <label for="fpg_email" class="form-label">Email</label>
        <div class="controls">
            <input type="text" name="fpg_email" value="{{ old('fpg_email') }}" class="form-control" >
            @if ($errors->has('fpg_email')) <p class="help-block">{{ $errors->first('fpg_email') }}</p> @endif
        </div>
    </div>
    <div class="form-group col-md-4 @if ($errors->has('fpg_telefone')) has-error @endif">
        <label for="fpg_telefone" class="form-label">Telefone</label>
        <div class="controls">
            <input type="text" name="fpg_telefone" value="{{ old('fpg_telefone') }}" class="form-control" >
            @if ($errors->has('fpg_telefone')) <p class="help-block">{{ $errors->first('fpg_telefone') }}</p> @endif
        </div>
    </div>
    <div class="form-group col-md-4 @if ($errors->has('fpg_celular')) has-error @endif">
        <label for="fpg_celular" class="form-label">Celular</label>
        <div class="controls">
            <input type="text" name="fpg_celular" value="{{ old('fpg_celular') }}" class="form-control" >
            @if ($errors->has('fpg_celular')) <p class="help-block">{{ $errors->first('fpg_celular') }}</p> @endif
        </div>
    </div>
</div>
<div class="row">
    <div class="form-group col-md-12 @if ($errors->has('fpg_observacao')) has-error @endif">
        <label for="fpg_observacao" class="form-label">Observação</label>
        <div class="controls">
            <textarea name="fpg_observacao" class="form-control" rows="4">{{ old('fpg_observacao') }}</textarea>
            @if ($errors->has('fpg_observacao')) <p class="help-block">{{ $errors->first('fpg_observacao') }}</p> @endif
        </div>
    </div>
</div>
<div class="row">

    <div class="form-group col-md-12">
        <button type="submit" class="btn btn-primary pull-right">Salvar dados</button>
    </div>
</div>


@section('scripts')
    <script>
        window.PageRoutes = {
            {{--alterarsituacao: "{{ route('academico.async.matricula.alterarsituacao') }}"--}}
        };
    </script>

    @vite('modulos/RH/Resources/js/pages/fontespagadoras/formulario.js')
@stop