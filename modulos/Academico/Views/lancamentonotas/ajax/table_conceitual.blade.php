@php
 $opcoes = [
    'Insuficiente' => 'Insuficiente',
    'Regular' => 'Regular',
    'Bom' => 'Bom',
    'Muito Bom' => 'Muito Bom',
    'Excelente' => 'Excelente'
];
@endphp
<table class="table table-bordered table-striped text-center">
    <thead>
    <tr>
        <th>Aluno</th>
        <th>Avaliação</th>
        <th width="15%">Situação</th>
        <th width="15%">Ação</th>
    </tr>
    </thead>
    <tbody>
    @foreach($matriculas as $matricula)
        <tr id="{{ $loop->index }}">
            <td>{{ $matricula->getAluno->pessoa->pes_nome }}</td>
            <td>{!! Form::select('conceito', $opcoes , $matricula->mof_conceito, ['class' => 'form-control select-control', 'id' => 'select_'.$loop->index]) !!}</td>
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
        function atualizaSituacao(index, situacao) {
            situacaoCell = $('.situacao_' + index);

            situacaoCell.empty();

            if (situacao === 'aprovado_media') {
                situacaoCell.append('<span class="label bg-green">Aprovado por média</span>');
            }

            if (situacao === 'reprovado_media') {
                situacaoCell.append('<span class="label bg-red">Reprovado por média</span>');
            }

            if (situacao === 'cursando') {
                situacaoCell.append('<span class="label bg-blue">Cursando</span>');
            }
        }

        function situacaoMatricula(index) {
            input = document.getElementById('select_' + index);
            situacao = 'aprovado_media';

            configuracoesCurso = "{{ $configuracoesCurso }}".replace(/&quot;/ig, '"').replace('"[', '[').replace(']"', ']');
            configuracoesCurso = JSON.parse(configuracoesCurso);

            // Conceito de aprovacao
            if(configuracoesCurso.conceitos_aprovacao.indexOf(input.value) === -1) {
                situacao = 'reprovado_media';
            }

            atualizaSituacao(index, situacao);

            data = {
                mof_conceito: input.value,
                mof_situacao_matricula: situacao
            };

            return data;
        }

        // Evento alterado
        $('table tr td select').on('input', function () {
            var tableRow = $(this).closest('tr');
            var index = tableRow.attr('id');

            matricula = situacaoMatricula(index);
        });

        $('.btn-salvar-notas').on('click', function () {
            tableRow = $(this).closest('tr');
            inputs = document.getElementById(tableRow.attr('id')).getElementsByTagName('select');

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
