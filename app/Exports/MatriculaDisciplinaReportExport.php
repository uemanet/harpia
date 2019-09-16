<?php

namespace App\Exports;

use Carbon\Carbon;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Events\AfterSheet;
use Maatwebsite\Excel\Events\BeforeExport;


class MatriculaDisciplinaReportExport implements FromCollection, WithMapping, WithHeadings, ShouldAutoSize, WithEvents
{

    protected $invoices;
    protected $matriculas;
    protected $disciplina;
    protected $turma;

    public function __construct($invoices,  $matriculas, $disciplina, $turma)
    {
        $this->invoices = $invoices;
        $this->matriculas = $matriculas;
        $this->disciplina = $disciplina;
        $this->turma = $turma;
    }

    /**
     * @return array
     */
    public function registerEvents(): array
    {
        return [

            AfterSheet::class => function(AfterSheet $event) {
                $event->sheet->getDelegate()->mergeCells('A1:K1');
                $event->sheet->getDelegate()->mergeCells('A2:K2');
                $event->sheet->getDelegate()->mergeCells('A3:K3');
                $event->sheet->styleCells(
                    'A1:K1',
                    [
                        'font' => [
                            'bold' => true,
                        ]
                    ]
                );
                $event->sheet->styleCells(
                    'A2:K2',
                    [
                        'font' => [
                            'bold' => true,
                        ]
                    ]
                );
                $event->sheet->styleCells(
                    'A3:K3',
                    [
                        'font' => [
                            'bold' => true,
                        ]
                    ]
                );
                $event->sheet->styleCells(
                    'A4:K4',
                    [
                        'font' => [
                            'bold' => true,
                        ]
                    ]
                );
            },

        ];
    }

    public function headings(): array
    {

        $date = new Carbon();

        return [['Relatorio de matrículas da disciplina '.$this->disciplina[0]],
            ['Emitido em: ' . $date->format('d/m/Y H:i:s')],
            ['Turma: ' . $this->turma->trm_nome],
            [
                'Matrícula',
                'Aluno',
                'Email',
                'Polo',
                'Data de Nascimento',
                'Identidade',
                'CPF',
                'Nome do Pai',
                'Nome da Mãe',
                'Situação'
        ]];
    }


    /**
     * @var  $matricula
     * @return array
     */
    public function map($matricula): array
    {

        return [
                $matricula->mat_id,
                $matricula->pes_nome,
                $matricula->pes_email,
                $matricula->pol_nome,
                $matricula->pes_nascimento,
                $matricula->rg,
                $matricula->cpf,
                $matricula->pes_pai,
                $matricula->pes_mae,
                $matricula->mof_situacao_matricula
        ];
    }

    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {

        return $this->matriculas;
    }
}
