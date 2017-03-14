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
            background-color: #7a869d;
        }

    </style>
</head>
<body>
    <div class="bloco">
        <div class="radius" style="background-color: white;">
            <div align="left" style="width: 15%;float: left;padding: 0.2em;">
                <img height="80%" src="{{public_path('/img/logo_uema.png')}}">
            </div>
            <div class="radius" style="padding: 0.1em;">
                <p>Universidade Estadual do Maranhão</p>
                <p>Sistema Acadêmico de Educação a Distância - <strong>SAED/UEMA</strong></p>
                <p><strong>Polo: </strong>{{$matricula->polo->pol_nome}}</p>
            </div>
        </div>
        <h3 style="text-align: center">HISTÓRICO PARCIAL</h3>
        <h3 style="text-align: center">{{$curso->crs_nome}}</h3>
    </div>
    <div class="bloco">
        <h4>DADOS PESSOAIS</h4>
        <div class="radius" style="background-color: white; padding: 0.2em;">
            <p><strong>Nome: </strong>{{$aluno->pessoa->pes_nome}}
            <p><strong>Turma: </strong>{{$matricula->turma->trm_nome}}</p>
        </div>
    </div>


    @foreach($gradeCurricular as $periodo)
        <div class="bloco">
            <h4>Período Letivo: {{$periodo['per_nome']}}</h4>
            <div class="radius" style="background-color: white;">
                <table>
                    <tr>
                        <td style="width: 10%;"><strong>MD.</strong></td>
                        <td><strong>Disciplina</strong></td>
                        <td style="width: 10%;"><strong>CH</strong></td>
                        <td><strong>Média Final</strong></td>
                        <td><strong>Situação</strong></td>
                    </tr>
                    @foreach($periodo['ofertas_disciplinas'] as $disciplina)
                        <tr>
                            <td>{{$disciplina->mof_id}}</td>
                            <td>{{$disciplina->dis_nome}}</td>
                            <td>{{$disciplina->dis_carga_horaria}}</td>
                            <td>{{$disciplina->mof_mediafinal}}</td>
                            <td>{{ucfirst($disciplina->mof_situacao_matricula)}}</td>
                        </tr>
                    @endforeach
                </table>
            </div>
        </div>
    @endforeach

    <?php
        setlocale(LC_ALL, 'pt_BR', 'pt_BR.utf-8', 'pt_BR.utf-8', 'portuguese');
        date_default_timezone_set('America/Sao_Paulo');
    ?>

    <div>
        <h4 style="text-align: center;">São Luís, {{strftime('%d de %B de %Y', strtotime('today'))}}</h4>
    </div>
</body>
</html>