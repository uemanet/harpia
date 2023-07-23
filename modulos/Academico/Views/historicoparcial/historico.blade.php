<!doctype html>
<html lang="pt_BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">

    <style>

        body {
            font-size: 10pt;
        }

        .data {
            font-size: 12pt;
        }

        table {
            width: 100%;
            table-layout: fixed;
            border-spacing: 0;
            border-radius: 0.5em;
        }

        .cabecario td {
            padding: 0.2em;
        }

        .padding td {
            padding: 0.5em;
        }

        .padding-0_3 td {
            padding: 0.3em;
        }

        .table-bordered {
            border: 0.5mm solid black;
            border-collapse: collapse;
        }

        .center {
            text-align: center;
        }

        .thead-left th {
            text-align: left;
        }

        .thead-config th {
            padding: 0.5em;
        }

        .thead-border th {
            border-top: 0.1mm solid #000000;
        }

        .margin-top {
            margin-top: 1em;
        }

        .border-td td {
            border-top: 0.1mm solid #000000;
        }

        .background-thead th {
            background-color: #007bb6;
            color: white;
        }

        #byUemanet {
            font-family: monospace;
            position: absolute;
            font-size: xx-small;
            right: 10px;
            bottom: 0;
            width: 100%;
            height: 2.5rem;
        }

    </style>
</head>
<body>
<table class="table-bordered">
    <tbody>
    <tr>
        <td rowspan="2" width="10%" style="padding: 0.3em;">
            <img height="10%" src="{{public_path('/img/logo_capes.png')}}">
        </td>
        <td width="55%" style="padding-top: 0.8em;">Coordenação de Aperfeiçoamento de Pessoal de Nível Superior - <strong>CAPES</strong></td>
        <td width="35%" style="padding-top: 0.8em;padding-left: 0.8em;">Polo: <b>{{mb_strtoupper($matricula->polo->pol_nome, 'UTF-8')}}</b></td>
    </tr>
    <tr>
        <td style="padding-bottom: 0.8em;">Programa <b>Ciência é 10!</b></td>
        <td style="padding-bottom: 0.8em;padding-left: 0.8em;">Período: <b>{{$matricula->turma->periodo->per_nome}}</b></td>
    </tr>
    </tbody>
</table>

<table class="margin-top" style="font-size: 12pt;">
    <tbody>
    <tr>
        <td class="center"><strong>HISTÓRICO PARCIAL</strong></td>
    </tr>
    <tr>
        <td class="center"><strong>{{mb_strtoupper($curso->crs_nome, 'UTF-8')}}</strong></td>
    </tr>
    </tbody>
</table>

<table class="table-bordered" style="margin-top: 0.8em;page-break-inside: avoid;">
    <thead>
        <tr class="thead-left thead-config background-thead">
            <th colspan="2" style="border-bottom: 0.1mm solid #000000;">DADOS PESSOAIS</th>
        </tr>
    </thead>
    <tbody>
    <tr class="padding">
        <td width="60%">
            <strong>Nome:</strong> {{mb_strtoupper($aluno->pessoa->pes_nome, 'UTF-8')}}
        </td>
        <td>
            <strong>CPF:</strong> {{Format::mask($aluno->cpf, '###.###.###-##')}}
        </td>
    </tr>
    <tr class="padding">
        <td>
            <strong>Sexo:</strong> @php echo ($aluno->pessoa->pes_sexo == 'M') ? 'MASCULINO' : 'FEMININO'; @endphp
        </td>
    </tr>
    </tbody>
</table>

@php $cargaHorariaTotal = 0; $coeficienteParcial = 0; @endphp
@foreach($gradeCurricular as $periodo)
    <table class="table-bordered" style="margin-top: 0.8em;">
        <thead>
            <tr class="thead-left thead-config background-thead">
                <th colspan="12">Período: {{mb_strtoupper($periodo['per_nome'], 'UTF-8')}}</th>
            </tr>
            <tr class="thead-left thead-config thead-border">
                <th width="25%">Disciplina</th>
                <th width="10%">Tipo</th>
                <th width="15%">Módulo</th>
                <th width="8%">CH</th>
                <th width="15%">Média Final</th>
                <th width="27%">Situação</th>
            </tr>
        </thead>
        <tbody>
        @foreach($periodo['ofertas_disciplinas'] as $disciplina)
            <tr class="padding border-td">
                <td style="border-bottom: 0.1mm solid;">{{$disciplina->dis_nome}}</td>

                <td style="border-bottom: 0.1mm solid;">
                  @php
                      if ($disciplina->mdc_tipo_disciplina == 'obrigatoria') {
                          echo "OBR";
                      } elseif ($disciplina->mdc_tipo_disciplina == 'eletiva') {
                          echo "ELT";
                      } elseif ($disciplina->mdc_tipo_disciplina == 'tcc') {
                          echo "TCC";
                      } elseif ($disciplina->mdc_tipo_disciplina == 'optativa') {
                          echo "OPT";
                      }
                  @endphp
                </td>
                <td style="border-bottom: 0.1mm solid;">{{$disciplina->mdo_nome}}</td>
                <td style="border-bottom: 0.1mm solid;">{{$disciplina->dis_carga_horaria}} h</td>
                <td style="border-bottom: 0.1mm solid;">{{$disciplina->mof_mediafinal}}</td>
                @php
                    if ($disciplina->mof_mediafinal != '---'):
                        $coeficienteParcial += ($disciplina->dis_carga_horaria * $disciplina->mof_mediafinal);
                    endif;
                @endphp
                <td style="border-bottom: 0.1mm solid;">
                    @php
                        if ($disciplina->mof_tipo_matricula == 'aproveitamento'){
                            echo "APROVEITAMENTO";
                        } else if ($disciplina->mof_situacao_matricula == 'aprovado_media') {
                            echo "APROVADO POR MÉDIA";
                        } elseif ($disciplina->mof_situacao_matricula == 'aprovado_final') {
                            echo "APROVADO POR FINAL";
                        } elseif ($disciplina->mof_situacao_matricula == 'reprovado_media') {
                            echo "REPROVADO POR MÉDIA";
                        } elseif ($disciplina->mof_situacao_matricula == 'reprovado_final') {
                            echo "REPROVADO POR FINAL";
                        } elseif ($disciplina->mof_situacao_matricula == 'cursando') {
                            echo "CURSANDO";
                        } elseif ($disciplina->mof_situacao_matricula == 'cancelado') {
                            echo "CANCELADO";
                        }
                    @endphp
                </td>
            </tr>
            @php
                if (in_array($disciplina->mof_situacao_matricula, ['aprovado_media', 'aprovado_final']))
                {
                    $cargaHorariaTotal += $disciplina->dis_carga_horaria;
                }
            @endphp
        @endforeach
        </tbody>
    </table>
@endforeach

<table class="table-bordered margin-top">
    <tbody>
    <tr>
        <td width="50%">
            <strong>Carga Horária Total: </strong>{{$cargaHorariaTotal}} horas
        </td>
        <td width="50%" align="right">
            @php
                $coeficienteGeral = 0;
                if ($cargaHorariaTotal>0) {
                    $coeficienteGeral = number_format($coeficienteParcial/$cargaHorariaTotal, 2);
                }
            @endphp
            <strong>Coeficiente de Rendimento: </strong>{{$coeficienteGeral}}
        </td>
    </tr>
    </tbody>
</table>

<table class="margin-top">
    <tbody>
    <tr>
        <td class="center data">
            {{$data}}
        </td>
    </tr>
    </tbody>
</table>
<footer id="byUemanet">
    <p align="right">DESENVOLVIDO PELO NÚCLEO DE TECNOLOGIAS PARA EDUCAÇÃO – UEMANET/UEMA.</p>
</footer>
</body>
</html>
