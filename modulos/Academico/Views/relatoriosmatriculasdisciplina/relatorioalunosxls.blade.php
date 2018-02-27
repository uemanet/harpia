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
        width: 6% ;
    }

    .nome {
        width: 27%;
    }

    .email{
        width: 20%;
    }

    .turma{
        width: 10%;
    }

    .polo{
        width: 22%;
    }

    .situacao{
        width: 15%;
    }

</style>

<div align="center" style="font-size:140%"><img src="{{public_path('/img/logo_oficial.png')}}"></div>
<div align="center" style="font-size:140%">Relatório de alunos da Disciplina: {{ $disciplina[0] }}</div>
<div align="center" style="font-size:110%">Emitido em: {{ $date->format('d/m/Y H:i:s') }}</div>
<div align="center" style="font-size:140%">Turma: {{ $turma->trm_nome }}</div>
<br>

<table>
    <thead>
    <tr>
        <th>Matrícula</th>
        <th>Aluno</th>
        <th>Email</th>
        <th>Polo</th>
        <th>Data de nascimento</th>
        <th>Identidade</th>
        <th>Cpf</th>
        <th>Nome do pai</th>
        <th>Nome da mãe</th>
        <th>Situação</th>
    </tr>
    </thead>
    <tbody>


    @foreach ($alunos as $aluno)
        <tr >
            <td>{{$aluno->mat_id}}</td>
            <td>{{$aluno->pes_nome}}</td>
            <td>{{$aluno->pes_email}}</td>
            <td>{{$aluno->pol_nome}}</td>
            <td>{{$aluno->pes_nascimento}}</td>
            <td>{{$aluno->rg}}</td>
            <td>{{$aluno->cpf}}</td>
            <td>{{$aluno->pes_pai}}</td>
            <td>{{$aluno->pes_mae}}</td>
            <td>{{$aluno->situacao_matricula}}</td>
        </tr>
    @endforeach

    </tbody>
</table>

</html>
