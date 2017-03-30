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

        #cabecario table td {
            padding: 0.1em;
        }

        .padding td {
            padding: 0.5em;
        }

        .padding-0_3 td {
            padding: 0.3em;
        }

        .table-bordered {
            border: 0.5mm solid #9d9d9d;
        }

        .center {
            text-align: center;
        }

        .right {
            text-align: right;
        }

        .margin-top {
            margin-top: 1em;
        }

        .border-td td {
            border-bottom: 0.1mm solid #000000;
        }

    </style>
</head>
<body>
    <table class="table-bordered" id="cabecario">
        <tbody>
            <tr>
                <td rowspan="3" width="10%" style="padding: 0.3em;">
                    <img height="10%" src="{{public_path('/img/logo_uema.png')}}">
                </td>
                <td width="50%">Universidade Estadual do Maranhão - <strong>UEMA</strong></td>
                <td width="40%">Polo: <strong>{{mb_strtoupper($dados['polo']->pol_nome, 'UTF-8')}}</strong></td>
            </tr>
            <tr>
                <td>Sistema Acadêmico de Educação à Distância - <strong>SAED/UEMA</strong></td>
                <td>Período: <strong>{{$dados['turma']->periodo_letivo}}</strong></td>
            </tr>
            <tr>
                <td>Educação Profissional Técnica de Nível Médio</td>
                <td>Resolução de Reconhecimento No: <strong>231/2014 - CEE</strong></td>
            </tr>
        </tbody>
    </table>

    <table class="margin-top" style="font-size: 12pt;">
        <tbody>
            <tr>
                <td class="center"><strong>HISTÓRICO ESCOLAR</strong></td>
            </tr>
            <tr>
                <td class="center"><strong>{{mb_strtoupper($dados['curso']->crs_nome, 'UTF-8')}}</strong></td>
            </tr>
        </tbody>
    </table>

    <table class="margin-top">
        <tbody>
            <tr>
                <td><strong>DADOS PESSOAIS</strong></td>
            </tr>
        </tbody>
    </table>
    <table class="table-bordered" style="margin-top: 0.3em;">
        <tbody>
            <tr class="padding">
                <td width="60%">
                    <strong>Nome:</strong> {{mb_strtoupper($dados['pessoa']['nome'], 'UTF-8')}}
                </td>
                <td width="40%">
                    <strong>RG:</strong> {{$dados['pessoa']['rg']['conteudo']}} <strong>Órgão:</strong> {{$dados['pessoa']['rg']['orgao']}}
                </td>
            </tr>
            <tr class="padding">
                <td>
                    <strong>Nascimento:</strong> {{$dados['pessoa']['nascimento']}}
                </td>
                <td>
                    <strong>Data Expedição:</strong> {{$dados['pessoa']['rg']['data_expedicao']}}
                </td>
            </tr>
            <tr class="padding">
                <td>
                    <strong>CPF:</strong> {{$dados['pessoa']['cpf']}}
                </td>
                <td>
                    <strong>Nacionalidade:</strong> {{$dados['pessoa']['nacionalidade']}}
                </td>
            </tr>
            <tr class="padding">
                <td>
                    <strong>Sexo:</strong> {{$dados['pessoa']['sexo']}}
                </td>
                <td>
                    <strong>Naturalidade:</strong> {{$dados['pessoa']['naturalidade']}}
                </td>
            </tr>
            <tr class="padding">
                <td>
                    @php
                        $filiacao = mb_strtoupper($dados['pessoa']['mae'], 'UTF-8');

                        if (!empty($dados['pessoa']['pai'])) {
                            $filiacao .= ' E '.mb_strtoupper($dados['pessoa']['pai'], 'UTF-8');
                        }
                    @endphp
                    <strong>Filiação:</strong> {{$filiacao}}
                </td>
            </tr>
        </tbody>
    </table>

    @php $cargaHorariaTotal = 0; @endphp
    @foreach($dados['modulos'] as $modulo)
        <table class="margin-top" style="page-break-inside: avoid;">
            <tbody>
            <tr>
                <td><strong>{{mb_strtoupper($modulo['nome'], 'UTF-8')}}</strong></td>
            </tr>
            </tbody>
        </table>
        <table class="table-bordered" style="margin-top: 0.3em;">
            <tbody>
                <tr class="padding border-td">
                    <td width="10%"><strong>MD.</strong></td>
                    <td><strong>Disciplina</strong></td>
                    <td width="10%"><strong>CH</strong></td>
                    <td width="10%"><strong>Média Final</strong></td>
                    <td width="25%"><strong>Situação</strong></td>
                </tr>

                @php $cargaHorariaModulo = 0; @endphp
                @foreach($modulo['disciplinas'] as $key => $obj)
                    <tr class="padding border-td">
                        <td>{{$obj->mof_id}}</td>
                        <td>{{$obj->dis_nome}}</td>
                        <td>{{$obj->dis_carga_horaria}}</td>
                        <td>{{number_format($obj->mof_mediafinal, 2)}}</td>
                        <td>
                            @php
                                if ($obj->mof_situacao_matricula == 'aprovado_media') {
                                    echo "APROVADO POR MÉDIA";
                                } elseif ($obj->mof_situacao_matricula == 'aprovado_final') {
                                    echo "APROVADO POR FINAL";
                                } elseif ($obj->mof_situacao_matricula == 'reprovado_media') {
                                    echo "REPROVADO POR MÉDIA";
                                } elseif ($obj->mof_situacao_matricula == 'reprovado_final') {
                                    echo "REPROVADO POR FINAL";
                                } elseif ($obj->mof_situacao_matricula == 'cursando') {
                                    echo "CURSANDO";
                                } elseif ($obj->mof_situacao_matricula == 'cancelado') {
                                    echo "CANCELADO";
                                }
                            @endphp
                        </td>
                    </tr>
                    @php
                        $cargaHorariaTotal += $obj->dis_carga_horaria;
                        $cargaHorariaModulo += $obj->dis_carga_horaria;
                    @endphp
                @endforeach
                <tr class="padding border-td">
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td><strong>Carga Horária:</strong> {{$cargaHorariaModulo}} h</td>
                </tr>
                <tr class="padding-0_3">
                    <td colspan="5">
                        <strong>QUALIFICAÇÃO:</strong> {{$modulo['qualificacao']}}
                    </td>
                </tr>
                <tr class="padding-0_3">
                    <td colspan="5">
                        <strong>COMPETÊNCIAS:</strong> {{$modulo['descricao']}}
                    </td>
                </tr>
            </tbody>
        </table>
    @endforeach

    <table class="table-bordered margin-top">
        <tbody>
            <tr>
                <td class="right">
                    <strong>Carga Horária Total: </strong>{{$cargaHorariaTotal}} h
                </td>
            </tr>
        </tbody>
    </table>

    <table class="margin-top">
        <tbody>
        <tr>
            <td class="center data">
                {{$dados['data']}}
            </td>
        </tr>
        </tbody>
    </table>
</body>
</html>