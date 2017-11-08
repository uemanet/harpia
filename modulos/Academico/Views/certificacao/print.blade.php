<html>

<style type="text/css">

    .tudo {
      border: 0.4mm solid;
      margin-right: 10px;
    }
    .divesquerda {
      float: left;
      width: 600px;
      height: 390px;
      border: 0.4mm solid;
      border-top-style: none;
      border-bottom-style: none;
      border-left-style: none;
      padding: 20px;

    }
    .divdireita {
      /*border: 0.4mm solid;*/
      height: 360px;
      margin-left:0px;

    }
</style>

<h4 style="font-family: Arial; text-align:center;">UNIVERSIDADE ESTADUAL DO MARANHÃO - UEMA</h4>
<h4 style="font-family: Arial; text-align:center;">NÚCLEO DE TECNOLOGIAS PARA EDUCAÇÃO - UEMANET</h4>
<h4 style="font-family: Arial; text-align:center;">EDUCAÇÃO PROFISSIONAL</h4>

<div class="tudo" style="font-family: Arial; font-size:12px">
  <div style="font-size:16px; padding: 10px;border: 0.4mm solid;border-top-style:none;border-right-style: none;border-left-style: none;">
    <b>QUALIFICAÇÃO</b>: {{$dados['QUALIFICACAOMODULO']}}
  </div>
  <div style="font-size:16px; padding: 10px;border: 0.4mm solid;border-top-style:none;border-right-style: none;border-left-style: none;">
    <b>EIXO:</b> {{$dados['EIXOCURSO']}}
  </div>
  <div class="divesquerda" style="font-size:16px">
    <b >COMPONENTES CURRICULARES</b><br>
          <p style="margin-left:20px">
            @foreach($dados['DISCIPLINAS'] as $disciplina)
                    {{$disciplina}}<br>
            @endforeach
          </p>
  </div>

  <div class="divdireita">
    <div style="padding: 20px;border: 0.4mm solid;border-top-style:none;border-right-style: none;border-left-style: none;">
          <b>REGISTRO</b>
    </div>

    <div>

      <div style="height: 40px; float:left; width:115px; border: 0.4mm solid; border-top-style:none; border-left-style:none; border-bottom-style:none;">
          <h4 style="text-align:center">Nº LIVRO</h4>
      </div>

      <div style="height: 40px; margin-left:0px; float:left; width:121px;border: 0.4mm solid;border-top-style:none;border-left-style: none;">
          <h4 style="text-align:center">Nº FOLHA</h4>
      </div>

      <div style="height: 40px; margin-left:0px; border: 0.4mm solid; width:115px; border-top-style:none;border-right-style: none;border-left-style: none;">
          <h4 style="text-align:center">Nº REGISTRO</h4>
      </div>

    </div>

    <div>

      <div style="height: 40px; float:left; width:115px; border: 0.4mm solid; border-top-style:none; border-left-style:none; border-bottom-style:none;">
          <h4 style="text-align:center">{{$dados['LIVRO']}}</h4>
      </div>

      <div style="height: 40px; margin-left:0px; float:left; width:121px;border: 0.4mm solid;border-top-style:none;border-left-style: none;">
          <h4 style="text-align:center">{{$dados['FOLHA']}}</h4>
      </div>

      <div style="height: 40px; margin-left:0px; border: 0.4mm solid; width:115px; border-top-style:none;border-right-style: none;border-left-style: none;">
          <h4 style="text-align:center">{{$dados['REGISTRO']}}</h4>
      </div>

    </div>

    <div style="height: 150px; padding: 20px;border: 0.4mm solid;border-top-style:none;border-right-style: none;border-left-style: none;">

    </div>

    <div style="padding: 10px;">
        <b>Observação:</b><br>
        <p style="font-size:14px; margin-left:40px">Coeficiente: <b>{{$dados['COEFICIENTEDOMODULO']}}</b></p>
    </div>

  </div>
</div>

<pagebreak>

    <br><br><br><br><br><br><br><br><br><br><br><br><br><br>

    <p style="font-family: Arial;text-align:center; font-size:16px;">
      <b style="font-size:24px;">{{$dados['PESSOANOME']}}</b><br><br>
            portador do CPF de número {{$dados['PESSOACPF']}}<br>
            concluiu o Módulo <b>{{$dados['DESCRICAOMODULO']}}</b><br>
            assegurando a qualificação em <b>{{$dados['QUALIFICACAOMODULO']}}</b><br>
            com duração de <b>{{$dados['CARGAHORARIAMODULO']}} horas</b>.<br>
            <br>
            <br>
            <?php
                setlocale(LC_ALL, 'pt_BR', 'pt_BR.utf-8', 'pt_BR.utf-8', 'portuguese');
                date_default_timezone_set('America/Sao_Paulo');
            ?>
            São Luís - MA, {{strftime('%d de %B de %Y', strtotime('today'))}}
    </p>

</html>
