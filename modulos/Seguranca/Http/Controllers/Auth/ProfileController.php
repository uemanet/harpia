<?php

namespace Modulos\Seguranca\Http\Controllers\Auth;

use Illuminate\Contracts\Auth\Guard;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Modulos\Core\Http\Controller\BaseController;
use Modulos\Geral\Repositories\PessoaRepository;
use Modulos\Seguranca\Http\Requests\ProfileRequest;
use Symfony\Component\HttpFoundation\Request;

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
        $pessoa = $this->auth->user()->pessoa;
        ;

        return view('Seguranca::auth.profile.index', compact('pessoa'));
    }

    public function putEdit($idUsuario, ProfileRequest $request)
    {
        try {
            $pes_id = $this->auth->user()->pessoa->pes_id;

            if ($idUsuario != $pes_id) {
                flash()->error('Acesso ilegal.');

                return redirect('/');
            }

            $data = $request->only($this->pessoaRepository->getFillableModelFields());

            if (!$this->pessoaRepository->update($data, $pes_id, 'pes_id')) {
                flash()->error('Erro ao tentar salvar.');

                return redirect()->back()->withInput($request->all());
            }

            flash()->success('Perfil atualizado com sucesso.');

            return redirect('/seguranca/profile');
        } catch (\Exception $e) {
            if (config('app.debug')) {
                throw $e;
            } else {
                flash()->success('Erro ao tentar salvar. Caso o problema persista, entre em contato com o suporte.');

                return redirect()->back();
            }
        }
    }

    public function postUpdatepassword(Request $request)
    {
        try {
            $validation = $this->validate($request, [
                'usr_senha' => 'required|min:6',
                'usr_senha_nova' => 'required|min:6|confirmed'
            ]);

            $usuario = $this->auth->user();

            if (!Hash::check($request->get('usr_senha'), $usuario->usr_senha)) {
                flash()->error('Senha atual não confere.');

                return redirect('/seguranca/profile');
            }

            $usuario->forceFill([
                'usr_senha' => bcrypt($request->get('usr_senha_nova')),
                'remember_token' => Str::random(60),
            ])->save();

            Auth::guard()->login($usuario);

            flash()->success('Senha atualizada com sucesso.');

            return redirect('/seguranca/profile');
        } catch (\Illuminate\Validation\ValidationException $e) {
            flash()->error('Não foi possível alterar a senha. Por favor, tente novamente.');

            return redirect('/seguranca/profile')->withErrors($e->validator);
        } catch (\Exception $e) {
            if (config('app.debug')) {
                throw $e;
            } else {
                flash()->success('Erro ao tentar salvar. Caso o problema persista, entre em contato com o suporte.');

                return redirect()->back();
            }
        }
    }
}
