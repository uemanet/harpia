<html>


<style type="text/css">
    table {
        font-family: sans-serif;
        /*border: 0.5mm solid;*/
        border-collapse: collapse;
        width: 100%;
        table-layout: fixed;

    }


    td, th {
        border: 0.1mm solid;
        padding: 6px;
    }


    .tg td {
        padding: 20px 20px;
    }

    .celula_fixa {
        width: 600px;

        overflow: auto;
    }
</style>

<br>
<h4 style="text-align:center;">UNIVERSIDADE ESTADUAL DO MARANHÃO</h4>
<h4 style="text-align:center;">NÚCLEO DE TECNOLOGIA PARA EDUCAÇÃO - UEMANET</h4>
<h4 style="text-align:center;">EDUCAÇÃO PROFISSIONAL</h4>

<table class="tg">
    <tr>
        <td class="" colspan="15"><b>QUALIFICAÇÃO</b>: {{$dados['QUALIFICACAOMODULO']}}</td>
    </tr>
    <tr>
        <td class="" colspan="15"><b>EIXO:</b> {{$dados['EIXOCURSO']}}</td>
    </tr>
    <tr>
        <td class=" celula_fixa" colspan="9" rowspan="13">

        <b>Componentes Curriculares</b><br>
            @foreach($dados['DISCIPLINAS'] as $disciplina)
                    {{$disciplina}}<br>
            @endforeach
        </td>
        <td class="" colspan="6"><b>REGISTRO</b></td>
    </tr>
    <tr>
        <td class="" colspan="2"><b>Nº LIVRO</b></td>
        <td class="" colspan="2"><b>Nº FOLHA</b></td>
        <td class="" colspan="2"><b>Nº REGISTRO</b></td>
    </tr>
    <tr>
        <td style="text-align:center;" class="" colspan="2" rowspan="1">{{$dados['LIVRO']}}</td>
        <td style="text-align:center;" class="" colspan="2" rowspan="1">{{$dados['FOLHA']}}</td>
        <td style="text-align:center;" class="" colspan="2" rowspan="1">{{$dados['REGISTRO']}}</td>
    </tr>
    <tr>
    </tr>
    <tr>
    </tr>
    <tr>
        <td class="" colspan="6" rowspan="1"></td>
    </tr>
    <tr>
    </tr>
    <tr>
    </tr>
    <tr>
    </tr>
    <tr>
    </tr>
    <tr>
    </tr>
    <tr>
        <td class="" colspan="6" rowspan="2"><b>Observação:</b><br> Coeficiente: <b>{{$dados['COEFICIENTEDOMODULO']}}</b> </td>
    </tr>
    <tr>
    </tr>
</table>

<pagebreak>
    <br><br><br><br><br><br><br><br><br><br><br><br><br><br>
    <p style="top:50%;text-align:center">
      <b>{{$dados['PESSOANOME']}}</b><br><br>
            portador do CPF de número {{$dados['PESSOACPF']}}<br>
            concluiu o módulo <b>{{$dados['DESCRICAOMODULO']}}</b><br>
            assegurando a qualificação em <b>{{$dados['QUALIFICACAOMODULO']}}</b><br>
            com duração de <b>{{$dados['CARGAHORARIAMODULO']}} horas</b><br>
            <br>
            <br>
            <?php
                setlocale(LC_ALL, 'pt_BR', 'pt_BR.utf-8', 'pt_BR.utf-8', 'portuguese');
                date_default_timezone_set('America/Sao_Paulo');
            ?>
            São Luís - MA, {{strftime('%d de %B de %Y', strtotime('today'))}}
    </p>


</html>
