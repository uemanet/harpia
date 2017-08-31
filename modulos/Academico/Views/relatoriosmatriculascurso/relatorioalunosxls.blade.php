<html>
<style>
    table {
        font-family: sans-serif;
        border: 0.5mm solid;
        border-collapse: collapse;
        width: 100%;
        table-layout: fixed;

    }

    tr:nth-child(even) {
        background-color: #dddddd;
    }

    td, th {
        border: 0.5mm solid;
        padding: 1px;
    }

    td{
        text-align: justify;
    }

    .matricula {
        width: 8% ;
    }

    .nome {
        width: 26%;
    }

    .email{
        width: 25%;
    }

    .turma{
        width: 10%;
    }

    .polo{
        width: 22%;
    }

    .situacao{
        width: 9%;
    }

</style>

<div align="center" style="font-size:140%"><img src="{{public_path('/img/logo_oficial.png')}}"></div>
<div align="center" style="font-size:140%">Relatório de alunos do Curso: {{$nomecurso->crs_nome}}</div>
<div align="center" style="font-size:110%">Emitido em: {{ $date->format('d/m/Y H:i:s') }} </div>
<div align="center" style="font-size:140%">Turma: {{$turma->trm_nome}}</div>
<br>

<table>
    <thead>
    <tr>
        <th>Matrícula</th>
        <th>Aluno</th>
        <th>Email</th>
        <th>Polo</th>
        <th>Grupo</th>
        <th>Data de nascimento</th>
        <th>Identidade</th>
        <th>Cpf</th>
        <th>Nome do pai</th>
        <th>Nome da mãe</th>
        <th>Situação</th>
    </tr>
    </thead>
    <tbody>
    @foreach ($matriculas as $matricula)
        <tr>
            <td>{{$matricula->mat_id}}</td>
            <td>{{$matricula->pes_nome}}</td>
            <td>{{$matricula->pes_email}}</td>
            <td>{{$matricula->pol_nome}}</td>
            <td>{{$matricula->grp_nome}}</td>
            <td>{{$matricula->pes_nascimento}}</td>
            <td>{{$matricula->rg}}</td>
            <td>{{$matricula->cpf}}</td>
            <td>{{$matricula->pes_pai}}</td>
            <td>{{$matricula->pes_mae}}</td>
            <td>{{ucfirst($matricula->mat_situacao)}}</td>
        </tr>
    @endforeach

    </tbody>
</table>

</html>
