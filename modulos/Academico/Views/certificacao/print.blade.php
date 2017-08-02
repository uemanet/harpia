<html>

<style type="text/css">

    .tudo {
      border: 0.4mm solid;
    }
    .divesquerda {
      float: left;
      width: 600px;
      height: 370px;
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

<h4 style="text-align:center;">UNIVERSIDADE ESTADUAL DO MARANHÃO</h4>
<h4 style="text-align:center;">NÚCLEO DE TECNOLOGIA PARA EDUCAÇÃO - UEMANET</h4>
<h4 style="text-align:center;">EDUCAÇÃO PROFISSIONAL</h4>

<div class="tudo" style="font-size:12px">
  <div style="padding: 20px;border: 0.4mm solid;border-top-style:none;border-right-style: none;border-left-style: none;">
    <b>QUALIFICAÇÃO</b>: {{$dados['QUALIFICACAOMODULO']}}
  </div>
  <div style="padding: 20px;border: 0.4mm solid;border-top-style:none;border-right-style: none;border-left-style: none;">
    <b>EIXO:</b> {{$dados['EIXOCURSO']}}
  </div>
  <div class="divesquerda">
    <b style="font-size:12px">COMPONENTES CURRICULARES</b><br>
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

      <div style="height: 40px; float:left; width:120px; border: 0.4mm solid; border-top-style:none; border-left-style:none; border-bottom-style:none;">
          <h4 style="text-align:center">Nº LIVRO</h4>
      </div>

      <div style="height: 40px; margin-left:0px; float:left; width:121px;border: 0.4mm solid;border-top-style:none;border-left-style: none;">
          <h4 style="text-align:center">Nº FOLHA</h4>
      </div>

      <div style="height: 40px; margin-left:0px; border: 0.4mm solid; width:120px; border-top-style:none;border-right-style: none;border-left-style: none;">
          <h4 style="text-align:center">Nº REGISTRO</h4>
      </div>

    </div>

    <div>

      <div style="height: 40px; float:left; width:120px; border: 0.4mm solid; border-top-style:none; border-left-style:none; border-bottom-style:none;">
          <h4 style="text-align:center">{{$dados['LIVRO']}}</h4>
      </div>

      <div style="height: 40px; margin-left:0px; float:left; width:121px;border: 0.4mm solid;border-top-style:none;border-left-style: none;">
          <h4 style="text-align:center">{{$dados['FOLHA']}}</h4>
      </div>

      <div style="height: 40px; margin-left:0px; border: 0.4mm solid; width:120px; border-top-style:none;border-right-style: none;border-left-style: none;">
          <h4 style="text-align:center">{{$dados['REGISTRO']}}</h4>
      </div>

    </div>

    <div style="height: 150px; padding: 20px;border: 0.4mm solid;border-top-style:none;border-right-style: none;border-left-style: none;">

    </div>

    <div style="padding: 20px;">
        <b>Observação:</b><br> Coeficiente: <b>{{$dados['COEFICIENTEDOMODULO']}}</b>
    </div>

  </div>
</div>

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
