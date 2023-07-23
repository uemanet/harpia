<?php

namespace Modulos\Academico\Http\Controllers;

use Auth;
use ActionButton;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Facades\Excel;
use Modulos\Core\Http\Controller\BaseController;
use Modulos\Geral\Http\Requests\PessoaRequest;
use Modulos\Geral\Repositories\PessoaRepository;
use Modulos\Academico\Repositories\AlunoRepository;
use Modulos\Geral\Repositories\DocumentoRepository;
use Modulos\Seguranca\Repositories\UsuarioRepository;

class ImportacoesController extends BaseController
{
    protected $alunoRepository;
    protected $pessoaRepository;
    protected $documentoRepository;
    protected $usuarioRepository;

    public function __construct(AlunoRepository $aluno,
                                PessoaRepository $pessoa,
                                DocumentoRepository $documento,
                                UsuarioRepository $usuario)
    {
        $this->alunoRepository = $aluno;
        $this->pessoaRepository = $pessoa;
        $this->documentoRepository = $documento;
        $this->usuarioRepository = $usuario;
    }

    public function getIndex(Request $request)
    {
        return view('Academico::importacao.index');
    }

    public function postImportar(Request $request)
    {
        $alunosParaImportar = Excel::toArray(new \stdClass(), $request->file('doc_file'))[0];

        $dataPessoas = [];

        foreach ($alunosParaImportar as $item) {
            $pessoa = array(
                'pes_nome' => $item[0],
                'pes_email' => $item[1],
                'pes_telefone' => $item[2],
                'pes_sexo' => $item[3],
                'doc_conteudo' => (string)$item[4]
            );
            $cpf = $item[4];

            $pessoaRequest = new PessoaRequest();
            $validator = Validator::make($pessoa, $pessoaRequest->rules());
            if ($validator->fails()) {
                dd($validator->getMessageBag());
                flash()->error('Dados inválidos para a pessoa com cpf: '.$cpf);
                return redirect()->back();
            }

            if (strlen((string)$cpf) !== 11) {
                flash()->error('CPF inválido: '.$cpf);
                return redirect()->back();
            }

            $dataPessoas[] = $pessoa ;

            $dataForm = $request->all();

        }

        flash()->success('Pessoas importadas com sucesso!');
        return view('Academico::importacao.index');
    }

}
