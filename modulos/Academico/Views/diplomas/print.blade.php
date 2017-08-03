<html>

<style type="text/css">

    .tudo {
      margin-left:30px;
      margin-right: 56px;
      border: 0.4mm solid;

    }
    .divesquerda {
      float: left;
      width: 350px;
      height: 360px;
      border: 0.4mm solid;
      border-top-style: none;
      border-bottom-style: none;
      border-left-style: none;
      padding: 20px 20px 0px 20px;

    }
    .divdireita {
      /*border: 0.4mm solid;*/
      height: 360px;
      margin-left:350px;

    }
</style>
@foreach($retorno as $dados)
    <br>
    <br>
      <h4 style="font-family: Arial; margin-left:30px;font-weight: normal;"> EIXO: <b>{{$dados['EIXOCURSO']}} </b> </h4>
      <h4 style="font-family: Arial; margin-left:30px;font-weight: normal;"> HABILITAÇÃO: <b>{{$dados['HABILITAÇÂO']}}</b> </h4>
    <br>
    <br>
    <!-- HTML -->
    <div class="tudo" style="font-family: Arial; font-size:13px">

      <div class="divesquerda">
                <p style="text-align:center"><b>UNIVERSIDADE ESTADUAL DO MARANHÃO</b><br>
                  <b>REGISTRO DE DIPLOMAS</b><br><br>
                  Registrado sob o N° <b>{{$dados['REGISTRO']}}</b><br><br>
                  Livro N° <b>{{$dados['LIVRO']}}</b> FLS N° <b>{{$dados['FOLHA']}} </b><br><br>
                  Processo N° <b>{{$dados['PROCESSO']}}</b><br><br><br><br><br>
                  <?php
                      setlocale(LC_ALL, 'pt_BR', 'pt_BR.utf-8', 'pt_BR.utf-8', 'portuguese');
                      date_default_timezone_set('America/Sao_Paulo');
                  ?>
                  SÃO LUÍS-MA, <u>{{strftime('%d', strtotime('today'))}}</u>/<u>{{strftime('%m', strtotime('today'))}}</u>/<u>{{strftime('%Y', strtotime('today'))}}</u><br><br><br><br><br>
                  _________________________________________________<br>
                  <b>NOME DO PROFISSIONAL RESPONSÁVEL</b><br><br>
              </p>
              <b style="margin-left: 40px;font-size:9 px;">DIPLOMA VÁLIDO EM TODO TERRITÓRIO NACIONAL</b>
      </div>

      <div class="divdireita">
        <div style="padding: 20px 20px 0px 20px;border: 0.4mm solid;border-top-style:none;border-right-style: none;border-left-style: none;">
                Observação: Curso de Educação Profissional Técnica de Nível Médio - Subsequente<br><br><br>

                 ASSINATURA ANVERSO:<br><br>
                 Nome do Diretor do Centro: <b>{{$dados['DIRETORCENTRO']}}</b><br>
                 Matrícula: <b>{{$dados['MATRICULADIRETORCENTRO']}}</b><br><br><br>

                 Nome do Diretor do Curso: <b>{{$dados['DIRETORCURSO']}}</b><br>
                 Matrícula: <b>{{$dados['MATRICULADIRETORCURSO']}}</b><br><br>
        </div>
        <div style="padding: 20px 20px 0px 20px;">
                N°. de Registro no SISTEC: <b>{{$dados['REGISTROSISTEC']}}</b><br><br><br>
                CARGA HORÁRIA DA PRÁTICA PROFISSIONAL: <b>{{$dados['CHPRATICA']}}</b><br><br>
                CARGA HORÁRIA TOTAL DO CURSO: <b>{{$dados['CHTOTAL']}}</b><br><br><br>
                <b>Resolução de Reconhecimento N°</b>: {{$dados['RESOLUCAO']}} de {{strftime('%d de %B de %Y', $dados['DATA_AUTORIZACAO'])}}
        </div>

      </div>
    </div>

    <pagebreak>

        <br><br><br><br><br><br><br><br><br><br><br><br><br>
        <p style="font-size:14pt; font-family: Arial;margin-left:0px; margin-right:50px; text-align:justify; line-height:1.5">
            A UNIVERSIDADE ESTADUAL DO MARANHÃO, por meio do {{$dados['CENTRO']}}, tendo em vista a conclusão do Curso Técnico de Nível Médio em {{$dados['CURSO']}}, em {{strftime('%d de %B de %Y', $dados['CONCLUSAO'])}}, confere o título de <b>{{$dados['HABILITAÇÂO']}}</b> a <b>{{$dados['NOME']}}</b>, nascido(a) aos {{$dados['DIAEXTENSO']}} {{strftime('dias do mês de %B de %Y', $dados['NASCIMENTO'])}}, nacionalidade {{$dados['NACIONALIDADE']}}, natural de {{$dados['NATURALIDADE']}}, carteira de identidade N° {{$dados['IDENTIDADE']}} / {{$dados['ORGAO']}}, outorga-lhe o  presente Diploma a fim de que possa gozar de todos os direitos e prerrogativas legais.
        </p>

        <p  style="font-size:18px; font-family: Arial;margin-left:0px; margin-right:50px; text-align:right">
            São Luís (MA), {{strftime('%d de %B de %Y', strtotime('today'))}}<br>
        </p>

    </html>

    @if(!$loop->last)
        <pagebreak>
    @endif

@endforeach
