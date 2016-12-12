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
        $tiposdocumentos = $this->tipodocumentoRepository->lists('tpd_id', 'tpd_nome');

        return view('Geral::documentos.create', compact('pessoa', 'tiposdocumentos'));
    }

    public function getDocumentoAnexo($documentoId)
    {
        $documento = $this->documentoRepository->find($documentoId);

        if (!$documento) {
            flash()->error('Documento não existe.');
            return redirect()->back();
        }

        return $this->anexoRepository->recuperarAnexo($documento->doc_anx_documento);
    }

    public function postCreate(DocumentoRequest $request)
    {
        $url = $request->session()->get('last_acad_route');
        $id = $request->session()->get('last_id');

        try {

            DB::beginTransaction();

            $dados = $request->all();

            if ($request->file('doc_file') != null){
                $anexoDocumento = $request->file('doc_file');
                $anexoCriado = $this->anexoRepository->salvarAnexo($anexoDocumento);
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
        $pessoa = $this->pessoaRepository->find($documento->doc_pes_id);

        if (!$documento) {
            flash()->error('Recurso não existe.');
            return redirect()->back();
        }
        $tiposdocumentos = $this->tipodocumentoRepository->listsTipoDocumentoByDocumentoId($documentoId);

        foreach ($tiposdocumentos as $tipo) {
            $documentotipo = $tipo;
        }

        return view('Geral::documentos.edit', compact('documento', 'documentotipo', 'tiposdocumentos', 'pessoa'));
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

//            $dados = $request->only('doc_anx_projeto_pedagogico', 'mtc_descricao', 'mtc_titulo',
//                'mtc_data', 'mtc_creditos', 'mtc_horas', 'mtc_horas_praticas');

            if ($request->file('doc_file') != null) {
                // Novo Anexo
                $anexoDocumento = $request->file('doc_file');
                // Atualiza anexo
                $this->anexoRepository->atualizarAnexo($documento->doc_anx_documento, $anexoDocumento);
            }

            $dados['doc_anx_documento'] = $documento->doc_anx_documento;
            if (!$this->documentoRepository->update($dados, $documento->doc_id, 'doc_id')) {
                DB::rollBack();
                flash()->error('Erro ao tentar atualizar');
                return redirect()->back()->withInput($request->all());
            }

            DB::commit();

//            if (!$this->documentoRepository->update($requestData, $documento->doc_id, 'doc_id')) {
//                flash()->error('Erro ao tentar salvar.');
//                return redirect()->back()->withInput($request->all());
//            }

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
                $this->anexoRepository->deletarAnexo($documento->doc_anx_documento);
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
