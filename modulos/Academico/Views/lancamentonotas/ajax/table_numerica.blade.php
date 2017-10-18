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
            <td>
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
            <td><a href="#" class="btn btn-success btn-salvar-notas"><i class="fa fa-save"></i> Salvar notas</a></td>
        </tr>
    @endforeach
    </tbody>
</table>
<script type="text/javascript">
    $(document).ready(function () {

        function disableFinal(index){
            $('.final_' + index).attr('disabled', true);
        }

        function disableRecuperacao(index){
            $('.recuperacao_' + index).attr('disabled', true);
        }

        function mediaFinal(index) {
            var inputs = document.getElementById(index).getElementsByTagName('input');

            configuracoesCurso = "{{ $configuracoesCurso }}".replace(/&quot;/ig, '"').replace('"[', '[').replace(']"', ']');
            configuracoesCurso = JSON.parse(configuracoesCurso);

            notaMaisBaixa = isNaN(inputs[0].valueAsNumber) ? 0 : inputs[0].valueAsNumber;
            somaDasNotas = 0;

            // 3 primeiras notas
            for(var i = 0; i < 3; i++) {
                if(isNaN(inputs[i].valueAsNumber)) {
                    notaMaisBaixa = 0;
                    continue;
                }

                somaDasNotas += inputs[i].valueAsNumber;

                if(notaMaisBaixa > inputs[i].valueAsNumber) {
                    notaMaisBaixa = inputs[i].valueAsNumber;
                }
            }

            media = (somaDasNotas / 3);

            // Media suficiente para aprovacao
            if(media >= parseFloat(configuracoesCurso.media_min_aprovacao)){
                disableRecuperacao(index);
                disableFinal(index);
            }

            // Recuperacao
            if(!isNaN(inputs[3].valueAsNumber)) {
                if(configuracoesCurso.modo_recuperacao === "substituir_media_final") {
                    media = inputs[3].valueAsNumber;
                }

                if(configuracoesCurso.modo_recuperacao === "substituir_menor_nota") {
                    media = ((somaDasNotas - notaMaisBaixa) + inputs[3].valueAsNumber) / 3;
                }
            }

            // Media suficiente para aprovacao
            if(media >= parseFloat(configuracoesCurso.media_min_aprovacao)){
                disableFinal(index)
            }

            // Final
            if(!isNaN(inputs[4].valueAsNumber)) {
                media = (media + inputs[4].valueAsNumber) / 2;
            }

            return media;
        }

        // Evento alterado
        $('table tr td input').on('input', function(){
            var tableRow = $(this).closest('tr');
            var index = tableRow.attr('id');

            media = mediaFinal(index);

            var mediaCell = $('.media-final_' + index);

            mediaCell.empty();
            mediaCell.append((media).toPrecision(3).toString());
        });

        $('.btn-salvar-notas').on('click', function(){
            var tableRow = $(this).closest('tr');
            var inputs = document.getElementById(tableRow.attr('id')).getElementsByTagName('input');

            for(var i = 0; i < inputs.length; i++) {
                console.log([i, inputs[i].valueAsNumber]);
            }
        });
    });
</script>
