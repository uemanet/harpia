<?php

namespace Modulos\Seguranca\Http\Controllers\Auth;

use Illuminate\Contracts\Auth\Guard;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Modulos\Core\Http\Controller\BaseController;
use Modulos\Geral\Repositories\PessoaRepository;
use Modulos\Seguranca\Http\Requests\ProfileRequest;
use Validator;

class ProfileController extends BaseController
{
    protected $auth;
    protected $pessoaRepository;

    public function __construct(Guard $auth, PessoaRepository $pessoaRepository)
    {
        $this->auth = $auth;
        $this->pessoaRepository = $pessoaRepository;
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
}
