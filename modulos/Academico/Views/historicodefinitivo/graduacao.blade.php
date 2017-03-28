<!doctype html>
<html lang="pt_BR">
<head>
    <meta charset="UTF-8">

    <style>

        body {
            font-size: 8pt;
        }
        table {
            width: 100%;
            table-layout: fixed;
            border-spacing: 0;
            /*border: 0.5mm solid #000000;*/
        }

        td {
            border: 0.2mm solid #000000;
            padding: 0.5em;
        }
        .center {
            text-align: center;
        }
    </style>
</head>
<body>
    <table>
        <tbody>
            <tr>
                <td width="50%" class="center">
                    <p>UEMA</p>
                    <p>Pró-Reitoria de Pesquisa e Pós-Graduação</p>
                    <p>Coordenadoria de Pós-Graduação</p>
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
                <td width="50%" class="center"><strong>{{mb_strtoupper($dados['pessoa']['nome'], 'UTF-8')}}</strong></td>
                <td width="50%" class="center">{{str_pad($dados['pessoa']['matricula'], 10, '0', STR_PAD_LEFT)}}</td>
            </tr>
            <tr>
                <td width="50%" class="center"><strong>DATA DE NASCIMENTO</strong></td>
                <td width="50%" class="center"><strong>CARTEIRA DE IDENTIDADE</strong></td>
            </tr>
            <tr>
                <td width="50%" class="center">{{$dados['pessoa']['nascimento']}}</td>
                <td width="50%" class="center">{{$dados['pessoa']['rg']['conteudo']}}</td>
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
                <td width="100%" class="center"><strong>CURSO</strong></td>
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
                <td width="5%"><strong>ITEM</strong></td>
                <td width="40%"><strong>DISCIPLINA</strong></td>
                <td width="35%"><strong>PROFESSOR(TITULAÇÃO)</strong></td>
                <td width="5%"><strong>C.H</strong></td>
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
                <td class="center"><strong>{{mb_strtoupper($dados['tcc']->ltc_titulo, 'UTF-8')}}</strong></td>
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
                <td width="100%" class="center"><strong>REGULAMENTAÇÃO</strong></td>
            </tr>
            <tr>
                <td>
                    "O curso está em conformidade com a Resolução no 01 do CNE/CES, de 08 de junho de 2007 e Normas dos cursos de Pós-Graduação "Lato Sensu" da
                    Universidade Estadual do Maranhão aprovada pela Resolução 909/2009-CEPE/UEMA de 15 de dezembro de 2009".
                </td>
            </tr>
        </tbody>
    </table>
    <table>
        <tbody>
        <tr>
            <td width="50%" class="center"><strong>LOCAL/DATA</strong></td>
            <td width="50%" class="center"><strong>ASSINATURA DO COORDENADOR(A)</strong></td>
        </tr>
        <tr>
            <td class="center">SÃO LUÍS, 28 DE MARÇO DE 2017</td>
            <td></td>
        </tr>
        </tbody>
    </table>
</body>
</html>