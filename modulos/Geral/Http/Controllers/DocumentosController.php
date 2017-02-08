<?php

namespace Modulos\Geral\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Modulos\Geral\Repositories\AnexoRepository;
use Modulos\Seguranca\Providers\ActionButton\Facades\ActionButton;
use Modulos\Seguranca\Providers\ActionButton\TButton;
use Modulos\Core\Http\Controller\BaseController;
use Modulos\Geral\Http\Requests\DocumentoRequest;
use Illuminate\Http\Request;
use Modulos\Geral\Repositories\DocumentoRepository;
use Modulos\Geral\Repositories\TipoDocumentoRepository;
use Modulos\Geral\Repositories\PessoaRepository;

class DocumentosController extends BaseController
{
    protected $documentoRepository;
    protected $tipodocumentoRepository;
    protected $pessoaRepository;
    protected $anexoRepository;

    public function __construct(DocumentoRepository $documentoRepository,
                                TipoDocumentoRepository $tipodocumentoRepository,
                                PessoaRepository $pessoaRepository, AnexoRepository $anexoRepository)
    {
        $this->documentoRepository = $documentoRepository;
        $this->tipodocumentoRepository = $tipodocumentoRepository;
        $this->pessoaRepository = $pessoaRepository;
        $this->anexoRepository = $anexoRepository;
    }

    public function getCreate($pessoaId, Request $request)
    {
        $url = $request->session()->get('last_acad_route');
        $id = $request->session()->get('last_id');

        $pessoa = $this->pessoaRepository->find($pessoaId);

        if (!$pessoa) {
            flash()->error('Pessoa não existe');
            return redirect()->back();
        }

        $tiposdocumentos = $this->tipodocumentoRepository->listsTiposDocumentosWithoutPessoa($pessoaId);

        return view('Geral::documentos.create', compact('pessoa', 'tiposdocumentos'));
    }

    public function getDocumentoAnexo($documentoId)
    {
        $documento = $this->documentoRepository->find($documentoId);

        if (!$documento) {
            flash()->error('Documento não existe.');
            return redirect()->back();
        }

        $anexo =  $this->anexoRepository->recuperarAnexo($documento->doc_anx_documento);

        if($anexo == 'error_non_existent'){
            flash()->error('anexo não existe']);
            return redirect()->back();
        }

        return $anexo;
    }

    public function postCreate(DocumentoRequest $request)
    {
        $url = $request->session()->get('last_acad_route');
        $id = $request->session()->get('last_id');

        try {
            DB::beginTransaction();

            $dados = $request->all();

            if ($request->file('doc_file') != null) {
                $anexoDocumento = $request->file('doc_file');
                $anexoCriado = $this->anexoRepository->salvarAnexo($anexoDocumento);

                if ($anexoCriado['type'] == 'error_exists') {
                    flash()->error($anexoCriado['message']);
                    return redirect()->back()->withInput($request->all());
                }

                if (!$anexoCriado) {
                    flash()->error('ocorreu um problema ao salvar o arquivo');
                    return redirect()->back()->withInput($request->all());
                }

                $dados['doc_anx_documento'] = $anexoCriado->anx_id;
            }

            unset($dados['doc_file']);

            $tipodocumento = $this->documentoRepository->verifyTipoExists($request->input('doc_tpd_id'), $request->input('doc_pes_id'));

            if (!$tipodocumento) {
                $errors = array('doc_tpd_id' => 'Essa pessoa já tem esse documento cadastrado');
                return redirect()->back()->withInput($request->all())->withErrors($errors);
            }

            $documento = $this->documentoRepository->create($dados);

            if (!$documento) {
                flash()->error('Erro ao tentar salvar.');
                return redirect()->back()->withInput($request->all());
            }

            DB::commit();


            flash()->success('Documento criado com sucesso.');
            return redirect()->route($url, ['id' => $id]);
        } catch (\Exception $e) {
            if (config('app.debug')) {
                throw $e;
            }

            flash()->error('Erro ao tentar atualizar. Caso o problema persista, entre em contato com o suporte.');
            return redirect()->back();
        }
    }

    public function getEdit($documentoId, Request $request)
    {
        $url = $request->session()->get('last_acad_route');
        $id = $request->session()->get('last_id');

        $documento = $this->documentoRepository->find($documentoId);

        if (!$documento) {
            flash()->error('Recurso não existe.');
            return redirect()->back();
        }

        $pessoa = $this->pessoaRepository->find($documento->doc_pes_id);
        $anexo = $this->anexoRepository->find($documento->doc_anx_documento);


        $tiposdocumentos = $this->tipodocumentoRepository->listsTipoDocumentoByDocumentoId($documentoId);

        foreach ($tiposdocumentos as $tipo) {
            $documentotipo = $tipo;
        }

        return view('Geral::documentos.edit', compact('documento', 'documentotipo', 'tiposdocumentos', 'pessoa', 'anexo'));
    }

    public function putEdit($DocumentoId, DocumentoRequest $request)
    {
        $url = $request->session()->get('last_acad_route');
        $id = $request->session()->get('last_id');

        try {
            DB::beginTransaction();

            $documento = $this->documentoRepository->find($DocumentoId);

            if (!$documento) {
                flash()->error('Documento não existe.');
                return redirect()->back();
            }

            $dados = $request->only($this->documentoRepository->getFillableModelFields());
            $dados['doc_anx_documento'] = $documento->doc_anx_documento;


            if ($request->file('doc_file') != null) {
                // Novo Anexo
                $anexoDocumento = $request->file('doc_file');

                if ($documento->doc_anx_documento != null) {
                    // Atualiza anexo
                    $atualizaAnexo = $this->anexoRepository->atualizarAnexo($documento->doc_anx_documento, $anexoDocumento);

                    if($atualizaAnexo['type'] == 'error_non_existent'){
                        flash()->error($anexo['message']);
                        return redirect()->back();
                    }

                    if ($atualizaAnexo['type'] == 'error_exists') {
                        flash()->error($atualizaAnexo['message']);
                        return redirect()->back()->withInput($request->all());
                    }

                    if (!$atualizaAnexo) {
                        flash()->error('ocorreu um problema ao salvar o arquivo');
                        return redirect()->back()->withInput($request->all());
                    }
                } else {
                    // Cria um novo anexo caso o documento nao tenha anteriormente
                    $anexo = $this->anexoRepository->salvarAnexo($anexoDocumento);

                    if ($anexo['type'] == 'error_exists') {
                        flash()->error($anexoCriado['message']);
                        return redirect()->back()->withInput($request->all());
                    }

                    if (!$anexo) {
                        flash()->error('ocorreu um problema ao salvar o arquivo');
                        return redirect()->back()->withInput($request->all());
                    }

                    $dados['doc_anx_documento'] = $anexo->anx_id;
                }
            }

            if (!$this->documentoRepository->update($dados, $documento->doc_id, 'doc_id')) {
                DB::rollBack();
                flash()->error('Erro ao tentar atualizar');
                return redirect()->back()->withInput($request->all());
            }

            DB::commit();

            flash()->success('Documento atualizado com sucesso.');
            return redirect()->route($url, ['id' => $id]);
        } catch (\Exception $e) {
            if (config('app.debug')) {
                throw $e;
            }

            flash()->error('Erro ao tentar atualizar. Caso o problema persista, entre em contato com o suporte.');
            return redirect()->back();
        }
    }

    public function postDelete(Request $request)
    {
        try {
            $documentoId = $request->get('id');
            $documento = $this->documentoRepository->find($documentoId);

            if ($this->documentoRepository->delete($documentoId) && $documento->doc_anx_documento != null) {
                $excluiAnexo = $this->anexoRepository->deletarAnexo($documento->doc_anx_documento);

                if ($excluiAnexo['type'] == 'error_exists') {
                    flash()->error($anexoCriado['message']);
                    return redirect()->back()->withInput($request->all());
                }

                if (!$excluiAnexo) {
                    flash()->error('ocorreu um problema ao excluir o arquivo');
                    return redirect()->back()->withInput($request->all());
                }
            }

            flash()->success('Documento excluído com sucesso.');
            return redirect()->back();
        } catch (\Exception $e) {
            if (config('app.debug')) {
                throw $e;
            }

            flash()->error('Erro ao tentar excluir o documento');
            return redirect()->back();
        }
    }
}
