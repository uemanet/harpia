<?php

namespace Modulos\Seguranca\Http\Controllers\Auth;

use Illuminate\Contracts\Auth\Guard;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Response;
use Modulos\Core\Http\Controller\BaseController;
use Modulos\Geral\Repositories\AnexoRepository;
use Modulos\Geral\Repositories\PessoaRepository;
use Modulos\Seguranca\Http\Requests\ProfileRequest;
use Modulos\Seguranca\Http\Requests\UpdateProfilePictureRequest;
use Modulos\Seguranca\Repositories\UsuarioRepository;
use Validator;

class ProfileController extends BaseController
{
    protected $auth;
    protected $pessoaRepository;
    protected $anexoRepository;
    protected $usuarioRepository;

    public function __construct(Guard $auth,
                                PessoaRepository $pessoaRepository,
                                AnexoRepository $anexoRepository,
                                UsuarioRepository $usuarioRepository
    )
    {
        $this->auth = $auth;
        $this->pessoaRepository = $pessoaRepository;
        $this->anexoRepository = $anexoRepository;
        $this->usuarioRepository = $usuarioRepository;
    }

    public function getIndex()
    {
        if (Auth::check()) {
            $usuario = $this->auth->user();

            return view('Seguranca::auth.profile.index', compact('usuario'));
        }

        flash()->error('Voçê não tem permissão para acessar este recurso!');
        return redirect()->back();
    }

    public function putEdit(ProfileRequest $request)
    {
        if (!$this->auth->check()) {
            flash()->error('Você não tem permissão para acessar este recurso!');
            return redirect()->back();
        }

        try {
            $pes_id = $this->auth->user()->pessoa->pes_id;

            $data = $request->only(
                'pes_nome',
                'pes_email',
                'pes_telefone',
                'pes_sexo',
                'pes_nascimento',
                'pes_estado_civil',
                'pes_mae',
                'pes_pai',
                'pes_naturalidade',
                'pes_nacionalidade',
                'pes_raca',
                'pes_necessidade_especial',
                'pes_estrangeiro'
            );

            if ($request->getMethod() == 'POST') {
                $data = $request->only(
                    'pes_endereco',
                    'pes_numero',
                    'pes_cep',
                    'pes_bairro',
                    'pes_cidade',
                    'pes_estado'
                );
            }

            if (!$this->pessoaRepository->update($data, $pes_id, 'pes_id')) {
                flash()->error('Erro ao tentar salvar.');

                return redirect()->back()->withInput($request->all());
            }

            flash()->success('Perfil atualizado com sucesso.');

            return redirect()->route('index');
        } catch (\Exception $e) {
            if (config('app.debug')) {
                throw $e;
            } else {
                flash()->error('Erro ao tentar salvar. Caso o problema persista, entre em contato com o suporte.');

                return redirect()->back();
            }
        }
    }

    public function postUpdatepassword(Request $request)
    {
        if (!$this->auth->check()) {
            flash()->error('Você não tem permissão para acessar este recurso!');
            return redirect()->back();
        }

        // Faz a validação dos dados
        $validator = Validator::make($request->all(), [
            'usr_senha' => 'required|min:6',
            'usr_senha_nova' => 'required|min:6|confirmed'
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator);
        }

        try {
            $usuario = $this->auth->user();

            if (!Hash::check($request->get('usr_senha'), $usuario->usr_senha)) {
                flash()->error('Senha atual não confere.');

                return redirect()->back();
            }

            $usuario->forceFill([
                'usr_senha' => bcrypt($request->get('usr_senha_nova'))
            ])->save();

            Auth::guard()->login($usuario);

            flash()->success('Senha atualizada com sucesso.');

            return redirect()->route('index');
        } catch (\Illuminate\Validation\ValidationException $e) {
            flash()->error('Não foi possível alterar a senha. Por favor, tente novamente.');

            return redirect()->back()->withErrors($e->validator);
        } catch (\Exception $e) {
            if (config('app.debug')) {
                throw $e;
            } else {
                flash()->error('Erro ao tentar salvar. Caso o problema persista, entre em contato com o suporte.');

                return redirect()->back();
            }
        }
    }

    public function getProfilePicture($pictureId)
    {

        if (!(int)$pictureId) {
            return Response::download(public_path('/img/avatar.png'));
        }

        $anexo = $this->anexoRepository->recuperarAnexo($pictureId);

        if ($anexo == 'error_non_existent') {
            return Response::download(public_path('/img/avatar.png'));
        }

        return $anexo;
    }


    public function putPicture(UpdateProfilePictureRequest $request)
    {
        try {
            $anexoDocumento = $request->file('usr_picture');

            $oldPicture = $this->auth->user()->usr_profile_picture_id;


            $anexo = $this->anexoRepository->salvarAnexo($anexoDocumento);

            if ($anexo['type'] == 'error_exists') {
                flash()->error($anexo['message']);
                return redirect()->back()->withInput($request->all());
            }
            if (!$anexo) {
                flash()->error('ocorreu um problema ao salvar o arquivo');
                return redirect()->back()->withInput($request->all());
            }
            $dados['usr_profile_picture_id'] = $anexo->anx_id;

            if (!$this->usuarioRepository->update($dados, $this->auth->user()->usr_id, 'usr_id')) {
                DB::rollBack();
                flash()->error('Erro ao tentar atualizar');
                return redirect()->back()->withInput($request->all());
            }

            if ($oldPicture) {
                $this->anexoRepository->deletarAnexo($this->auth->user()->usr_profile_picture_id);
            }

            DB::commit();

            flash()->success('Documento atualizado com sucesso.');

            return redirect()->back();

        } catch (\Illuminate\Validation\ValidationException $e) {
            flash()->error('Não foi possível alterar a senha. Por favor, tente novamente.');

            return redirect()->back()->withErrors($e->validator);
        } catch (\Exception $e) {
            if (config('app.debug')) {
                throw $e;
            } else {
                flash()->error('Erro ao tentar salvar. Caso o problema persista, entre em contato com o suporte.');

                return redirect()->back();
            }
        }
    }
}
