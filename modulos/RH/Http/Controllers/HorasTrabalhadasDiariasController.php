<?php

namespace Modulos\RH\Http\Controllers;

use Carbon\Carbon;
use Carbon\Traits\Creator;
use Maatwebsite\Excel\Facades\Excel;
use Modulos\Core\Http\Controller\BaseController;
use Modulos\RH\Repositories\ColaboradorRepository;
use Modulos\RH\Repositories\HoraTrabalhadaDiariaRepository;
use Illuminate\Http\Request;
use Modulos\RH\Repositories\HoraTrabalhadaRepository;
use Modulos\RH\Repositories\PeriodoLaboralRepository;
use Mpdf\Mpdf;
use Validator;
use DB;

class HorasTrabalhadasDiariasController extends BaseController
{
    protected $horaTrabalhadaDiariaRepository;
    protected $horaTrabalhadaRepository;
    protected $colaboradorRepository;
    protected $periodoLaboralRepository;

    public function __construct(HoraTrabalhadaDiariaRepository $horaTrabalhadaDiariaRepository,
                                HoraTrabalhadaRepository $horaTrabalhadaRepository,
                                ColaboradorRepository $colaboradorRepository,
                                PeriodoLaboralRepository $periodoLaboralRepository)

    {
        $this->horaTrabalhadaDiariaRepository = $horaTrabalhadaDiariaRepository;
        $this->horaTrabalhadaRepository = $horaTrabalhadaRepository;
        $this->colaboradorRepository = $colaboradorRepository;
        $this->periodoLaboralRepository = $periodoLaboralRepository;

    }

    public function postImport(Request $request): \Illuminate\Http\RedirectResponse
    {
        try {
            $data = Excel::toArray(new \stdClass(), $request->file('csv_file'))[0];

            DB::beginTransaction();
            $menorEMaiorDatasImportadas = $this->horaTrabalhadaDiariaRepository->importarDadosDeHorasTrabalhadas($data);

            $periodosQueDevemSerSincronizados = $this->periodoLaboralRepository
                ->buscaPeriodosLaboraisEntreDatas($menorEMaiorDatasImportadas['menorData'], $menorEMaiorDatasImportadas['maiorData']);


            foreach ($periodosQueDevemSerSincronizados as $periodo){
                $this->horaTrabalhadaRepository->sincronizarHorasTrabalhadas($periodo);
            }
            DB::commit();

            flash()->success('Dados Importados com sucesso.');
            return redirect()->route('rh.horastrabalhadas.index');
        } catch (\Exception $e) {
            DB::rollback();
            if (config('app.debug')) {
                throw $e;
            }

            flash()->error('Erro ao tentar salvar. Caso o problema persista, entre em contato com o suporte.');
            return redirect()->back();
        }
    }

    public function getColaboradorHorasTrabalhadasDiariasPorPeriodoLaboral(int $colaboradorId,int $periodoLaboralId, Request $request)
    {
        $colaborador = $this->colaboradorRepository->find($colaboradorId);

        if (!$colaborador) {
            flash()->error('Colaborador não existe.');
            return redirect()->back();
        }

        $periodoLaboral = $this->periodoLaboralRepository->find($periodoLaboralId);
        if (!$periodoLaboral) {
            flash()->error('Período Laboral não existe.');
            return redirect()->back();
        }

        $paginacao = null;
        $tabela = null;

        $request['htd_col_id'] = $colaboradorId;
        $request['pel_inicio'] = $periodoLaboral->getRawOriginal('pel_inicio');
        $request['pel_termino'] = $periodoLaboral->getRawOriginal('pel_termino');
        $tableData = $this->horaTrabalhadaDiariaRepository->paginateRequest($request->all());

        if ($tableData->count()) {
            $tabela = $tableData->columns(array(
                'htd_data' => 'Data',
                'htd_horas' => 'Horas Trabalhadas',
            ))
                ->sortable(array('htd_data', 'htd_horas'));

            $paginacao = $tableData->appends($request->except('page'));
        }

        return view('RH::colaboradores.horastrabalhadasdiarias', ['colaborador' => $colaborador ,'tabela' => $tabela, 'paginacao' => $paginacao]);
    }

    public function postPdf(Request $request)
    {
        $rules = [
            'pel_id' => 'required'
        ];

        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator);
        }

        $data = $request->all();

        $periodoLaboral = $this->periodoLaboralRepository->find($data['pel_id']);
        if (!$periodoLaboral) {
            flash()->error('Período Laboral não existe.');
            return redirect()->back();
        }

        $horasTrabalhadas = $this->horaTrabalhadaDiariaRepository->buscarDadosParaRelatorioDeHorasTrabalhadas($data['pel_id'], $data['set_id'] ?? null);

        $date = new Carbon();

        $configs = ['c', 'A4', '', '', 15, 15, 16, 16, 9, 9];
        define('_MPDF_TTFONTDATAPATH', sys_get_temp_dir()."/");
        $mpdf = new mPDF($configs);

        $mpdf->mirrorMargins = 1;
        $mpdf->SetTitle('Relatório de horas trabalhadas');
        $mpdf->SetHeader('{PAGENO} / {nb}');
        $mpdf->SetFooter('Emitido em : ' . $date->format('d/m/Y H:i:s'));
        $mpdf->defaultheaderfontsize = 10;
        $mpdf->defaultheaderfontstyle = 'B';
        $mpdf->defaultheaderline = 0;
        $mpdf->defaultfooterfontsize = 10;
        $mpdf->defaultfooterfontstyle = 'BI';
        $mpdf->defaultfooterline = 0;
        $mpdf->addPage('L');



        $mpdf->WriteHTML(view('RH::horastrabalhadas.relatoriohorastrabalhadas', compact('horasTrabalhadas', 'date', 'periodoLaboral'))->render());
        $mpdf->Output();
        exit;

    }
}