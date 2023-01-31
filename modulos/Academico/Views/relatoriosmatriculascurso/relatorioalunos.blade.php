<html>
<style>
    table {
        font-family: sans-serif;
        border: 0.2mm solid;
        border-collapse: collapse;
        width: 100%;
        table-layout: fixed;
    }

    .table-bordered {
        border: none;
        /*border-collapse: collapse;*/

    }

    tr:nth-child(even) {
        /*background-color: #dddddd;*/
    }

    .tr-alunos th{
        border: 0.1mm solid #000000;
    }
    .border-td td {
        border: 0.1mm solid #000000;
        padding-right: 5px;
        padding-left: 5px;
    }

    td, th {
        padding: 1px;
    }

    td{
        text-align: justify;
    }

    .matricula {
        width: 8% ;
    }

    .nome {
        padding-left: 3px;
        width: 26%;
    }

    .email{
        padding-left: 5px;
        width: 25%;
    }

    .turma{
        width: 10%;
    }

    .polo{
        text-align: center;
        padding-left: 3px;
        width: 22%;
    }

    .situacao{
        text-align: center;
        padding-left: 3px;
        width: 9%;
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

{{--<div align="center" style="font-size:140%"><img src="{{public_path('/img/logo_oficial.png')}}"></div>--}}
{{--<div align="center" style="font-size:140%">Relatório de alunos do Curso: {{$nomecurso->crs_nome}}</div>--}}
{{--<div align="center" style="font-size:110%">Emitido em: {{ $date->format('d/m/Y H:i:s') }} </div>--}}
{{--<div align="center" style="font-size:140%">Turma: {{$turma->trm_nome}}</div>--}}
<table class="table-bordered">
    <tbody>
    <tr>
        <td rowspan="2" width="10%" style="padding: 0.3em;">
            <img height="8%" src="{{public_path('/img/logo_capes.png')}}">
        </td>
        <td width="55%" style="padding-top: 0.8em;">Coordenação de Aperfeiçoamento de Pessoal de Nível Superior - <strong>CAPES</strong></td>
        <td width="35%" style="padding-top: 0.8em;padding-left: 0.8em;">Curso: <b>{{$nomecurso->crs_nome}}</b></td>
    </tr>
    <tr>
        <td style="padding-bottom: 0.8em;">Programa <b>Ciência é 10!</b></td>
        <td style="padding-bottom: 0.8em;padding-left: 0.8em;">Turma: <b>{{ $turma->trm_nome }}</b></td>
    </tr>
    </tbody>
</table>
<br>

<table class="table-alunos">
    <thead class="tr-alunos">
    <tr class="tr-alunos">
        <th>Matrícula</th>
        <th>Aluno</th>
        <th>Email</th>
        <th>Polo</th>
        <th>Situação</th>
    </tr>
    </thead>
    <tbody>
    @foreach ($matriculas as $matricula)
        <tr class="border-td">
            <td align="center" class="matricula">{{$matricula->mat_id}}</td>
            <td class="nome">{{$matricula->pes_nome}}</td>
            <td class="email">{{$matricula->pes_email}}</td>
            <td class="polo">{{$matricula->pol_nome}}</td>
            <td class="situacao">{{ucfirst($matricula->mat_situacao)}}</td>
        </tr>
    @endforeach

    </tbody>
</table>
<footer id="byUemanet">
    <p align="right">DESENVOLVIDO PELO NÚCLEO DE TECNOLOGIAS PARA EDUCAÇÃO – UEMANET/UEMA</p>
</footer>

</html>
