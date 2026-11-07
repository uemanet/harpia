@extends('layouts.clean')

@section('title')
    Módulo de Segurança
@stop

@section('subtitle')
    Perfil do usuário
@stop

@section('content')

    <div class="content-wrapper p-5">
        <section class="content">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-md-3">
                        <!-- Profile Image -->
                        <div class="card card-primary card-outline">
                            <div class="card-body box-profile">
                                <div class="text-center">
                                    <img class="profile-user-img img-circle"
                                         style="border-radius: 50%; max-height: 160px; max-width: 160px"
                                         src="{{ route('seguranca.profile.profile-picture', \Illuminate\Support\Facades\Auth::user()->usr_profile_picture_id ?? 0) }}"
                                         alt="User profile picture">
                                </div>

                                <h3 class="profile-username text-center">{{ $usuario->pessoa->pes_nome }}</h3>

                                <p class="text-muted text-center">{{ $usuario->pessoa->pes_email }}</p>
                            </div>
                            <!-- /.card-body -->
                        </div>
                        <!-- /.card -->
                    </div>
                    <!-- /.col -->
                    <div class="col-md-9">
                        <div class="card card-primary card-outline card-outline-tabs">
                            <div class="card-header p-0 border-bottom-0">
                                <ul class="nav nav-tabs" role="tablist">
                                    <li class="nav-item">
                                        <a class="nav-link @if (!$errors->has('usr_senha') and !$errors->has('usr_senha_nova') and !$errors->has('usr_senha_nova_confirmation')) active @endif" id="dados-tab" data-bs-toggle="tab" href="#dados" role="tab" aria-controls="dados">Dados pessoais</a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link" id="endereco-tab" data-bs-toggle="tab" href="#endereco" role="tab" aria-controls="endereco">Endereço</a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link @if ($errors->has('usr_senha') or $errors->has('usr_senha_nova') or $errors->has('usr_senha_nova_confirmation')) active @endif" id="senha-tab" data-bs-toggle="tab" href="#senha" role="tab" aria-controls="senha">Alterar Senha</a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link" id="foto-tab" data-bs-toggle="tab" href="#foto" role="tab" aria-controls="foto">Alterar Foto</a>
                                    </li>
                                </ul>
                            </div>
                            <!-- /.card-header -->
                            <div class="card-body">
                                <div class="tab-content">

                                    <div class="tab-pane fade @if (!$errors->has('usr_senha') and !$errors->has('usr_senha_nova') and !$errors->has('usr_senha_nova_confirmation')) show active @endif" id="dados" role="tabpanel" aria-labelledby="dados-tab">
                                        <form action="{{ route('seguranca.profile.edit') }}" method="POST" id="form" role="form">
                                            @csrf
                                            @method('PUT')
                                            {{-- Form model: $usuario->pessoa - inputs devem usar old('campo', $usuario->pessoa->campo) --}}
                                            <div class="mb-3 @if ($errors->has('pes_nome')) has-error @endif">
                                                <label for="pes_nome" class="col-sm-3 control-label">Nome completo*</label>

                                                <div class="col-sm-9">
                                                    <input type="text" name="pes_nome" value="{{ old('pes_nome') }}" class="form-control" >
                                                    @if ($errors->has('pes_nome')) <p
                                                            class="help-block">{{ $errors->first('pes_nome') }}</p> @endif
                                                </div>
                                            </div>
                                            <div class="mb-3 @if ($errors->has('pes_email')) has-error @endif">
                                                <label for="pes_email" class="col-sm-3 control-label">Email*</label>
                                                <div class="col-sm-9">
                                                    <input type="email" name="pes_email" value="{{ old('pes_email') }}" class="form-control" >
                                                    @if ($errors->has('pes_email')) <p
                                                            class="help-block">{{ $errors->first('pes_email') }}</p> @endif
                                                </div>
                                            </div>
                                            <div class="mb-3 @if ($errors->has('pes_telefone')) has-error @endif">
                                                <label for="pes_telefone" class="col-sm-3 control-label">Telefone*</label>

                                                <div class="col-sm-9">
                                                    <input type="text" name="pes_telefone" value="{{ old('pes_telefone') }}" class="form-control" >
                                                    @if ($errors->has('pes_telefone')) <p
                                                            class="help-block">{{ $errors->first('pes_telefone') }}</p> @endif
                                                </div>
                                            </div>
                                            <div class="mb-3 @if ($errors->has('pes_sexo')) has-error @endif">
                                                <label for="pes_sexo" class="col-sm-3 control-label">Sexo*</label>

                                                <div class="col-sm-9">
                                                    <select name="pes_sexo" class="form-control">
                                                        <option value="M" {{ old('pes_sexo') == 'M' ? 'selected' : '' }}>Masculino</option>
                                                        <option value="F" {{ old('pes_sexo') == 'F' ? 'selected' : '' }}>Feminino</option>
                                                    </select>
                                                    @if ($errors->has('pes_sexo')) <p
                                                            class="help-block">{{ $errors->first('pes_sexo') }}</p> @endif
                                                </div>
                                            </div>
                                            <div class="mb-3 @if ($errors->has('pes_nascimento')) has-error @endif">
                                                <label for="pes_nascimento" class="col-sm-3 control-label">Nascimento*</label>

                                                <div class="col-sm-9">
                                                    <input type="text" name="pes_nascimento" value="{{ old('pes_nascimento') }}" class="form-control datepicker" >
                                                    @if ($errors->has('pes_nascimento')) <p
                                                            class="help-block">{{ $errors->first('pes_nascimento') }}</p> @endif
                                                </div>
                                            </div>
                                            <div class="mb-3 @if ($errors->has('pes_estado_civil')) has-error @endif">
                                                <label for="pes_estado_civil" class="col-sm-3 control-label">Estado Civil*</label>

                                                <div class="col-sm-9">
                                                    <select name="pes_estado_civil" class="form-control">
                                                        <option value="uniao_estavel" {{ old('pes_estado_civil') == 'uniao_estavel' ? 'selected' : '' }}>União estável</option>
                                                        <option value="outro" {{ old('pes_estado_civil') == 'outro' ? 'selected' : '' }}>Outro</option>
                                                    </select>
                                                    @if ($errors->has('pes_estado_civil')) <p
                                                            class="help-block">{{ $errors->first('pes_estado_civil') }}</p> @endif
                                                </div>
                                            </div>
                                            <div class="mb-3 @if ($errors->has('pes_mae')) has-error @endif">
                                                <label for="pes_mae" class="col-sm-3 control-label">Nome da mãe*</label>

                                                <div class="col-sm-9">
                                                    <input type="text" name="pes_mae" value="{{ old('pes_mae') }}" class="form-control" >
                                                    @if ($errors->has('pes_mae')) <p
                                                            class="help-block">{{ $errors->first('pes_mae') }}</p> @endif
                                                </div>
                                            </div>
                                            <div class="mb-3 @if ($errors->has('pes_pai')) has-error @endif">
                                                <label for="pes_pai" class="col-sm-3 control-label">Nome do pai</label>

                                                <div class="col-sm-9">
                                                    <input type="text" name="pes_pai" value="{{ old('pes_pai') }}" class="form-control" >
                                                    @if ($errors->has('pes_pai')) <p
                                                            class="help-block">{{ $errors->first('pes_pai') }}</p> @endif
                                                </div>
                                            </div>
                                            <div class="mb-3 @if ($errors->has('pes_naturalidade')) has-error @endif">
                                                <label for="pes_naturalidade" class="col-sm-3 control-label">Naturalidade*</label>

                                                <div class="col-sm-9">
                                                    <input type="text" name="pes_naturalidade" value="{{ old('pes_naturalidade') }}" class="form-control" >
                                                    @if ($errors->has('pes_naturalidade')) <p
                                                            class="help-block">{{ $errors->first('pes_naturalidade') }}</p> @endif
                                                </div>
                                            </div>
                                            <div class="mb-3 @if ($errors->has('pes_nacionalidade')) has-error @endif">
                                                <label for="pes_nacionalidade" class="col-sm-3 control-label">Nacionalidade*</label>

                                                <div class="col-sm-9">
                                                    <input type="text" name="pes_nacionalidade" value="{{ old('pes_nacionalidade') }}" class="form-control" >
                                                    @if ($errors->has('pes_nacionalidade')) <p
                                                            class="help-block">{{ $errors->first('pes_nacionalidade') }}</p> @endif
                                                </div>
                                            </div>
                                            <div class="mb-3 @if ($errors->has('pes_raca')) has-error @endif">
                                                <label for="pes_raca" class="col-sm-3 control-label">Cor/Raça*</label>

                                                <div class="col-sm-9">
                                                    <select name="pes_raca" class="form-control">
                                                        <option value="branca" {{ old('pes_raca') == 'branca' ? 'selected' : '' }}>Branca</option>
                                                        <option value="preta" {{ old('pes_raca') == 'preta' ? 'selected' : '' }}>Preta</option>
                                                        <option value="parda" {{ old('pes_raca') == 'parda' ? 'selected' : '' }}>Parda</option>
                                                        <option value="amarela" {{ old('pes_raca') == 'amarela' ? 'selected' : '' }}>Amarela</option>
                                                        <option value="indigena" {{ old('pes_raca') == 'indigena' ? 'selected' : '' }}>Indígena</option>
                                                        <option value="outra" {{ old('pes_raca') == 'outra' ? 'selected' : '' }}>Outra</option>
                                                    </select>
                                                    @if ($errors->has('pes_raca')) <p
                                                            class="help-block">{{ $errors->first('pes_raca') }}</p> @endif
                                                </div>
                                            </div>
                                            <div class="mb-3 @if ($errors->has('pes_necessidade_especial')) has-error @endif">
                                                <label for="pes_necessidade_especial" class="col-sm-3 control-label">Necessidade especial?*</label>

                                                <div class="col-sm-9">
                                                    <select name="pes_necessidade_especial" class="form-control">
                                                        <option value="S" {{ old('pes_necessidade_especial') == 'S' ? 'selected' : '' }}>Sim</option>
                                                        <option value="N" {{ old('pes_necessidade_especial') == 'N' ? 'selected' : '' }}>Não</option>
                                                    </select>
                                                    @if ($errors->has('pes_necessidade_especial')) <p
                                                            class="help-block">{{ $errors->first('pes_necessidade_especial') }}</p> @endif
                                                </div>
                                            </div>

                                            <div class="mb-3 @if ($errors->has('pes_estrangeiro')) has-error @endif">
                                                <label for="pes_estrangeiro" class="col-sm-3 control-label">Estrangeiro?*</label>

                                                <div class="col-sm-9">
                                                    <select name="pes_estrangeiro" class="form-control">
                                                        <option value="0" {{ old('pes_estrangeiro') == '0' ? 'selected' : '' }}>Não</option>
                                                        <option value="1" {{ old('pes_estrangeiro') == '1' ? 'selected' : '' }}>Sim</option>
                                                    </select>

                                                    @if ($errors->has('pes_estrangeiro')) <p
                                                            class="help-block">{{ $errors->first('pes_estrangeiro') }}</p> @endif
                                                </div>
                                            </div>
                                            @haspermission('seguranca.profile.edit')
                                            <div class="mb-3">
                                                <div class="offset-sm-2 col-sm-10">
                                                    <button type="submit" class="btn btn-primary">Atualizar informações</button>
                                                </div>
                                            </div>
                                            @endhaspermission
                                        </form>
                                    </div>

                                    <div class="tab-pane fade" id="endereco" role="tabpanel" aria-labelledby="endereco-tab">
                                        <form action="{{ route('seguranca.profile.edit') }}" method="POST" id="form" role="form">
                                            @csrf
                                            {{-- Form model: $usuario->pessoa - inputs devem usar old('campo', $usuario->pessoa->campo) --}}
                                            <div class="mb-3 @if ($errors->has('pes_cep')) has-error @endif">
                                                <label for="pes_cep" class="col-sm-3 control-label">CEP*</label>

                                                <div class="col-sm-9">
                                                    @haspermission('seguranca.profile.edit')
                                                        <input type="text" name="pes_cep" value="{{ old('pes_cep') }}" class="form-control" >
                                                    @else
                                                        <input type="text" name="pes_cep" value="{{ old('pes_cep') }}" disabled class="form-control" >
                                                    @endif
                                                    @if ($errors->has('pes_cep')) <p
                                                            class="help-block">{{ $errors->first('pes_cep') }}</p> @endif
                                                </div>
                                            </div>
                                            <div class="mb-3 @if ($errors->has('pes_endereco')) has-error @endif">
                                                <label for="pes_endereco" class="col-sm-3 control-label">Endereço*</label>

                                                <div class="col-sm-9">
                                                    @haspermission('seguranca.profile.edit')
                                                        <input type="text" name="pes_endereco" value="{{ old('pes_endereco') }}" class="form-control" >
                                                    @else
                                                        <input type="text" name="pes_endereco" value="{{ old('pes_endereco') }}" disabled class="form-control" >
                                                    @endif
                                                    @if ($errors->has('pes_endereco')) <p
                                                            class="help-block">{{ $errors->first('pes_endereco') }}</p> @endif
                                                </div>
                                            </div>
                                            <div class="mb-3 @if ($errors->has('pes_complemento')) has-error @endif">
                                                <label for="pes_complemento" class="col-sm-3 control-label">Complemento</label>

                                                <div class="col-sm-9">
                                                    @haspermission('seguranca.profile.edit')
                                                        <input type="text" name="pes_complemento" value="{{ old('pes_complemento') }}" class="form-control" >
                                                    @else
                                                        <input type="text" name="pes_complemento" value="{{ old('pes_complemento') }}" disabled class="form-control" >
                                                    @endif
                                                    @if ($errors->has('pes_complemento')) <p
                                                            class="help-block">{{ $errors->first('pes_complemento') }}</p> @endif
                                                </div>
                                            </div>
                                            <div class="mb-3 @if ($errors->has('pes_numero')) has-error @endif">
                                                <label for="pes_numero" class="col-sm-3 control-label">Numero*</label>

                                                <div class="col-sm-9">
                                                    @haspermission('seguranca.profile.edit')
                                                        <input type="text" name="pes_numero" value="{{ old('pes_numero') }}" class="form-control" >
                                                    @else
                                                        <input type="text" name="pes_numero" value="{{ old('pes_numero') }}" disabled class="form-control" >
                                                    @endif
                                                    @if ($errors->has('pes_numero')) <p
                                                            class="help-block">{{ $errors->first('pes_numero') }}</p> @endif
                                                </div>
                                            </div>
                                            <div class="mb-3 @if ($errors->has('pes_bairro')) has-error @endif">
                                                <label for="pes_bairro" class="col-sm-3 control-label">Bairro*</label>

                                                <div class="col-sm-9">
                                                    @haspermission('seguranca.profile.edit')
                                                        <input type="text" name="pes_bairro" value="{{ old('pes_bairro') }}" class="form-control" >
                                                    @else
                                                        <input type="text" name="pes_bairro" value="{{ old('pes_bairro') }}" disabled class="form-control" >
                                                    @endif
                                                    @if ($errors->has('pes_bairro')) <p
                                                            class="help-block">{{ $errors->first('pes_bairro') }}</p> @endif
                                                </div>
                                            </div>
                                            <div class="mb-3 @if ($errors->has('pes_cidade')) has-error @endif">
                                                <label for="pes_cidade" class="col-sm-3 control-label">Cidade*</label>

                                                <div class="col-sm-9">
                                                    @haspermission('seguranca.profile.edit')
                                                        <input type="text" name="pes_cidade" value="{{ old('pes_cidade') }}" class="form-control" >
                                                    @else
                                                        <input type="text" name="pes_cidade" value="{{ old('pes_cidade') }}" disabled class="form-control" >
                                                    @endif
                                                    @if ($errors->has('pes_cidade')) <p
                                                            class="help-block">{{ $errors->first('pes_cidade') }}</p> @endif
                                                </div>
                                            </div>
                                            <div class="mb-3 @if ($errors->has('pes_estado')) has-error @endif">
                                                <label for="pes_estado" class="col-sm-3 control-label">Estado*</label>
                                                <div class="col-sm-9">
                                                    @php
                                                        $estados = [
                                                            'AC' => 'Acre', 'AL' => 'Alagoas', 'AP' => 'Amapá', 'AM' => 'Amazonas',
                                                            'BA' => 'Bahia', 'CE' => 'Ceará', 'DF' => 'Distrito Federal', 'ES' => 'Espirito Santo',
                                                            'GO' => 'Goiás', 'MA' => 'Maranhão', 'MT' => 'Mato Grosso', 'MS' => 'Mato Grosso do Sul',
                                                            'MG' => 'Minas Gerais', 'PA' => 'Pará', 'PB' => 'Paraiba', 'PR' => 'Paraná',
                                                            'PE' => 'Pernambuco', 'PI' => 'Piauí', 'RJ' => 'Rio de Janeiro', 'RN' => 'Rio Grande do Norte',
                                                            'RS' => 'Rio Grande do Sul', 'RO' => 'Rondônia', 'RR' => 'Roraima', 'SC' => 'Santa Catarina',
                                                            'SP' => 'São Paulo', 'SE' => 'Sergipe', 'TO' => 'Tocantis',
                                                        ];
                                                    @endphp
                                                    @haspermission('seguranca.profile.edit')
                                                        <select name="pes_estado" class="form-control" style="width: 100%;">
                                                            <option value="">Selecione um estado</option>
                                                            @foreach ($estados as $sigla => $nome)
                                                                <option value="{{ $sigla }}" {{ old('pes_estado') == $sigla ? 'selected' : '' }}>{{ $nome }}</option>
                                                            @endforeach
                                                        </select>
                                                    @else
                                                        <select name="pes_estado" class="form-control" style="width: 100%;" disabled>
                                                            <option value="">Selecione um estado</option>
                                                            @foreach ($estados as $sigla => $nome)
                                                                <option value="{{ $sigla }}" {{ old('pes_estado') == $sigla ? 'selected' : '' }}>{{ $nome }}</option>
                                                            @endforeach
                                                        </select>
                                                    @endif

                                                    @if ($errors->has('pes_estado')) <p
                                                            class="help-block">{{ $errors->first('pes_estado') }}</p> @endif
                                                </div>
                                            </div>

                                            @haspermission('seguranca.profile.edit')
                                            <div class="mb-3">
                                                <div class="offset-sm-2 col-sm-10">
                                                    <button type="submit" class="btn btn-primary">Atualizar Endereço</button>
                                                </div>
                                            </div>
                                            @endhaspermission
                                        </form>
                                    </div>
                                    <!-- /.tab-pane -->

                                    <div class="tab-pane fade @if ($errors->has('usr_senha') or $errors->has('usr_senha_nova') or $errors->has('usr_senha_nova_confirmation')) show active @endif" id="senha" role="tabpanel" aria-labelledby="senha-tab">
                                        <form action="{{ route('seguranca.profile.updatepassword') }}" method="POST" id="form" role="form">
                                            @csrf
                                            @method('PUT')
                                            {{-- Form model: $usuario->pessoa - inputs devem usar old('campo', $usuario->pessoa->campo) --}}
                                            <div class="mb-3 @if ($errors->has('usr_senha')) has-error @endif">
                                                <label for="usr_senha" class="col-sm-3 control-label">Senha atual*</label>

                                                <div class="col-sm-9">
                                                    <input type="password" name="usr_senha" class="form-control" >
                                                    @if ($errors->has('usr_senha')) <p
                                                            class="help-block">{{ $errors->first('usr_senha') }}</p> @endif
                                                </div>
                                            </div>
                                            <div class="mb-3 @if ($errors->has('usr_senha_nova')) has-error @endif">
                                                <label for="usr_senha_nova" class="col-sm-3 control-label">Nova senha*</label>

                                                <div class="col-sm-9">
                                                    <input type="password" name="usr_senha_nova" class="form-control" >
                                                    @if ($errors->has('usr_senha_nova')) <p
                                                            class="help-block">{{ $errors->first('usr_senha_nova') }}</p> @endif
                                                </div>
                                            </div>
                                            <div class="mb-3 @if ($errors->has('usr_senha_nova_confirmation')) has-error @endif">
                                                <label for="usr_senha_nova_confirmation" class="col-sm-3 control-label">Repita a nova senha*</label>

                                                <div class="col-sm-9">
                                                    <input type="password" name="usr_senha_nova_confirmation" class="form-control" >
                                                    @if ($errors->has('usr_senha_nova_confirmation')) <p
                                                            class="help-block">{{ $errors->first('usr_senha_nova_confirmation') }}</p> @endif
                                                </div>
                                            </div>
                                            <div class="mb-3">
                                                <div class="offset-sm-2 col-sm-10">
                                                    <button type="submit" class="btn btn-danger">Alterar senha</button>
                                                </div>
                                            </div>
                                        </form>
                                    </div>

                                    <div class="tab-pane fade" id="foto" role="tabpanel" aria-labelledby="foto-tab">
                                        <form action="{{ route('seguranca.profile.picture') }}" method="POST" id="form" role="form" enctype="multipart/form-data">
                                            @csrf
                                            @method('PUT')
                                            {{-- Form model: $usuario->pessoa - inputs devem usar old('campo', $usuario->pessoa->campo) --}}

                                            <div class="mb-3 @if ($errors->has('usr_picture')) has-error @endif">
                                                <div class="col-sm-9">
                                                    <input type="file" name="usr_picture" class="form-control file" >
                                                    @if ($errors->has('usr_picture')) <p class="help-block">{{ $errors->first('usr_picture') }}</p> @endif
                                                </div>
                                            </div>

                                            <div class="mb-3">
                                                <div class="offset-sm-2 col-sm-10">
                                                    <button type="submit" class="btn btn-danger">Alterar foto</button>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                    <!-- /.tab-pane -->
                                </div>
                                <!-- /.tab-content -->
                            </div>
                            <!-- /.card-body -->
                        </div>
                        <!-- /.card -->
                    </div>
                    <!-- /.col -->
                </div>
                <!-- /.row -->
            </div>
        </section>
    </div>
@stop
