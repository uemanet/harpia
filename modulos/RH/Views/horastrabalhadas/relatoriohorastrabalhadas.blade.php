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

</style>

<div align="center" style="font-size:140%"><img src="{{public_path('/img/logo_oficial.png')}}"></div>
<div align="center" style="font-size:140%">Relatório de horas trabalhadas no período:  {{$periodoLaboral->pel_inicio}} as {{$periodoLaboral->pel_termino}}</div>
<br>

<table>
    <thead>
    <tr>
        <th>Colaborador</th>
        <th>Horas Previstas</th>
        <th>Horas Trabalhadas</th>
        <th>Horas Justificadas</th>
        <th>Saldo</th>
    </tr>
    </thead>
    <tbody>
    @foreach ($horasTrabalhadas as $horaTrabalhada)
        <tr>
            <td >{{$horaTrabalhada->pes_nome}}</td>
            <td >{{$horaTrabalhada->htr_horas_previstas}}</td>
            <td >{{$horaTrabalhada->htr_horas_trabalhadas}}</td>
            <td >{{$horaTrabalhada->htr_horas_justificadas}}</td>
            <td >{{$horaTrabalhada->htr_saldo}}</td>
        </tr>
    @endforeach

    </tbody>
</table>

</html>
