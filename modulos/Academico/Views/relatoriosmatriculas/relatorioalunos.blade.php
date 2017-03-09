<html>
<style>
    table {
        font-family: sans-serif;
        border: 0.5mm solid;
        border-collapse: collapse;
        width: 100%;

    }

    tr:nth-child(even) {
        background-color: #dddddd;
    }

    td, th {
        border: 0.5mm solid;
        padding: 1px;
    }

</style>

<div align="center" style="font-size:140%"><img src="{{public_path('/img/logo_oficial.png')}}"></div>
<div align="center" style="font-size:140%">Relatório de alunos do Curso: {{$nomecurso->crs_nome}}</div>
<div align="center" style="font-size:110%">Emitido em: {{date("d/m/y")}}</div>
<br>

<table align="center">
    <thead>
    <tr>
        <th>Matrícula</th>
        <th>Aluno</th>
        <th>Email</th>
        <th>Turma</th>
        <th>Polo</th>
        <th>Situação</th>
    </tr>
    </thead>
    <tbody>


    @foreach ($matriculas as $matricula)

        <tr>
            <td align="center">{{$matricula->mat_id}}</td>
            <td>{{$matricula->aluno->pessoa->pes_nome}}</td>
            <td>{{$matricula->aluno->pessoa->pes_email}}</td>
            <td>{{$matricula->turma->trm_nome}}</td>
            <td>{{$matricula->polo->pol_nome}}</td>
            <td>{{$matricula->mat_situacao}}</td>
        </tr>
    @endforeach

    </tbody>
</table>

</html>