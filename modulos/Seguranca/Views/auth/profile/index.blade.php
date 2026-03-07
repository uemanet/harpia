@extends('layouts.clean')

@section('stylesheets')
    <link rel="stylesheet" href="{{ asset('/css/plugins/datepicker3.css') }}">
    <link rel="stylesheet" href="{{asset('/css/plugins/select2.css')}}">
@stop

@section('title')
    Módulo de Segurança
@stop

@section('subtitle')
    Perfil do usuário
@stop

@section('content')
    <div class="container" style="padding-top: 20px;">
        <div class="row">
            <div class="col-md-3">

                <!-- Profile Image -->
                <div class="box box-primary">
                    <div class="box-body box-profile">
                        <img class="profile-user-img img-responsive img-circle" src="{{{ route('seguranca.profile.profile-picture', \Illuminate\Support\Facades\Auth::user()->usr_profile_picture_id ?? 0) }}}"
                             alt="User profile picture">

                        <h3 class="profile-username text-center">{{$usuario->pessoa->pes_nome}}</h3>

                        <p class="text-muted text-center">{{$usuario->pessoa->pes_email}}</p>
                    </div>
                    <!-- /.box-body -->
                </div>
                <!-- /.box -->
            </div>
            <!-- /.col -->
            <div class="col-md-9">
                <div class="nav-tabs-custom">
                    <ul class="nav nav-tabs">
                        <li class="@if (!$errors->has('usr_senha') and !$errors->has('usr_senha_nova') and !$errors->has('usr_senha_nova_confirmation')) active @endif"><a href="#dados" data-toggle="tab">Dados pessoais</a></li>
                        <li><a href="#endereco" data-toggle="tab">Endereço</a></li>
                        <li class = "@if ($errors->has('usr_senha') or $errors->has('usr_senha_nova') or $errors->has('usr_senha_nova_confirmation')) active @endif"><a href="#senha" data-toggle="tab">Alterar Senha</a></li>
                        <li><a href="#foto" data-toggle="tab">Alterar Foto</a></li>
                    </ul>
                    <div class="tab-content">

                        <div class="tab-pane @if (!$errors->has('usr_senha') and !$errors->has('usr_senha_nova') and !$errors->has('usr_senha_nova_confirmation')) active @endif" id="dados">
                            <form action="{{ route('seguranca.profile.edit') }}" method="POST" id="form" role="form">
    @csrf
    @method('PUT')
    {{-- Form model: $usuario->pessoa - inputs devem usar old('campo', $usuario->pessoa->campo) --}}
                            <div class="form-group @if ($errors->has('pes_nome')) has-error @endif">
                                <label for="pes_nome" class="col-sm-3 control-label">Nome completo*</label>

                                <div class="col-sm-9">
                                    @haspermission('seguranca.profile.edit')
                                        <input type="text" name="pes_nome" value="{{ old('pes_nome') }}" class="form-control" >
                                    @else
                                        <input type="text" name="pes_nome" value="{{ old('pes_nome') }}" class="form-control" >
                                    @endif
                                    @if ($errors->has('pes_nome')) <p
                                            class="help-block">{{ $errors->first('pes_nome') }}</p> @endif
                                </div>
                            </div>
                            <div class="form-group @if ($errors->has('pes_email')) has-error @endif">
                                <label for="pes_email" class="col-sm-3 control-label">Email*</label>
                                <div class="col-sm-9">
                                    @haspermission('seguranca.profile.edit')
                                        <input type="email" name="pes_email" value="{{ old('pes_email') }}" class="form-control" >
                                    @else
                                        <input type="email" name="pes_email" value="{{ old('pes_email') }}" class="form-control" >
                                    @endif
                                    @if ($errors->has('pes_email')) <p
                                            class="help-block">{{ $errors->first('pes_email') }}</p> @endif
                                </div>
                            </div>
                            <div class="form-group @if ($errors->has('pes_telefone')) has-error @endif">
                                <label for="pes_telefone" class="col-sm-3 control-label">Telefone*</label>

                                <div class="col-sm-9">
                                    @haspermission('seguranca.profile.edit')
                                        <input type="text" name="pes_telefone" value="{{ old('pes_telefone') }}" class="form-control" >
                                    @else
                                        <input type="text" name="pes_telefone" value="{{ old('pes_telefone') }}" class="form-control" >
                                    @endif
                                    @if ($errors->has('pes_telefone')) <p
                                            class="help-block">{{ $errors->first('pes_telefone') }}</p> @endif
                                </div>
                            </div>
                            <div class="form-group @if ($errors->has('pes_sexo')) has-error @endif">
                                <label for="pes_sexo" class="col-sm-3 control-label">Sexo*</label>

                                <div class="col-sm-9">
                                    @haspermission('seguranca.profile.edit')
                                        <select name="pes_sexo" class="form-control">
    <option value="M" {{ old('pes_sexo') == 'M' ? 'selected' : '' }}>Masculino</option>
    <option value="F" {{ old('pes_sexo') == 'F' ? 'selected' : '' }}>Feminino</option>
</select>
                                    @else
                                        <select name="pes_sexo" class="form-control">
    <option value="M" {{ old('pes_sexo') == 'M' ? 'selected' : '' }}>Masculino</option>
    <option value="F" {{ old('pes_sexo') == 'F' ? 'selected' : '' }}>Feminino</option>
</select>
                                    @endif
                                    @if ($errors->has('pes_sexo')) <p
                                            class="help-block">{{ $errors->first('pes_sexo') }}</p> @endif
                                </div>
                            </div>
                            <div class="form-group @if ($errors->has('pes_nascimento')) has-error @endif">
                                <label for="pes_nascimento" class="col-sm-3 control-label">Nascimento*</label>

                                <div class="col-sm-9">
                                    @haspermission('seguranca.profile.edit')
                                        <input type="text" name="pes_nascimento" value="{{ old('pes_nascimento') }}" class="form-control datepicker" >
                                    @else
                                        <input type="text" name="pes_nascimento" value="{{ old('pes_nascimento') }}" class="form-control datepicker" >
                                    @endif
                                    @if ($errors->has('pes_nascimento')) <p
                                            class="help-block">{{ $errors->first('pes_nascimento') }}</p> @endif
                                </div>
                            </div>
                            <div class="form-group @if ($errors->has('pes_estado_civil')) has-error @endif">
                                <label for="pes_estado_civil" class="col-sm-3 control-label">Estado Civil*</label>

                                <div class="col-sm-9">
                                    @haspermission('seguranca.profile.edit')
                                        <select name="pes_estado_civil" class="form-control">
    <option value="uniao_estavel" {{ old('pes_estado_civil') == 'uniao_estavel' ? 'selected' : '' }}>União estável</option>
    <option value="outro" {{ old('pes_estado_civil') == 'outro' ? 'selected' : '' }}>Outro</option>
</select>
                                    @else
                                        <select name="pes_estado_civil" class="form-control">
    <option value="uniao_estavel" {{ old('pes_estado_civil') == 'uniao_estavel' ? 'selected' : '' }}>União estável</option>
    <option value="outro" {{ old('pes_estado_civil') == 'outro' ? 'selected' : '' }}>Outro</option>
</select>
                                    @endif
                                    @if ($errors->has('pes_estado_civil')) <p
                                            class="help-block">{{ $errors->first('pes_estado_civil') }}</p> @endif
                                </div>
                            </div>
                            <div class="form-group @if ($errors->has('pes_mae')) has-error @endif">
                                <label for="pes_mae" class="col-sm-3 control-label">Nome da mãe*</label>

                                <div class="col-sm-9">
                                    @haspermission('seguranca.profile.edit')
                                        <input type="text" name="pes_mae" value="{{ old('pes_mae') }}" class="form-control" >
                                    @else
                                        <input type="text" name="pes_mae" value="{{ old('pes_mae') }}" class="form-control" >
                                    @endif
                                    @if ($errors->has('pes_mae')) <p
                                            class="help-block">{{ $errors->first('pes_mae') }}</p> @endif
                                </div>
                            </div>
                            <div class="form-group @if ($errors->has('pes_pai')) has-error @endif">
                                <label for="pes_pai" class="col-sm-3 control-label">Nome do pai</label>

                                <div class="col-sm-9">
                                    @haspermission('seguranca.profile.edit')
                                        <input type="text" name="pes_pai" value="{{ old('pes_pai') }}" class="form-control" >
                                    @else
                                        <input type="text" name="pes_pai" value="{{ old('pes_pai') }}" class="form-control" >
                                    @endif
                                    @if ($errors->has('pes_pai')) <p
                                            class="help-block">{{ $errors->first('pes_pai') }}</p> @endif
                                </div>
                            </div>
                            <div class="form-group @if ($errors->has('pes_naturalidade')) has-error @endif">
                                <label for="pes_naturalidade" class="col-sm-3 control-label">Naturalidade*</label>

                                <div class="col-sm-9">
                                    @haspermission('seguranca.profile.edit')
                                        <input type="text" name="pes_naturalidade" value="{{ old('pes_naturalidade') }}" class="form-control" >
                                    @else
                                        <input type="text" name="pes_naturalidade" value="{{ old('pes_naturalidade') }}" class="form-control" >
                                    @endif
                                    @if ($errors->has('pes_naturalidade')) <p
                                            class="help-block">{{ $errors->first('pes_naturalidade') }}</p> @endif
                                </div>
                            </div>
                            <div class="form-group @if ($errors->has('pes_nacionalidade')) has-error @endif">
                                <label for="pes_nacionalidade" class="col-sm-3 control-label">Nacionalidade*</label>

                                <div class="col-sm-9">
                                    @haspermission('seguranca.profile.edit')
                                        <input type="text" name="pes_nacionalidade" value="{{ old('pes_nacionalidade') }}" class="form-control" >
                                    @else
                                        <input type="text" name="pes_nacionalidade" value="{{ old('pes_nacionalidade') }}" class="form-control" >
                                    @endif
                                    @if ($errors->has('pes_nacionalidade')) <p
                                            class="help-block">{{ $errors->first('pes_nacionalidade') }}</p> @endif
                                </div>
                            </div>
                            <div class="form-group @if ($errors->has('pes_raca')) has-error @endif">
                                <label for="pes_raca" class="col-sm-3 control-label">Cor/Raça*</label>

                                <div class="col-sm-9">
                                    @haspermission('seguranca.profile.edit')
                                        <select name="pes_raca" class="form-control">
    <option value="branca" {{ old('pes_raca') == 'branca' ? 'selected' : '' }}>Branca</option>
    <option value="preta" {{ old('pes_raca') == 'preta' ? 'selected' : '' }}>Preta</option>
    <option value="parda" {{ old('pes_raca') == 'parda' ? 'selected' : '' }}>Parda</option>
    <option value="amarela" {{ old('pes_raca') == 'amarela' ? 'selected' : '' }}>Amarela</option>
    <option value="indigena" {{ old('pes_raca') == 'indigena' ? 'selected' : '' }}>Indígena</option>
    <option value="outra" {{ old('pes_raca') == 'outra' ? 'selected' : '' }}>Outra</option>
</select>
                                    @else
                                        <select name="pes_raca" class="form-control">
    <option value="branca" {{ old('pes_raca') == 'branca' ? 'selected' : '' }}>Branca</option>
    <option value="preta" {{ old('pes_raca') == 'preta' ? 'selected' : '' }}>Preta</option>
    <option value="parda" {{ old('pes_raca') == 'parda' ? 'selected' : '' }}>Parda</option>
    <option value="amarela" {{ old('pes_raca') == 'amarela' ? 'selected' : '' }}>Amarela</option>
    <option value="indigena" {{ old('pes_raca') == 'indigena' ? 'selected' : '' }}>Indígena</option>
    <option value="outra" {{ old('pes_raca') == 'outra' ? 'selected' : '' }}>Outra</option>
</select>
                                    @endif
                                    @if ($errors->has('pes_raca')) <p
                                            class="help-block">{{ $errors->first('pes_raca') }}</p> @endif
                                </div>
                            </div>
                            <div class="form-group @if ($errors->has('pes_necessidade_especial')) has-error @endif">
                                <label for="pes_necessidade_especial" class="col-sm-3 control-label">Necessidade especial?*</label>

                                <div class="col-sm-9">
                                    @haspermission('seguranca.profile.edit')
                                        <select name="pes_necessidade_especial" class="form-control">
    <option value="S" {{ old('pes_necessidade_especial') == 'S' ? 'selected' : '' }}>Sim</option>
    <option value="N" {{ old('pes_necessidade_especial') == 'N' ? 'selected' : '' }}>Não</option>
</select>
                                    @else
                                        <select name="pes_necessidade_especial" class="form-control">
    <option value="S" {{ old('pes_necessidade_especial') == 'S' ? 'selected' : '' }}>Sim</option>
    <option value="N" {{ old('pes_necessidade_especial') == 'N' ? 'selected' : '' }}>Não</option>
</select>
                                    @endif
                                    @if ($errors->has('pes_necessidade_especial')) <p
                                            class="help-block">{{ $errors->first('pes_necessidade_especial') }}</p> @endif
                                </div>
                            </div>

                            <div class="form-group @if ($errors->has('pes_estrangeiro')) has-error @endif">
                                <label for="pes_estrangeiro" class="col-sm-3 control-label">Estrangeiro?*</label>

                                <div class="col-sm-9">
                                    @haspermission('seguranca.profile.edit')
                                        <select name="pes_estrangeiro" class="form-control">
    <option value="0" {{ old('pes_estrangeiro') == '0' ? 'selected' : '' }}>Não</option>
    <option value="1" {{ old('pes_estrangeiro') == '1' ? 'selected' : '' }}>Sim</option>
</select>
                                    @else
                                        <select name="pes_estrangeiro" class="form-control">
    <option value="0" {{ old('pes_estrangeiro') == '0' ? 'selected' : '' }}>Não</option>
    <option value="1" {{ old('pes_estrangeiro') == '1' ? 'selected' : '' }}>Sim</option>
</select>
                                    @endif

                                    @if ($errors->has('pes_estrangeiro')) <p
                                            class="help-block">{{ $errors->first('pes_estrangeiro') }}</p> @endif
                                </div>
                            </div>
                            @haspermission('seguranca.profile.edit')
                            <div class="form-group">
                                <div class="col-sm-offset-2 col-sm-10">
                                    <button type="submit" class="btn btn-primary">Atualizar informações</button>
                                </div>
                            </div>
                            @endhaspermission
                            </form>
                        </div>

                        <div class="tab-pane" id="endereco">
                            <form action="{{ route('seguranca.profile.edit') }}" method="POST" id="form" role="form">
    @csrf
    {{-- Form model: $usuario->pessoa - inputs devem usar old('campo', $usuario->pessoa->campo) --}}
                            <div class="form-group @if ($errors->has('pes_cep')) has-error @endif">
                                <label for="pes_cep" class="col-sm-3 control-label">CEP*</label>

                                <div class="col-sm-9">
                                    @haspermission('seguranca.profile.edit')
                                        <input type="text" name="pes_cep" value="{{ old('pes_cep') }}" class="form-control" >
                                    @else
                                        <input type="text" name="pes_cep" value="{{ old('pes_cep') }}" class="form-control" >
                                    @endif
                                    @if ($errors->has('pes_cep')) <p
                                            class="help-block">{{ $errors->first('pes_cep') }}</p> @endif
                                </div>
                            </div>
                            <div class="form-group @if ($errors->has('pes_endereco')) has-error @endif">
                                <label for="pes_endereco" class="col-sm-3 control-label">Endereço*</label>

                                <div class="col-sm-9">
                                    @haspermission('seguranca.profile.edit')
                                        <input type="text" name="pes_endereco" value="{{ old('pes_endereco') }}" class="form-control" >
                                    @else
                                        <input type="text" name="pes_endereco" value="{{ old('pes_endereco') }}" class="form-control" >
                                    @endif
                                    @if ($errors->has('pes_endereco')) <p
                                            class="help-block">{{ $errors->first('pes_endereco') }}</p> @endif
                                </div>
                            </div>
                            <div class="form-group @if ($errors->has('pes_complemento')) has-error @endif">
                                <label for="pes_complemento" class="col-sm-3 control-label">Complemento</label>

                                <div class="col-sm-9">
                                    @haspermission('seguranca.profile.edit')
                                        <input type="text" name="pes_complemento" value="{{ old('pes_complemento') }}" class="form-control" >
                                    @else
                                        <input type="text" name="pes_complemento" value="{{ old('pes_complemento') }}" class="form-control" >
                                    @endif
                                    @if ($errors->has('pes_complemento')) <p
                                            class="help-block">{{ $errors->first('pes_complemento') }}</p> @endif
                                </div>
                            </div>
                            <div class="form-group @if ($errors->has('pes_numero')) has-error @endif">
                                <label for="pes_numero" class="col-sm-3 control-label">Numero*</label>

                                <div class="col-sm-9">
                                    @haspermission('seguranca.profile.edit')
                                        <input type="text" name="pes_numero" value="{{ old('pes_numero') }}" class="form-control" >
                                    @else
                                        <input type="text" name="pes_numero" value="{{ old('pes_numero') }}" class="form-control" >
                                    @endif
                                    @if ($errors->has('pes_numero')) <p
                                            class="help-block">{{ $errors->first('pes_numero') }}</p> @endif
                                </div>
                            </div>
                            <div class="form-group @if ($errors->has('pes_bairro')) has-error @endif">
                                <label for="pes_bairro" class="col-sm-3 control-label">Bairro*</label>

                                <div class="col-sm-9">
                                    @haspermission('seguranca.profile.edit')
                                        <input type="text" name="pes_bairro" value="{{ old('pes_bairro') }}" class="form-control" >
                                    @else
                                        <input type="text" name="pes_bairro" value="{{ old('pes_bairro') }}" class="form-control" >
                                    @endif
                                    @if ($errors->has('pes_bairro')) <p
                                            class="help-block">{{ $errors->first('pes_bairro') }}</p> @endif
                                </div>
                            </div>
                            <div class="form-group @if ($errors->has('pes_cidade')) has-error @endif">
                                <label for="pes_cidade" class="col-sm-3 control-label">Cidade*</label>

                                <div class="col-sm-9">
                                    @haspermission('seguranca.profile.edit')
                                        <input type="text" name="pes_cidade" value="{{ old('pes_cidade') }}" class="form-control" >
                                    @else
                                        <input type="text" name="pes_cidade" value="{{ old('pes_cidade') }}" class="form-control" >
                                    @endif
                                    @if ($errors->has('pes_cidade')) <p
                                            class="help-block">{{ $errors->first('pes_cidade') }}</p> @endif
                                </div>
                            </div>
                            <div class="form-group @if ($errors->has('pes_estado')) has-error @endif">
                                <label for="pes_estado" class="col-sm-3 control-label">Estado*</label>
                                <div class="col-sm-9">
                                    @haspermission('seguranca.profile.edit')
                                    <select name="pes_estado" class="form-control" style="width: 100%;">
    <option value="">Selecione um estado</option>
    <option value="AC" {{ old('pes_estado') == 'AC' ? 'selected' : '' }}>Acre</option>
    <option value="AL" {{ old('pes_estado') == 'AL' ? 'selected' : '' }}>Alagoas</option>
    <option value="AP" {{ old('pes_estado') == 'AP' ? 'selected' : '' }}>Amapá</option>
    <option value="AM" {{ old('pes_estado') == 'AM' ? 'selected' : '' }}>Amazonas</option>
    <option value="BA" {{ old('pes_estado') == 'BA' ? 'selected' : '' }}>Bahia</option>
    <option value="CE" {{ old('pes_estado') == 'CE' ? 'selected' : '' }}>Ceará</option>
    <option value="DF" {{ old('pes_estado') == 'DF' ? 'selected' : '' }}>Distrito Federal</option>
    <option value="ES" {{ old('pes_estado') == 'ES' ? 'selected' : '' }}>Espirito Santo</option>
    <option value="GO" {{ old('pes_estado') == 'GO' ? 'selected' : '' }}>Goiás</option>
    <option value="MA" {{ old('pes_estado') == 'MA' ? 'selected' : '' }}>Maranhão</option>
    <option value="MT" {{ old('pes_estado') == 'MT' ? 'selected' : '' }}>Mato Grosso</option>
    <option value="MS" {{ old('pes_estado') == 'MS' ? 'selected' : '' }}>Mato Grosso do Sul</option>
    <option value="MG" {{ old('pes_estado') == 'MG' ? 'selected' : '' }}>Minas Gerais</option>
    <option value="PA" {{ old('pes_estado') == 'PA' ? 'selected' : '' }}>Pará</option>
    <option value="PB" {{ old('pes_estado') == 'PB' ? 'selected' : '' }}>Paraiba</option>
    <option value="PR" {{ old('pes_estado') == 'PR' ? 'selected' : '' }}>Paraná</option>
    <option value="PE" {{ old('pes_estado') == 'PE' ? 'selected' : '' }}>Pernambuco</option>
    <option value="PI" {{ old('pes_estado') == 'PI' ? 'selected' : '' }}>Piauí</option>
    <option value="RJ" {{ old('pes_estado') == 'RJ' ? 'selected' : '' }}>Rio de Janeiro</option>
    <option value="RN" {{ old('pes_estado') == 'RN' ? 'selected' : '' }}>Rio Grande do Norte</option>
    <option value="RS" {{ old('pes_estado') == 'RS' ? 'selected' : '' }}>Rio Grande do Sul</option>
    <option value="RO" {{ old('pes_estado') == 'RO' ? 'selected' : '' }}>Rondônia</option>
    <option value="RR" {{ old('pes_estado') == 'RR' ? 'selected' : '' }}>Roraima</option>
    <option value="SC" {{ old('pes_estado') == 'SC' ? 'selected' : '' }}>Santa Catarina</option>
    <option value="SP" {{ old('pes_estado') == 'SP' ? 'selected' : '' }}>São Paulo</option>
    <option value="SE" {{ old('pes_estado') == 'SE' ? 'selected' : '' }}>Sergipe</option>
    <option value="TO" {{ old('pes_estado') == 'TO' ? 'selected' : '' }}>Tocantis</option>
</select>
                                    @else
                                        <select name="pes_estado" class="form-control" style="width: 100%;">
    <option value="">Selecione um estado</option>
    <option value="AC" {{ old('pes_estado') == 'AC' ? 'selected' : '' }}>Acre</option>
    <option value="AL" {{ old('pes_estado') == 'AL' ? 'selected' : '' }}>Alagoas</option>
    <option value="AP" {{ old('pes_estado') == 'AP' ? 'selected' : '' }}>Amapá</option>
    <option value="AM" {{ old('pes_estado') == 'AM' ? 'selected' : '' }}>Amazonas</option>
    <option value="BA" {{ old('pes_estado') == 'BA' ? 'selected' : '' }}>Bahia</option>
    <option value="CE" {{ old('pes_estado') == 'CE' ? 'selected' : '' }}>Ceará</option>
    <option value="DF" {{ old('pes_estado') == 'DF' ? 'selected' : '' }}>Distrito Federal</option>
    <option value="ES" {{ old('pes_estado') == 'ES' ? 'selected' : '' }}>Espirito Santo</option>
    <option value="GO" {{ old('pes_estado') == 'GO' ? 'selected' : '' }}>Goiás</option>
    <option value="MA" {{ old('pes_estado') == 'MA' ? 'selected' : '' }}>Maranhão</option>
    <option value="MT" {{ old('pes_estado') == 'MT' ? 'selected' : '' }}>Mato Grosso</option>
    <option value="MS" {{ old('pes_estado') == 'MS' ? 'selected' : '' }}>Mato Grosso do Sul</option>
    <option value="MG" {{ old('pes_estado') == 'MG' ? 'selected' : '' }}>Minas Gerais</option>
    <option value="PA" {{ old('pes_estado') == 'PA' ? 'selected' : '' }}>Pará</option>
    <option value="PB" {{ old('pes_estado') == 'PB' ? 'selected' : '' }}>Paraiba</option>
    <option value="PR" {{ old('pes_estado') == 'PR' ? 'selected' : '' }}>Paraná</option>
    <option value="PE" {{ old('pes_estado') == 'PE' ? 'selected' : '' }}>Pernambuco</option>
    <option value="PI" {{ old('pes_estado') == 'PI' ? 'selected' : '' }}>Piauí</option>
    <option value="RJ" {{ old('pes_estado') == 'RJ' ? 'selected' : '' }}>Rio de Janeiro</option>
    <option value="RN" {{ old('pes_estado') == 'RN' ? 'selected' : '' }}>Rio Grande do Norte</option>
    <option value="RS" {{ old('pes_estado') == 'RS' ? 'selected' : '' }}>Rio Grande do Sul</option>
    <option value="RO" {{ old('pes_estado') == 'RO' ? 'selected' : '' }}>Rondônia</option>
    <option value="RR" {{ old('pes_estado') == 'RR' ? 'selected' : '' }}>Roraima</option>
    <option value="SC" {{ old('pes_estado') == 'SC' ? 'selected' : '' }}>Santa Catarina</option>
    <option value="SP" {{ old('pes_estado') == 'SP' ? 'selected' : '' }}>São Paulo</option>
    <option value="SE" {{ old('pes_estado') == 'SE' ? 'selected' : '' }}>Sergipe</option>
    <option value="TO" {{ old('pes_estado') == 'TO' ? 'selected' : '' }}>Tocantis</option>
</select>
                                    @endif

                                    @if ($errors->has('pes_estado')) <p
                                            class="help-block">{{ $errors->first('pes_estado') }}</p> @endif
                                </div>
                            </div>

                            @haspermission('seguranca.profile.edit')
                            <div class="form-group">
                                <div class="col-sm-offset-2 col-sm-10">
                                    <button type="submit" class="btn btn-primary">Atualizar Endereço</button>
                                </div>
                            </div>
                            @endhaspermission


                            @haspermission('seguranca.profile.edit')

                            @else

                            @endif


                            </form>
                        </div>
                        <!-- /.tab-pane -->



                        <div class="tab-pane @if ($errors->has('usr_senha') or $errors->has('usr_senha_nova') or $errors->has('usr_senha_nova_confirmation')) active @endif" id="senha">
                            <form action="{{ route('seguranca.profile.updatepassword') }}" method="POST" id="form" role="form">
    @csrf
    @method('PUT')
    {{-- Form model: $usuario->pessoa - inputs devem usar old('campo', $usuario->pessoa->campo) --}}
                            <div class="form-group @if ($errors->has('usr_senha')) has-error @endif">
                                <label for="usr_senha" class="col-sm-3 control-label">Senha atual*</label>

                                <div class="col-sm-9">
                                    <input type="password" name="usr_senha" >
                                    @if ($errors->has('usr_senha')) <p
                                            class="help-block">{{ $errors->first('usr_senha') }}</p> @endif
                                </div>
                            </div>
                            <div class="form-group @if ($errors->has('usr_senha_nova')) has-error @endif">
                                <label for="usr_senha_nova" class="col-sm-3 control-label">Nova senha*</label>

                                <div class="col-sm-9">
                                    <input type="password" name="usr_senha_nova" >
                                    @if ($errors->has('usr_senha_nova')) <p
                                            class="help-block">{{ $errors->first('usr_senha_nova') }}</p> @endif
                                </div>
                            </div>
                            <div class="form-group @if ($errors->has('usr_senha_nova_confirmation')) has-error @endif">
                                <label for="usr_senha_nova_confirmation" class="col-sm-3 control-label">Repita a nova senha*</label>

                                <div class="col-sm-9">
                                    <input type="password" name="usr_senha_nova_confirmation" >
                                    @if ($errors->has('usr_senha_nova_confirmation')) <p
                                            class="help-block">{{ $errors->first('usr_senha_nova_confirmation') }}</p> @endif
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="col-sm-offset-2 col-sm-10">
                                    <button type="submit" class="btn btn-danger">Alterar senha</button>
                                </div>
                            </div>
                            </form>
                        </div>

                        <div class="tab-pane" id="foto">
                            <form action="{{ route('seguranca.profile.picture') }}" method="POST" id="form" role="form" enctype="multipart/form-data">
    @csrf
    @method('PUT')
    {{-- Form model: $usuario->pessoa - inputs devem usar old('campo', $usuario->pessoa->campo) --}}

                            <div class="form-group @if ($errors->has('usr_picture')) has-error @endif">
                                <div class="col-sm-9">
                                    <input type="file" name="usr_picture" class="form-control file" >
                                    @if ($errors->has('usr_picture')) <p class="help-block">{{ $errors->first('usr_picture') }}</p> @endif
                                </div>
                            </div>

                            <div class="form-group">
                                <div class="col-sm-offset-2 col-sm-10">
                                    <button type="submit" class="btn btn-danger">Alterar foto</button>
                                </div>
                            </div>
                            </form>
                        </div>
                        <!-- /.tab-pane -->
                    </div>
                    <!-- /.tab-content -->
                </div>
                <!-- /.nav-tabs-custom -->
            </div>
            <!-- /.col -->
        </div>
        <!-- /.row -->
    </div>
    <!-- /.container -->
@stop

@section('scripts')
    <script src="{{ asset('/js/plugins/input-mask/inputmask.js') }}"></script>
    <script src="{{ asset('/js/plugins/input-mask/inputmask.date.extensions.js') }}"></script>
    <script src="{{ asset('/js/plugins/input-mask/jquery.inputmask.js') }}"></script>
    <script src="{{ asset('/js/plugins/bootstrap-datepicker.js') }}"></script>
    <script src="{{asset('/js/plugins/bootstrap-datepicker.pt-BR.js')}}"></script>
    <script src="{{asset('/js/plugins/select2.js')}}" type="text/javascript"></script>

    <script type="text/javascript">

        $(function () {
            $("select").select2();

            $('.datepicker').datepicker({
                format: "dd/mm/yyyy",
                language: 'pt-BR',
                autoclose: true
            });

            $(document).find('span .select2').css('width', '100%');

            $('#pes_telefone').inputmask({"mask": "(99) 99999-9999", "removeMaskOnSubmit": true});
            $('#pes_cep').inputmask({"mask": "99999-999", "removeMaskOnSubmit": true});

            $("#pes_cep").focusout(function (e) {

                function limpaFormCep() {

                    $("#pes_cidade").val("");
                    $("#pes_estado").val("");
                    $("#pes_bairro").val("");
                    $("#pes_endereco").val("");
                }

                var str = e.target.value;

                var cep = str.replace(/\D/g, '');

                if (str != "") {
                    //Expressão regular para validar o CEP.
                    var validacep = /^[0-9]{8}$/;

                    if (validacep.test(cep)) {

                        $("#pes_cidade").val("Buscando...");
                        $("#pes_estado").val("Buscando...");
                        $("#pes_bairro").val("Buscando...");
                        $("#pes_endereco").val("Buscando...");

                        $.harpia.httpget('https://viacep.com.br/ws/' + cep + '/json/').done(function (data) {
                            if (!data.erro) {
                                $("#pes_cidade").val(data.localidade);
                                $("#pes_estado").val(data.uf).change();
                                $("#pes_bairro").val(data.bairro);
                                $("#pes_endereco").val(data.logradouro);
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
@stop