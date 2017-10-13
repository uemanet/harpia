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

        .thead-background th {
            background-color: #007bb6;
            color: white;

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
                <td width="55%" style="padding-top: 0.8em;">Universidade Estadual do Maranhão - <strong>UEMA</strong></td>
                <td width="35%" style="padding-top: 0.8em;padding-left: 0.5em;">Polo: <strong>{{mb_strtoupper($dados['polo']->pol_nome, 'UTF-8')}}</strong></td>
            </tr>
            <tr>
                <td>Sistema Acadêmico de Educação à Distância - <strong>SAED/UEMA</strong></td>
                <td style="padding-left: 0.5em;">Período: <strong>{{$dados['turma']->periodo_letivo}}</strong></td>
            </tr>
            <tr>
                <td style="padding-bottom: 0.8em;">Educação Profissional Técnica de Nível Médio</td>
                <td style="padding-bottom: 0.8em;padding-left: 0.5em;">Resolução de Reconhecimento: {{$dados['curso']->crs_resolucao}}</td>
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

    <table class="table-bordered" style="margin-top: 0.8em;page-break-inside: avoid;">
        <thead>
            <tr class="thead-left thead-config thead-background">
                <th colspan="2" style="border-bottom: 0.1mm solid #000000;">DADOS PESSOAIS</th>
            </tr>
        </thead>
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
        <table class="table-bordered" style="margin-top: 0.8em;page-break-inside: avoid;">
            <thead>
                <tr class="thead-left thead-config thead-background">
                    <th colspan="5">{{mb_strtoupper($modulo['nome'], 'UTF-8')}}</th>
                </tr>
                <tr class="thead-left thead-config thead-border">
                    <th width="10%">MD.</th>
                    <th>Disciplina</th>
                    <th width="10%">CH</th>
                    <th width="10%">Média Final</th>
                    <th width="25%">Situação</th>
                </tr>
            </thead>
            <tbody>
                @php $cargaHorariaModulo = 0; @endphp
                @foreach($modulo['disciplinas'] as $key => $obj)
                    <tr class="padding border-td">
                        <td>{{$obj->mof_id}}</td>
                        <td>{{$obj->dis_nome}}</td>
                        <td>{{$obj->dis_carga_horaria}}</td>
                        <td>@if($obj->ofd_tipo_avaliacao == numerica)
                              {{number_format($obj->mof_mediafinal, 2)}}
                            @else
                              {{$obj->mof_conceito}}
                            @endif</td>
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
                    <td colspan="5" style="border-top: 0.1mm solid #000000;">
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

    <table class="table-bordered margin-top" style="page-break-inside: avoid;">
        <tbody>
            <tr>
                <td class="right" style="font-size: 10pt;">
                    <strong>Carga Horária Total: </strong>{{$cargaHorariaTotal}} h
                </td>
            </tr>
        </tbody>
    </table>

    <table class="margin-top" style="page-break-inside: avoid;">
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
