<table class="table table-bordered table-striped text-center">
    <thead>
    <tr>
        <th>Aluno</th>
        <th width="10%">Nota 1</th>
        <th width="10%">Nota 2</th>
        <th width="10%">Nota 3</th>
        <th width="10%">Recuperação</th>
        <th width="10%">Final</th>
        <th>Média final</th>
        <th width="15%">Situação</th>
        <th width="15%">Ação</th>
    </tr>
    </thead>
    <tbody>
    @foreach($matriculas as $matricula)
        <tr id="{{ $loop->index }}">
            <td>{{ $matricula->getAluno->pessoa->pes_nome }}</td>
            <td>{!! Form::number('nota_1', $matricula->mof_nota1 ? number_format($matricula->mof_nota1, 2) : $matricula->mof_nota1, ['class' => 'form-control', 'min' => 0, 'max' => 10, 'step' => 0.01, 'required']); !!}</td>
            <td>{!! Form::number('nota_2', $matricula->mof_nota2 ? number_format($matricula->mof_nota2, 2) : $matricula->mof_nota2, ['class' => 'form-control', 'min' => 0, 'max' => 10, 'step' => 0.01, 'required']); !!}</td>
            <td>{!! Form::number('nota_3', $matricula->mof_nota3 ? number_format($matricula->mof_nota3, 2) : $matricula->mof_nota3, ['class' => 'form-control', 'min' => 0, 'max' => 10, 'step' => 0.01, 'required']); !!}</td>
            <td>{!! Form::number('recuperacao', $matricula->mof_recuperacao ? number_format($matricula->mof_recuperacao, 2) : $matricula->mof_recuperacao , ['class' => 'form-control recuperacao_' . $loop->index, 'min' => 0, 'max' => 10, 'step' => 0.01]); !!}</td>
            <td>{!! Form::number('final', $matricula->mof_final ? number_format($matricula->mof_final, 2) : $matricula->mof_final, ['class' => 'form-control final_' . $loop->index, 'min' => 0, 'max' => 10, 'step' => 0.01]); !!}</td>
            <td class="media-final_{{$loop->index}}">{{ $matricula->mof_mediafinal ? number_format($matricula->mof_mediafinal, 2) : $matricula->mof_mediafinal }}</td>
            <td class="situacao_{{$loop->index}}">
                @if(str_contains($matricula->situacao_matricula, 'Cursando'))
                    <span class="label bg-blue">{{$matricula->situacao_matricula}}</span>
                @elseif(str_contains($matricula->situacao_matricula, 'Aprovado'))
                    <span class="label bg-green">{{$matricula->situacao_matricula}}</span>
                @elseif(str_contains($matricula->situacao_matricula, 'Reprovado'))
                    <span class="label bg-red">{{$matricula->situacao_matricula}}</span>
                @elseif(str_contains($matricula->situacao_matricula, 'Cancelado'))
                    <span class="label bg-black">{{$matricula->situacao_matricula}}</span>
                @endif
            </td>
            <td><a href="#" class="btn btn-success btn-salvar-notas" id="{{$matricula->mof_id}}"><i
                            class="fa fa-save"></i> Salvar notas</a></td>
        </tr>
    @endforeach
    </tbody>
</table>
<script type="text/javascript">
    $(document).ready(function () {

        function disableFinal(index) {
            $('.final_' + index).attr('disabled', true);
        }

        function disableRecuperacao(index) {
            $('.recuperacao_' + index).attr('disabled', true);
        }

        function enableFinal(index) {
            $('.final_' + index).attr('disabled', false);
        }

        function enableRecuperacao(index) {
            $('.recuperacao_' + index).attr('disabled', false);
        }

        function atualizaSituacao(index, situacao) {
            situacaoCell = $('.situacao_' + index);

            situacaoCell.empty();

            if (situacao === 'aprovado_media') {
                situacaoCell.append('<span class="label bg-green">Aprovado por média</span>');
            }

            if (situacao === 'aprovado_final') {
                situacaoCell.append('<span class="label bg-green">Aprovado por final</span>');
            }

            if (situacao === 'reprovado_media') {
                situacaoCell.append('<span class="label bg-red">Reprovado por média</span>');
            }

            if (situacao === 'reprovado_final') {
                situacaoCell.append('<span class="label bg-red">Reprovado por final</span>');
            }

            if (situacao === 'cursando') {
                situacaoCell.append('<span class="label bg-blue">Cursando</span>');
            }
        }

        function situacaoMatricula(index) {
            enableRecuperacao(index);
            enableFinal(index);
            inputs = document.getElementById(index).getElementsByTagName('input');
            situacao = 'aprovado_media';

            configuracoesCurso = "{{ $configuracoesCurso }}".replace(/&quot;/ig, '"').replace('"[', '[').replace(']"', ']');
            configuracoesCurso = JSON.parse(configuracoesCurso);

            notaMaisBaixa = isNaN(inputs[0].valueAsNumber) ? 0 : inputs[0].valueAsNumber;
            somaDasNotas = 0;

            // 3 primeiras notas
            for (var i = 0; i < 3; i++) {
                if (isNaN(inputs[i].valueAsNumber)) {
                    notaMaisBaixa = 0;
                    continue;
                }

                somaDasNotas += inputs[i].valueAsNumber;

                if (notaMaisBaixa > inputs[i].valueAsNumber) {
                    notaMaisBaixa = inputs[i].valueAsNumber;
                }
            }

            media = (somaDasNotas / 3);

            // Media suficiente para aprovacao ou
            if (media >= parseFloat(configuracoesCurso.media_min_aprovacao)) {
                disableRecuperacao(index);
                disableFinal(index);
                situacao = 'aprovado_media';
                atualizaSituacao(index, situacao);
            } else {
                situacao = 'reprovado_media';
                atualizaSituacao(index, situacao);
            }

            // Recuperacao
            if (!isNaN(inputs[3].valueAsNumber) && inputs[3].valueAsNumber !== 0) {
                if (configuracoesCurso.modo_recuperacao === "substituir_media_final") {
                    media = inputs[3].valueAsNumber;
                }

                if (configuracoesCurso.modo_recuperacao === "substituir_menor_nota") {
                    media = ((somaDasNotas - notaMaisBaixa) + inputs[3].valueAsNumber) / 3;
                }
            }

            // Media suficiente para aprovacao
            if (media >= parseFloat(configuracoesCurso.media_min_aprovacao)) {
                disableFinal(index);
                situacao = 'aprovado_media';
                atualizaSituacao(index, situacao);
            } else {
                situacao = 'reprovado_media';
                atualizaSituacao(index, situacao);
            }

            if (media < parseFloat(configuracoesCurso.media_min_final)) {
                disableFinal(index);
            }

            // Final
            if (!isNaN(inputs[4].valueAsNumber) && inputs[3].valueAsNumber !== 0) {
                media = (media + inputs[4].valueAsNumber) / 2;

                if (media >= parseFloat(configuracoesCurso.media_min_aprovacao_final)) {
                    situacao = 'aprovado_final';
                    atualizaSituacao(index, situacao);
                } else {
                    situacao = 'reprovado_final';
                    atualizaSituacao(index, situacao);
                }
            }

            data = {
                mof_nota1: isNaN(inputs[0].valueAsNumber) ? 0 : inputs[0].valueAsNumber,
                mof_nota2: isNaN(inputs[1].valueAsNumber) ? 0 : inputs[1].valueAsNumber,
                mof_nota3: isNaN(inputs[2].valueAsNumber) ? 0 : inputs[2].valueAsNumber,
                mof_recuperacao: isNaN(inputs[3].valueAsNumber) ? null : inputs[3].valueAsNumber,
                mof_final: isNaN(inputs[4].valueAsNumber) ? null : inputs[4].valueAsNumber,
                mof_mediafinal: media,
                mof_situacao_matricula: situacao
            };

            return data;
        }

        // Evento alterado
        $('table tr td input').on('input', function () {
            var tableRow = $(this).closest('tr');
            var index = tableRow.attr('id');

            matricula = situacaoMatricula(index);

            var mediaCell = $('.media-final_' + index);

            mediaCell.empty();
            mediaCell.append((matricula.mof_mediafinal).toPrecision(3).toString());
        });

        $('.btn-salvar-notas').on('click', function () {
            tableRow = $(this).closest('tr');
            inputs = document.getElementById(tableRow.attr('id')).getElementsByTagName('input');

            data = situacaoMatricula(tableRow.attr('id'));
            data.mof_id = $(this).attr('id');
            data._token = "{{ csrf_token() }}";

            $.ajax({
                method: 'POST',
                data: data,
                url: "{{ route('academico.async.lancamentonotas.create') }}",
                success: function (response) {
                  toastr.success('Notas atualizadas com sucesso.', null, {progressBar: true});
                },
                error: function (response) {
                    $.harpia.hideloading();
                    toastr.error('Erro ao processar requisição. Entrar em contato com o suporte.', null, {progressBar: true});
                }
            });
        });
    });
</script>
