<!doctype html>
<html lang="pt_BR">
<head>
    <meta charset="UTF-8">

    <style>

        body {
            font-size: 11pt;
        }

        table {
            width: 100%;
            table-layout: fixed;
            border-spacing: 0;
        }

        .table-bordered {
            border: 0.2mm solid #000000;
        }

        td, th {
            padding: 0.4em;
        }

        th {
            text-align: left;
            border-bottom: 0.2mm solid #000000;
        }

        .zebra tr:nth-child(even) {
            background-color: #f2f2f2;
        }

        .table-data {
            margin-top: 10pt;
            border-top: 0.5mm solid #000000;
        }

    </style>
</head>
<body>

    <table class="table-bordered">
        <tbody>
            <tr>
                <td><strong>SMTT - PROCESSO DE HABILITAÇÃO DE ESTUDANTE AO BENEFÍCIO DA MEIA PASSAGEM</strong></td>
            </tr>
            <tr>
                <td><strong>INSTITUIÇÃO: 7201 - UEMA - UNIVERSIDADE ESTADUAL DO MARANHÃO/MATRIZ</strong></td>
            </tr>
            <tr>
                <td><strong>REMESSA: {{$lista->lst_id}}</strong></td>
            </tr>
        </tbody>
    </table>

    <table class="table-bordered zebra">
        <thead>
            <tr>
                <th>NOME</th>
                <th>MÃE</th>
                <th>DATA NASC.</th>
                <th>CURSO</th>
                <th>GRAU</th>
                <th>SERIE</th>
                <th>TURNO</th>
            </tr>
        </thead>
        <tbody>
            @foreach($matriculas as $matricula)
                <tr>
                    <td>{{$matricula->aluno->pessoa->pes_nome}}</td>
                    <td>{{$matricula->aluno->pessoa->pes_mae}}</td>
                    <td>{{$matricula->aluno->pessoa->pes_nascimento}}</td>
                    <td style="text-align: center;">998</td>
                    <td style="text-align: center;">3</td>
                    <td style="text-align: center;">1</td>
                    <td style="text-align: center;">I</td>
                </tr>
            @endforeach
            <tr>
                <td colspan="7" style="border-top: 0.2mm solid #000000;"><strong>TOTAL DE ALUNOS: {{$matriculas->count()}}</strong></td>
            </tr>
        </tbody>
    </table>

    <table class="table-data">
        <tr>
            <td><strong>EMISSÃO: {{\Carbon\Carbon::now(config('app.timezone'))->format('d/m/Y H:i:s')}}</strong></td>
        </tr>
    </table>
</body>
</html>