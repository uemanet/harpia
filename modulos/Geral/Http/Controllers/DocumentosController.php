<?php

namespace Modulos\Geral\Http\Controllers;

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

    public function __construct(DocumentoRepository $documentoRepository,
                                TipoDocumentoRepository $tipodocumentoRepository,
                                PessoaRepository $pessoaRepository)
    {
        $this->documentoRepository = $documentoRepository;
        $this->tipodocumentoRepository = $tipodocumentoRepository;
        $this->pessoaRepository = $pessoaRepository;
    }

    public function getCreate($pessoaId)
    {
        $pessoa = $this->pessoaRepository->find($pessoaId);
        if (!$pessoa) {
            flash()->error('Pessoa não existe');

            return redirect()->back();
        }
        $tiposdocumentos = $this->tipodocumentoRepository->lists('tpd_id', 'tpd_nome');

        return view('Geral::documentos.create', compact('pessoa', 'tiposdocumentos'));
    }

    public function postCreate(DocumentoRequest $request)
    {
        try {
            $documento = $this->documentoRepository->create($request->all());

            if (!$documento) {
                flash()->error('Erro ao tentar salvar.');
                return redirect()->back()->withInput($request->all());
            }

            flash()->success('Documento criado com sucesso.');
            return redirect('/geral/pessoas/show/'.$documento->doc_pes_id);
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
        $documento = $this->documentoRepository->find($documentoId);

        if (!$documento) {
            flash()->error('Recurso não existe.');
            return redirect()->back();
        }
        $tiposdocumentos = $this->tipodocumentoRepository->lists('tpd_id', 'tpd_nome');

        return view('Geral::documentos.edit', compact('documento', 'tiposdocumentos'));
    }

    public function putEdit($id, DocumentoRequest $request)
    {
        try {
            $documento = $this->documentoRepository->find($id);

            if (!$documento) {
                flash()->error('Documento não existe.');
                return redirect('/geral/pessoas/show/' . $id);
            }

            $requestData = $request->only($this->documentoRepository->getFillableModelFields());

            if (!$this->documentoRepository->update($requestData, $documento->doc_id, 'doc_id')) {
                flash()->error('Erro ao tentar salvar.');
                return redirect()->back()->withInput($request->all());
            }

            flash()->success('Documento atualizado com sucesso.');
            return redirect('/academico/pessoas/show/' . $documento->doc_pes_id);
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

            if ($this->documentoRepository->delete($documentoId)) {
                flash()->success('Documento excluída com sucesso.');
            } else {
                flash()->error('Erro ao tentar excluir o documento');
            }

            return redirect()->back();
        } catch (\Exception $e) {
            if (config('app.debug')) {
                throw $e;
            }

            flash()->error('Erro ao tentar salvar. Caso o problema persista, entre em contato com o suporte.');
            return redirect()->back();
        }
    }

}
