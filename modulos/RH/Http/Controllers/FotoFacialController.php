<?php

namespace Modulos\RH\Http\Controllers;

use Exception;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Modulos\Core\Http\Controller\BaseController;
use Modulos\Geral\Repositories\AnexoRepository;
use Modulos\RH\Models\Colaborador;

class FotoFacialController extends BaseController
{
    protected $anexoRepository;

    public function __construct(AnexoRepository $anexoRepository)
    {
        $this->anexoRepository = $anexoRepository;
    }

    public function create()
    {
        return view('RH::portal-foto.login');
    }

    public function authenticate(Request $request)
    {
        try {
            $request->validate([
                'cpf' => 'required|string',
                'data_nascimento' => 'required|string|date_format:d/m/Y'
            ]);

            // Limpa o CPF (garantia caso o input envie formatação futuramente)
            $cpf = preg_replace('/[^0-9]/', '', $request->cpf);

            // Converte a data do formato do datepicker (d/m/Y) para o formato do banco (Y-m-d)
            $dataNascimento = Carbon::createFromFormat('d/m/Y', $request->data_nascimento)->format('Y-m-d');

            // Busca o colaborador validando CPF, Data de Nascimento e Status Ativo
            $colaborador = Colaborador::where('col_status', 'ativo')
                ->whereHas('pessoa', function ($query) use ($cpf, $dataNascimento) {
                    $query->where('pes_nascimento', $dataNascimento)
                        ->whereHas('documentos', function ($qDoc) use ($cpf) {
                            $qDoc->where('doc_conteudo', $cpf)
                                ->where('doc_tpd_id', 2);
                        });
                })->first();

            if (!$colaborador) {
                flash()->error('Dados não encontrados ou colaborador inativo.');
                return redirect()->back()->withInput();
            }

            // Cria a sessão temporária
            session(['portal_col_id' => $colaborador->col_id]);

            return redirect()->route('portal.foto.edit');

        } catch (\Illuminate\Validation\ValidationException $e) {
            flash()->error('Preencha os dados corretamente.');
            return redirect()->back()->withInput();
        } catch (Exception $e) {
            if (config('app.debug')) {
                throw $e;
            }
            flash()->error('Erro ao tentar validar o acesso. Caso o problema persista, entre em contato com o suporte.');
            return redirect()->back()->withInput();
        }
    }

    public function edit()
    {
        if (!session()->has('portal_col_id')) {
            flash()->error('Sessão expirada. Informe seus dados novamente.');
            return redirect()->route('portal.foto.create');
        }

        $colaborador = Colaborador::find(session('portal_col_id'));

        return view('RH::portal-foto.upload', compact('colaborador'));
    }

    public function store(Request $request)
    {
        if (!session()->has('portal_col_id')) {
            flash()->error('Sessão expirada. Informe seus dados novamente.');
            return redirect()->route('portal.foto.create');
        }

        try {
            $request->validate([
                'col_foto_facial' => 'required|image|mimes:jpeg,png,jpg|max:2048'
            ]);

            $colaborador = Colaborador::find(session('portal_col_id'));

            DB::beginTransaction();

            if ($colaborador->col_foto_anx_id) {
                $anexo = $this->anexoRepository->atualizarAnexo($colaborador->col_foto_anx_id, $request->file('col_foto_facial'));
                $anexoId = (int) $colaborador->col_foto_anx_id;
            } else {
                $anexo = $this->anexoRepository->salvarAnexo($request->file('col_foto_facial'));
                $anexoId = $anexo->anx_id;
            }

            if (!$anexo || is_array($anexo)) {
                throw new Exception('Erro ao processar o arquivo no repositório.');
            }

            $colaborador->update(['col_foto_anx_id' => $anexoId]);

            DB::commit();
            session()->forget('portal_col_id'); // Revoga o acesso

            flash()->success('Foto facial salva com sucesso!');

            // Aqui você pode redirecionar para a view de sucesso ou para o create com flash
            return redirect()->route('portal.foto.create');

        } catch (\Illuminate\Validation\ValidationException $e) {
            DB::rollback();
            flash()->error('Arquivo inválido. Envie uma imagem JPG ou PNG de até 2MB.');
            return redirect()->back();
        } catch (Exception $e) {
            DB::rollback();
            if (config('app.debug')) {
                throw $e;
            }
            flash()->error('Erro ao salvar a foto. Caso o problema persista, entre em contato com o suporte.');
            return redirect()->back();
        }
    }

    public function getFotoAtual()
    {
        if (!session()->has('portal_col_id')) {
            abort(403, 'Acesso negado.');
        }

        $colaborador = Colaborador::find(session('portal_col_id'));
        $avatarPadrao = public_path('/img/avatar.png');

        if (!$colaborador || !$colaborador->col_foto_anx_id) {
            return response()->file($avatarPadrao);
        }

        $caminho = $this->anexoRepository->obterCaminhoAnexo($colaborador->col_foto_anx_id);

        if ($caminho === 'error_non_existent' || !file_exists($caminho)) {
            return response()->file($avatarPadrao);
        }

        $mime = mime_content_type($caminho) ?: 'image/jpeg';

        return response()->file($caminho, [
            'Content-Type' => $mime,
            'Cache-Control' => 'no-store, no-cache, must-revalidate, max-age=0',
        ]);
    }
}