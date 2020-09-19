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
<div align="center" style="font-size:140%">Comprovante de matrícula</div>
<div align="center" style="font-size:110%">Emitido em: {{ $date->format('d/m/Y H:i:s') }} </div>
<div align="center" style="font-size:140%"></div>
<br>

<p><strong>Aluno: </strong> {{$user->nome}}</p>
<p><strong>Seletivo: </strong> {{$seletivo_matricula->chamada->seletivo->nome}}</p>
<p><strong>Data de confirmação: </strong> {{date("d/m/Y H:i:s", strtotime($seletivo_matricula->updated_at))}}
</p>
<p><strong>Chamada: </strong> {{ $seletivo_matricula->chamada->nome }}</p>
<p><strong>Tipo de chamada: </strong> {{ $seletivo_matricula->chamada->tipo_chamada}}</p>

</html>
