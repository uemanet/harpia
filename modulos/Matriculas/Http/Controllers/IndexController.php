<?php

namespace Modulos\Matriculas\Http\Controllers;

use App\Http\Controllers\Controller;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Auth;
use Mpdf\Mpdf;
use Modulos\Matriculas\Repositories\SeletivoMatriculaRepository;
use Modulos\Matriculas\Repositories\SeletivoUserRepository;

class IndexController extends Controller
{

    protected $seletivoMatriculaRepository;
    protected $seletivoUserRepository;

    public function __construct(SeletivoMatriculaRepository $seletivoMatriculaRepository, SeletivoUserRepository $seletivoUserRepository)
    {
        $this->seletivoMatriculaRepository = $seletivoMatriculaRepository;
        $this->seletivoUserRepository = $seletivoUserRepository;

        $this->middleware('auth:matriculas-alunos');

    }

    public function getIndex()
    {

        $user = Auth::guard('matriculas-alunos')->user();

//        dd($user->seletivos_matriculas);

//        foreach ( $user->seletivos_matriculas as $item) {
//            dd($item->chamada);
//        }

        return view('Matriculas::matriculas-alunos.index', compact('user'));
    }

    public function getConfirmar($seletivo_matricula_id)
    {

        $user = Auth::guard('matriculas-alunos')->user();

        $seletivo_matricula = $this->seletivoMatriculaRepository->find($seletivo_matricula_id);

//        dd($user->seletivos_matriculas);

//        foreach ( $user->seletivos_matriculas as $item) {
//            dd($item->chamada);
//        }

        return view('Matriculas::matriculas-alunos.confirmarmatricula', compact('user', 'seletivo_matricula'));
    }

    public function postConfirmar($seletivo_matricula_id, Request $request)
    {

        try {
            $user = Auth::guard('matriculas-alunos')->user();

            $seletivo_matricula = $this->seletivoMatriculaRepository->find($seletivo_matricula_id);
            if (!$seletivo_matricula) {
                flash()->error('Banco não existe.');
                return redirect()->route('rh.bancos.index');
            }

            if (!$this->seletivoMatriculaRepository->update(['matriculado' => 1], $seletivo_matricula_id, 'id')) {
                flash()->error('Erro ao tentar salvar.');
                return redirect()->back()->withInput($request->all());
            }

            if (!$this->seletivoUserRepository->update($request->all(), $user->id, 'id')) {
                flash()->error('Erro ao tentar salvar.');
                return redirect()->back()->withInput($request->all());
            }

            flash()->success('Matrícula confirmada com sucesso.');
            return redirect()->route('matriculas-alunos.index.alunos');
        } catch (\Exception $e) {
            if (config('app.debug')) {
                throw $e;
            }

            flash()->error('Erro ao tentar atualizar. Caso o problema persista, entre em contato com o suporte.');
            return redirect()->back();
        }

    }

    public function getComprovante($seletivo_matricula_id, Request $request)
    {

        try {

            $seletivo_matricula = $this->seletivoMatriculaRepository->find($seletivo_matricula_id);

            $user = Auth::guard('matriculas-alunos')->user();

            $date = new Carbon();

            $configs = ['c', 'A4', '', '', 15, 15, 16, 16, 9, 9];
            define('_MPDF_TTFONTDATAPATH', sys_get_temp_dir()."/");
            $mpdf = new mPDF($configs);

            $mpdf->mirrorMargins = 1;
            $mpdf->SetTitle('Comprovante de matrícula');
            $mpdf->SetHeader('{PAGENO} / {nb}');
            $mpdf->SetFooter('Emitido em : ' . $date->format('d/m/Y H:i:s'));
            $mpdf->defaultheaderfontsize = 10;
            $mpdf->defaultheaderfontstyle = 'B';
            $mpdf->defaultheaderline = 0;
            $mpdf->defaultfooterfontsize = 10;
            $mpdf->defaultfooterfontstyle = 'BI';
            $mpdf->defaultfooterline = 0;
            $mpdf->addPage('L');

            $mpdf->WriteHTML(view('Matriculas::matriculas-alunos.comprovante', compact('user', 'date', 'seletivo_matricula'))->render());
            $mpdf->Output();
            exit;
        } catch (\Exception $e) {
            if (config('app.debug')) {
                throw $e;
            }

            flash()->error('Erro ao tentar atualizar. Caso o problema persista, entre em contato com o suporte.');
            return redirect()->back();
        }

    }



}
