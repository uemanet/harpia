<!doctype html>
<html lang="pt_BR">
<head>
    <meta charset="UTF-8">
    <style>

        body {
            font-size: 10pt;
            font-family: sans-serif;
            line-height: 0.2;
        }

        .bloco {
            margin-bottom: 2.0em;
            background-color: #969696;
            border-radius: 0.5em;
            padding: 0.5em;
            width: 100%;
        }

        .radius {
            border-radius: 0.4em;
        }

        table {
            font-family: sans-serif;
            width: 100%;
            table-layout: fixed;
            border: 0.5mm solid #000000;
        }

        td{
            text-align: justify;
        }

        tr:nth-child(even) {
            background-color: #a0a0a0;
        }

    </style>
</head>
<body>
    <div class="bloco">
        <div class="radius" style="background-color: white;">
            <div align="left" style="width: 15%;float: left;padding: 0.2em;">
                <img height="80%" src="{{public_path('/img/logo_uema.png')}}">
            </div>
            <div class="radius" style="padding: 0.1em;margin-top: 0.5em;">
                <p>Universidade Estadual do Maranhão - <strong>UEMA</strong></p>
                <p>Sistema Acadêmico de Educação a Distância - <strong>SAED/UEMA</strong></p>
                <p>Núcleo de Tecnologias Educacionais - <strong>UEMANET</strong></p>
            </div>
        </div>
        <div style="margin-top: 0.3em;">
            <h3 style="text-align: center">HISTÓRICO PARCIAL</h3>
            <h3 style="text-align: center">{{$curso->crs_nome}}</h3>
        </div>
    </div>
    <div class="bloco">
        <h4>DADOS DO ALUNO</h4>
        <div class="radius" style="background-color: white; padding: 0.2em;">
            <div style="width: 50%;float: left;">
                <p><strong>Nome: </strong>{{$aluno->pessoa->pes_nome}}
                <p><strong>CPF: </strong>{{Format::mask($aluno->cpf, '###.###.###-##')}}</p>
            </div>
            <div style="width: 50%;">
                <p><strong>Turma: </strong>{{$matricula->turma->trm_nome}}</p>
                <p><strong>Polo: </strong>{{$matricula->polo->pol_nome}}</p>
            </div>
        </div>
    </div>

    <?php $cargaHorariaTotal = 0; $coeficienteParcial = 0; ?>
    @foreach($gradeCurricular as $periodo)
        <div class="bloco">
            <h4>Período Letivo: {{$periodo['per_nome']}}</h4>
            <div class="radius" style="background-color: white;">
                <table>
                    <tr>
                        <td style="width: 8%;"><strong>MD.</strong></td>
                        <td><strong>Disciplina</strong></td>
                        <td><strong>Módulo</strong></td>
                        <td style="width: 10%;"><strong>CH</strong></td>
                        <td style="width: 15%;"><strong>Média Final</strong></td>
                        <td><strong>Situação</strong></td>
                    </tr>
                    @foreach($periodo['ofertas_disciplinas'] as $disciplina)
                        <tr>
                            <td>{{$disciplina->mof_id}}</td>
                            <td>{{$disciplina->dis_nome}}</td>
                            <td>{{$disciplina->mdo_nome}}</td>
                            <td>{{$disciplina->dis_carga_horaria}}</td>
                            <td>{{$disciplina->mof_mediafinal}}</td>
                            <?php
                                if($disciplina->mof_mediafinal != '---'):
                                    $coeficienteParcial += ($disciplina->dis_carga_horaria * $disciplina->mof_mediafinal);
                                endif;
                            ?>
                            @if ($disciplina->mof_situacao_matricula == 'cursando')
                                <td>Cursando</td>
                            @elseif ($disciplina->mof_situacao_matricula == 'cancelado')
                                <td>Cancelado</td>
                            @elseif($disciplina->mof_situacao_matricula == 'aprovado_media')
                                <td>Aprovado Por Média</td>
                            @elseif($disciplina->mof_situacao_matricula == 'aprovado_final')
                                <td>Aprovado Por Final</td>
                            @elseif($disciplina->mof_situacao_matricula == 'reprovado_media')
                                <td>Reprovado Por Média</td>
                            @elseif($disciplina->mof_situacao_matricula == 'reprovado_final')
                                <td>Reprovado Por Final</td>
                            @endif
                        </tr>
                        <?php $cargaHorariaTotal += $disciplina->dis_carga_horaria; ?>
                    @endforeach
                </table>
            </div>
        </div>
    @endforeach

    <div class="bloco" style="padding: 1em;">
        <div style="width: 50%; float: left">
            <strong>Carga Horária Total: </strong>{{$cargaHorariaTotal}} horas
        </div>
        <?php
            $coeficienteGeral = number_format($coeficienteParcial/$cargaHorariaTotal, 2);
        ?>
        <div style="width: 50%;">
            <strong>Coeficiente de Rendimento: </strong>{{$coeficienteGeral}}
        </div>
    </div>

    <?php
        setlocale(LC_ALL, 'pt_BR', 'pt_BR.utf-8', 'pt_BR.utf-8', 'portuguese');
        date_default_timezone_set('America/Sao_Paulo');
    ?>

    <div>
        <h4 style="text-align: center;">São Luís, {{strftime('%d de %B de %Y', strtotime('today'))}}</h4>
    </div>
</body>
</html>