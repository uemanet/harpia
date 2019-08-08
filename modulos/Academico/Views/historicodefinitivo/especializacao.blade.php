<!doctype html>
<html lang="pt_BR">
<head>
    <meta charset="UTF-8">

    <style>

        body {
            font-size: 9pt;
        }
        table {
            width: 100%;
            table-layout: fixed;
            border-spacing: 0;
            /*border: 0.5mm solid #000000;*/
        }

        td {
            border: 0.2mm solid #000000;
            padding: 0.2em;
        }

        .heightMin td{
            height: 7em;
            vertical-align: top;
        }

        .heightAssinatura td {
            height: 5em;
        }

        .center {
            text-align: center;
        }

        .centerAssinatura {
            text-align: center;
        }
    </style>
</head>
<body>
<table>
    <tbody>
    <tr>
        <td width="50%" class="center">
            UEMA<br>
            Pró-Reitoria de Pesquisa e Pós-Graduação<br>
            Coordenadoria de Pós-Graduação
        </td>
        <td width="50%" class="center">
            <strong>HISTÓRICO ESCOLAR</strong>
        </td>
    </tr>
    <tr>
        <td width="50%" class="center"><strong>NOME</strong></td>
        <td width="50%" class="center"><strong>CÓDIGO</strong></td>
    </tr>
    <tr>
        <td width="50%" class="center">{{mb_strtoupper($dados['pessoa']['nome'], 'UTF-8')}}</td>
        <td width="50%" class="center">{{str_pad($dados['pessoa']['matricula'], 10, '0', STR_PAD_LEFT)}}</td>
    </tr>
    <tr>
        <td width="50%" class="center"><strong>DATA DE NASCIMENTO</strong></td>
        <td width="50%" class="center"><strong>CARTEIRA DE IDENTIDADE</strong></td>
    </tr>
    <tr>
        <td width="50%" class="center">{{$dados['pessoa']['nascimento']}}</td>
        <td width="50%" class="center">{{$dados['pessoa']['rg']['conteudo']}} {{$dados['pessoa']['rg']['orgao']}}</td>
    </tr>
    <tr>
        <td width="50%" class="center"><strong>NATURALIDADE</strong></td>
        <td width="50%" class="center"><strong>NACIONALIDADE</strong></td>
    </tr>
    <tr>
        <td width="50%" class="center">{{mb_strtoupper($dados['pessoa']['naturalidade'], 'UTF-8')}}</td>
        <td width="50%" class="center">{{mb_strtoupper($dados['pessoa']['nacionalidade'], 'UTF-8')}}</td>
    </tr>

    </tbody>
</table>
<table>
    <tbody>
        <tr>
            <td width="50%" class="center"><strong>CURSO SUPERIOR</strong></td>
            <td width="17%" class="center"><strong>INSTITUIÇÃO</strong></td>
            <td width="17%" class="center"><strong>SEDE</strong></td>
            <td width="16%" class="center"><strong>CONCLUSÃO</strong></td>
        </tr>
        <tr>
            @if ($dados['pessoa']['graduacao'])
                <td width="50%" class="center">{{$dados['pessoa']['graduacao']->tin_titulo}}</td>
                <td width="17%" class="center">{{$dados['pessoa']['graduacao']->tin_instituicao_sigla}}</td>
                <td width="17%" class="center">{{$dados['pessoa']['graduacao']->tin_instituicao_sede}}</td>
                <td width="16%" class="center">{{$dados['pessoa']['graduacao']->tin_anofim}}</td>
            @else
                <td width="50%" class="center">---</td>
                <td width="17%" class="center">---</td>
                <td width="17%" class="center">---</td>
                <td width="16%" class="center">---</td>
            @endif
        </tr>
    </tbody>
</table>
<table>
    <tbody>
    <tr>
        <td width="100%" class="center"><strong>CURSO DE ESPECIALIZAÇÃO</strong></td>
    </tr>
    <tr>
        <td width="100%" class="center">{{mb_strtoupper($dados['curso']->crs_nome, 'UTF-8')}}</td>
    </tr>
    </tbody>
</table>
<table>
    <tbody>
    <tr>
        <td width="20%" class="center"><strong>INSTITUIÇÃO</strong></td>
        <td width="30%" class="center"><strong>RESOLUÇÃO CEPE/ANO</strong></td>
        <td width="30%" class="center"><strong>RESOLUÇÃO CONSUN/ANO</strong></td>
        <td width="20%" class="center"><strong>PERIODO</strong></td>
    </tr>
    <tr>
        <td class="center">UEMA</td>
        <td class="center">{{$dados['curso']->crs_resolucao}}</td>
        <td class="center">{{$dados['curso']->crs_autorizacao}}</td>
        <td class="center">{{$dados['turma']->periodo_letivo}}</td>
    </tr>
    </tbody>
</table>
<table>
    <tbody>
    <tr>
        <td width="7%"><strong>ITEM</strong></td>
        <td><strong>DISCIPLINA</strong></td>
        <td><strong>PROFESSOR(TITULAÇÃO)</strong></td>
        <td width="6%"><strong>C.H</strong></td>
        <td width="5%"><strong>CR</strong></td>
        <td width="10%"><strong>NOTA FINAL</strong></td>
    </tr>
    @php
        $cargaHorariaTotal = 0;
        $creditosTotal = 0;
    @endphp
    @foreach($dados['disciplinas'] as $disciplina)
        <tr>
            <td>{{$loop->index+1}}</td>
            <td>{{$disciplina->dis_nome}}</td>
            <td>
                @php
                    $name = $disciplina->professor;

                    if (!empty($disciplina->professor_titulacao)) {
                        $name .= ' ('.$disciplina->professor_titulacao.')';
                    }

                    if ($disciplina->mdc_tipo_disciplina == 'tcc') {
                        $name = $dados['tcc']->pes_nome.' ('.$dados['tcc']->prf_titulacao.')';
                    }

                    echo mb_strtoupper($name, 'UTF-8');
                @endphp
            </td>
            <td>{{$disciplina->dis_carga_horaria}}</td>
            <td>{{$disciplina->dis_creditos}}</td>
            <td>{{number_format($disciplina->mof_mediafinal, 2)}}</td>
        </tr>
        @php
            $cargaHorariaTotal += $disciplina->dis_carga_horaria;
            $creditosTotal += $disciplina->dis_creditos;
        @endphp
    @endforeach
    <tr class="heightMin">
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
    </tr>
    </tbody>
</table>
<table>
    <tbody>
    <tr>
        <td width="80%" class="center"><strong>TITULO DO TRABALHO DE CONCLUSÃO DE CURSO (TCC)</strong></td>
        <td width="5%" class="center">{{$cargaHorariaTotal}}</td>
        <td width="5%" class="center">{{$creditosTotal}}</td>
        <td width="10%" class="center">Aprovado</td>
    </tr>
    <tr>
        <td class="center"><strong>{{$dados['tcc']->ltc_titulo}}</strong></td>
        <td colspan="3" class="center">
            <p><strong>DATA</strong></p>
            <p>{{$dados['tcc']->ltc_data_apresentacao}}</p>
        </td>
    </tr>
    </tbody>
</table>
<table>
    <tbody>
    <tr>
        <td class="center"><strong>REGULAMENTAÇÃO</strong></td>
    </tr>
    <tr>
        <td style="text-align: justify;">“O Curso está em conformidade com a Resolução n ° 01 CNE/CES de 08 de junho de 2007 e Normas dos cursos de PósGraduação “Lato Sensu” da Universidade Estadual do Maranhão aprovada pela Resolução N° 1244/2017-CEPE/UEMA de 04 de
            abril de 2017”.</td>
    </tr>
    </tbody>
</table>
<table>
    <tbody>
    <tr>
        <td width="50%" class="center"><strong>LOCAL/DATA</strong></td>
        <td width="50%" class="center"><strong>ASSINATURA DO COORDENADOR(A)</strong></td>
    </tr>
    <tr class="heightAssinatura">
        <td class="center">
            <p>{{mb_strtoupper($dados['data'], 'UTF-8')}}</p>
        </td>
        <td></td>
    </tr>
    </tbody>
</table>
</body>
</html>
