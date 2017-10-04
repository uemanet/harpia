<html lang="pt-br">
<head>
    <script src="{{ asset('/js/jQuery-2.2.0.min.js')}}"></script>
    <style>
        table {
            margin-bottom: 5px;
            font-size: 10px;
        }

        table, tr, td, th {
            /*border-spacing: 0;*/
            border-collapse: collapse;
            border: 1px solid #000;
            position: relative;
            padding: 6px;
        }

        td span {
            transform-origin: 0 50%;
            transform: rotate(-90deg);
            white-space: nowrap;
            display: block;
            position: absolute;
            bottom: 0;
            left: 50%;
        }

        .center {
            text-align: center;
        }

        .pagebreak {
            /*height: 2px;*/
            page-break-before: always;
        }
    </style>
</head>
<body>
    @php
        $colIniciais = 4;
        $colModulos = 0;

        $matrizCurricular = $matriz;

        // Quantidade de colunas para as disciplinas
        foreach (array_pop($matrizCurricular) as $nome => $modulo){
            $colModulos += count($modulo["disciplinas"]);
        }

        $data = "";
        $qtdColunas = $colIniciais + $colModulos + 1;

        $colLinhaInf = array();
        $sub = $qtdColunas - 2;
        $divisao = ceil(($qtdColunas - 2) / 4);

        for($i = 1; $i <= 4; $i++) {
            if($i < 4) {
                $colLinhaInf[$i] = $divisao;
            } else {
                $colLinhaInf[$i] = $sub;
            }

            $sub -= $divisao;
        }

        foreach ($resultados as $nomePolo => $matriculasPolo):
    @endphp
    <table>
        <thead>
            <tr>
                <th class="center" colspan="{{ $qtdColunas }}"><strong>ATAS DE RESULTADOS FINAIS</strong></th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td colspan="{{ $qtdColunas }}">{{ $data }}, foi concluído o processo de apuração das notas finais dos alunos do curso, módulos e turma abaixo relacionados, deste estabelecimento com os respectivos resultados:</td>
            </tr>
            <tr>
                <td colspan="2"><strong>Ano letivo:</strong> {{ date('Y') }}</td>
                <td colspan="{{ $colLinhaInf[1] }}"><strong>Curso:</strong> {{ $curso->crs_nome }}</td>
                <td colspan="{{ $colLinhaInf[2] }}"><strong>Eixo:</strong> {{ $curso->crs_eixo }}</td>
                <td colspan="{{ $colLinhaInf[3] }}"><strong>Polo:</strong> {{ $nomePolo }}</td>
                <td colspan="{{ $colLinhaInf[4] }}"><strong>Turno:</strong> Integral</td>
            </tr>
            <tr>
                <td class="center" colspan="2" rowspan="2"><strong>Módulos</strong></td>
                @php
                    $matrizCurricular = $matriz;
                    $modulos = array_pop($matrizCurricular);
                    unset($modulos["carga_horaria_matriz"]);
                    unset($modulos["VIVÊNCIA PRÁTICA"]);

                    foreach($modulos as $nome => $modulo) {
                        $colspan = count($modulo['disciplinas']);

                        echo '<td class="center" colspan="' . $colspan . '"><strong>' . $nome . '</strong></td>';
                    }
                @endphp
                <td rowspan="3"><span style="bottom: 30%;"><strong>Prática Profissional</strong></span></td>
                <td rowspan="5"><span style="bottom: 30%;"><strong>Resultado Final</strong></span></td>
            </tr>
            <tr>
                @php
                    $tamModulos = 0;
                    $matrizCurricular = $matriz;
                    $modulos = array_pop($matrizCurricular);
                    unset($modulos["carga_horaria_matriz"]);
                    unset($modulos["VIVÊNCIA PRÁTICA"]);

                    foreach($modulos as $nome => $modulo) {
                        $colspan = count($modulo['disciplinas']);
                        $tamModulos += $colspan;

                        echo '<td class="center" colspan="' . $colspan . '"><strong>' . $modulo['descricao'] . '</strong></td>';
                    }
                @endphp
            </tr>
            <tr>
                <td class="center" colspan="2"><strong>Componentes Curriculares</strong></td>
                @php
                    $matrizCurricular = $matriz;
                    $modulos = array_pop($matrizCurricular);
                    unset($modulos["carga_horaria_matriz"]);
                    unset($modulos["VIVÊNCIA PRÁTICA"]);

                    foreach($modulos as $nome => $modulo) {
                       foreach ($modulo['disciplinas'] as $disciplina){
                            echo '<td class="disciplina"><span>' . $disciplina['nome'] . '</span></td>';
                       }
                    }
                @endphp
            </tr>
            <tr>
                <td class="center" colspan="2"><strong>Carga Horária</strong></td>
                @php
                    $matrizCurricular = $matriz;
                    $modulos = array_pop($matrizCurricular);
                    unset($modulos["carga_horaria_matriz"]);

                    foreach($modulos as $nome => $modulo) {
                       foreach ($modulo['disciplinas'] as $disciplina){
                            echo '<td class="center"><span>' . $disciplina['carga_horaria'] . '</span></td>';
                       }
                    }
                @endphp
            </tr>
            <tr>
                <td><strong>Cód.</strong></td>
                <td><strong>Nome do aluno(a)</strong></td>
                <td class="center" colspan="{{ $tamModulos + 1 }}"><strong>Notas</strong></td>
            </tr>
            <tr>
                @php
                    $matrizCurricular = $matriz;
                    $modulos = array_pop($matrizCurricular);
                    unset($modulos["carga_horaria_matriz"]);

                    $html = "";

                    foreach ($matriculasPolo as $aluno => $modulosAluno) {
                        $html .= "<tr>";
                        $html .= '<td>' . $modulosAluno["idAluno"] . '</td>';
                        $html .= '<td>' . $aluno . '</td>';

                        foreach ($modulos as $nomeModulo => $modulo) {
                            foreach ($modulo['disciplinas'] as $nomeDisciplina => $disciplinas) {
                                if(isset($modulosAluno[$nomeModulo]) && isset($modulosAluno[$nomeModulo][$nomeDisciplina])){
                                    $html .= '<td class="center">';
                                    $nota = $modulosAluno[$nomeModulo][$nomeDisciplina]['media'];

                                    if (is_numeric($modulosAluno[$nomeModulo][$nomeDisciplina]['media'])) {
                                        $nota = number_format($modulosAluno[$nomeModulo][$nomeDisciplina]['media'], 2);
                                    }

                                    $html .= $nota .'</td>';
                                    continue;
                                }

                                $html .= '<td class="center"> -- </td>';
                            }
                        }

                        $html .= '<td class="center">'.$modulosAluno['situacao'].'</td>';
                        $html .= '</tr>';
                    }
                @endphp
            </tr>
        </tbody>
        @php
            echo $html;
            endforeach;
        @endphp
    </table>
    <script type="text/javascript">
        $(function() {
            var header_height = 0;
            $('table td span').each(function() {
                if ($(this).outerWidth() > header_height) header_height = $(this).outerWidth();
            });

            $('.disciplina').height(header_height);
        });
    </script>
</body>
</html>